<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\UserConsultation;
use App\Models\User;
use App\Models\Questionnaire;
use App\Models\TrackingType;
use App\Services\ActivityTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class ConsultationController extends Controller
{
    public function bookConsultation(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to book a consultation',
                'requires_auth' => true
            ]);
        }

        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'payment_method_id' => 'nullable|string',
            'coupon_code' => 'nullable|string'
        ]);

        $user = Auth::user();
        $consultation = Consultation::findOrFail($request->consultation_id);

        // Validate coupon if provided
        $finalPrice = $consultation->price;
        $couponCode = $request->coupon_code;
        
        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)
                ->where('status', 1)
                ->first();
                
            if ($coupon) {
                $currentDateTime = \Carbon\Carbon::now();
                
                // Check if coupon is valid
                if ($currentDateTime->gte($coupon->start_date) && $currentDateTime->lte($coupon->end_date)) {
                    // Check if coupon is applicable to this specific consultation
                    $isApplicableToConsultation = $coupon->consultations()->where('consultations.id', $consultation->id)->exists();
                    
                    if (!$isApplicableToConsultation) {
                        return response()->json([
                            'success' => false,
                            'message' => 'This coupon is not applicable to the selected consultation.'
                        ]);
                    }
                    
                    // Check usage limits
                    if ($coupon->max_uses == 0 || $coupon->usage_count < $coupon->max_uses) {
                        // Check user usage
                        $userUsageCount = \App\Models\CouponUsage::where('coupon_id', $coupon->id)
                            ->where('user_id', $user->id)
                            ->count();
                            
                        if ($coupon->uses_per_user == 0 || $userUsageCount < $coupon->uses_per_user) {
                            // Apply discount
                            if ($coupon->type === 'percentage') {
                                $discountAmount = ($finalPrice * $coupon->value) / 100;
                                $finalPrice = max(0, $finalPrice - $discountAmount);
                            } elseif ($coupon->type === 'fixed') {
                                $finalPrice = max(0, $finalPrice - $coupon->value);
                            }
                            
                            // Track coupon usage with same logic as PaymentController
                            $this->trackCouponUsage($coupon, $user, $consultation, $finalPrice, $couponCode);
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'You have already used this coupon.'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Coupon usage limit has been reached.'
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Coupon is not valid at this time.'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.'
                ]);
            }
        }

        DB::beginTransaction();

        try {
            // Remove the check that prevents multiple bookings of the same consultation
            // Users can now book the same consultation multiple times
            
            $paymentIntentId = null;
            $status = 'pending';

            // If payment is required, process with Stripe
            if ($finalPrice > 0) {
                // Check if payment method is provided
                if (!$request->payment_method_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment method is required for paid consultations.'
                    ]);
                }

                Stripe::setApiKey(config('services.stripe.secret'));

                $paymentIntent = PaymentIntent::create([
                    'amount' => $finalPrice * 100, // Convert to cents
                    'currency' => 'aud',
                    'payment_method' => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('front.consultations')
                ]);

                if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                    DB::rollBack();
                    return response()->json([
                        'requires_action' => true,
                        'payment_intent_client_secret' => $paymentIntent->client_secret,
                    ]);
                } elseif ($paymentIntent->status !== 'succeeded') {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Payment failed.']);
                }

                $paymentIntentId = $paymentIntent->id;
                $status = $paymentIntent->status;
            } else {
                // Free consultation - no payment required
                $status = 'completed';
            }

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'plan_id' => 0, // No plan for consultations
                'consultation_id' => $consultation->id,
                'price' => $finalPrice,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'payment_intent_id' => $paymentIntentId,
                'status' => $status,
                'coupon_code' => $request->coupon_code
            ]);

            // Create user consultation record
            UserConsultation::create([
                'user_id' => $user->id,
                'consultation_id' => $consultation->id
            ]);

            // Record coupon usage if coupon was applied
            if ($couponCode && isset($coupon)) {
                \App\Models\CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'discount_amount' => $consultation->price - $finalPrice
                ]);
                
                // Update coupon usage count
                $coupon->increment('usage_count');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Consultation booked successfully!',
                'payment_id' => $payment->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // If payment was successful but booking failed, we need to refund the payment
            if ($finalPrice > 0 && isset($paymentIntentId) && $paymentIntentId) {
                try {
                    // Refund the payment
                    $refund = \Stripe\Refund::create([
                        'payment_intent' => $paymentIntentId,
                        'reason' => 'consultation_booking_failed'
                    ]);
                    
                    Log::info('Payment refunded due to booking failure. Payment Intent: ' . $paymentIntentId . ', Refund ID: ' . $refund->id);
                } catch (\Exception $refundException) {
                    Log::error('Failed to refund payment after booking failure. Payment Intent: ' . $paymentIntentId . ', Error: ' . $refundException->getMessage());
                }
            }
            
            Log::error('Consultation booking error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while booking the consultation. If payment was charged, it will be refunded automatically.'
            ], 500);
        }
    }

    public function getConsultationDetails($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'consultation' => $consultation
        ]);
    }

    /**
     * Check if user has completed the nutrition-form questionnaire
     */
    public function checkQuestionnaireStatus()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated',
                'requires_auth' => true
            ]);
        }

        $user = Auth::user();
        
        // Check if user has completed nutrition-form questionnaire
        $questionnaireCompleted = Questionnaire::where('user_id', $user->id)
            ->where('question', 'nutrition-form')
            ->exists();

        return response()->json([
            'success' => true,
            'questionnaire_completed' => $questionnaireCompleted,
            'redirect_url' => $questionnaireCompleted 
                ? route('front.profile', $user->id) 
                : route('front.pre-plan-details')
        ]);
    }

    /**
     * Track coupon usage for consultation booking - matches PaymentController logic
     */
    private function trackCouponUsage($coupon, $user, $consultation, $finalPrice, $couponCode)
    {
        try {
            // 🔹 Split coupon code by "_", get the source slug (e.g., FB from FB_Athlete20)
            $couponParts = explode('_', $couponCode);
            $sourceSlug  = $couponParts[0] ?? null;

            $couponSource = null;
            if ($sourceSlug) {
                $couponSource = DB::table('coupon_source')->select('id', 'name')->where('slug', $sourceSlug)->first();
            }

            // 🔹 Determine discount
            if ($coupon->type === 'percentage' && $coupon->value == 100.00) {
                $discount       = 'full';
                $sectionElement = 'consultation_coupon_full_discount';
                $couponType     = TrackingType::FREE_PLAN_COUPON;
            } elseif ($coupon->type === 'percentage') {
                $discount       = ($consultation->price * $coupon->value) / 100;
                $sectionElement = 'consultation_coupon_percentage_discount';
                $couponType     = TrackingType::COUPON_APPLIED;
            } elseif ($coupon->type === 'fixed') {
                $discount       = $coupon->value;
                $sectionElement = 'consultation_coupon_fixed_discount';
                $couponType     = TrackingType::COUPON_APPLIED;
            }

            // 🔹 Track click
            $click = ActivityTracker::click($sectionElement, $user->id);

            // 🔹 Log in trackings with extra coupon source info - exact same as PaymentController
            ActivityTracker::log($couponType, $user->id, [
                'user_click_id'      => $click->id,
                'section_element_id' => $click->section_element_id,
                'coupon_code'        => $couponCode,
                'coupon_id'          => $coupon->id,
                'discount'           => $discount,
                'consultation_id'    => $consultation->id,
                'coupon_source_id'   => $couponSource->id ?? null,
                'coupon_source_name' => $couponSource->name ?? null,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to track coupon usage for consultation', [
                'coupon_id' => $coupon->id,
                'user_id' => $user->id,
                'consultation_id' => $consultation->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
