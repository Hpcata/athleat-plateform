<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\PlanPurchaseMail;
use App\Mail\PrePlanDetailsSubmitMail;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\SportCategory;
use App\Models\TrackingType;
use App\Models\User;
use App\Models\UserPrePlan;
use App\Models\UserConsultation;
use App\Models\UserPlan;
use App\Models\Consultation;
use App\Models\RecurringPayment;
use App\Services\ActivityTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $isGuest = ! auth()->guard('web')->check();
        $userId  = User::where('email', $request->email)->value('id');

        // Define validation rules
        $rules = [
            'plan_id'     => 'required|integer',
            'price'       => 'nullable|numeric',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'nullable|string|max:20',
            'password'    => ($isGuest && ! $userId) ? 'required|string|min:8' : 'nullable',
            'coupon_code' => 'nullable',
        ];

        // Payment method is always optional
        $rules['payment_method_id'] = 'nullable';

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            Log::debug('Stripe payment flow started.', ['request' => $request->all()]);

            $coupon    = null;
            $discount  = 0;
            $user      = User::where('email', $validated['email'])->first();
            $isNewUser = false;

            if (! $user) {
                $firstName = explode(' ', $validated['name'])[0];
                $lastName  = explode(' ', $validated['name'])[1] ?? '';

                $user = User::create([
                    'name'       => $validated['name'],
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $validated['email'],
                    'phone'      => $validated['phone'],
                    'password'   => Hash::make($validated['password']),
                ]);

                $click = ActivityTracker::click('user_account_create', $user->id);
                ActivityTracker::log(
                    TrackingType::ACCOUNT_CREATED, $user->id,
                    [
                        'email'              => $user->email,
                        'user_click_id'      => $click->id,
                        'section_element_id' => $click->section_element_id,
                        'user_id'            => $user->id,
                    ]
                );

                $isNewUser = true;
                Log::debug('New user created.', ['user_id' => $user->id]);
            }

            $submitQuestionnaire = $isNewUser || ! UserPrePlan::where('user_id', $user->id)->exists();

            // Set Stripe API key before customer creation
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create Stripe customer early in the process
            $customer = $this->getOrCreateStripeCustomer($user);
            Log::info('Stripe customer created/retrieved early in process', [
                'customer_id' => $customer->id,
                'user_id' => $user->id
            ]);

            $existingUserPlan = UserPlan::where('plan_id', $validated['plan_id'])
                ->where('user_id', $user->id)
                ->first();

            if ($existingUserPlan) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already purchased this plan. Please login to your account to manage your plans.',
                ]);
            }

            // Apply coupon logic
            Log::debug('Applying coupon logic.', ['coupon_code' => $validated['coupon_code']]);
            if (! empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])
                    ->where('status', true)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('max_uses', '>', 0)
                    ->first();

                if ($coupon) {
                    Log::debug('Coupon found.', ['coupon_id' => $coupon->id]);
                    $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                        ->where('user_id', $user->id)
                        ->count();

                    if ($coupon->uses_per_user > 0 && $userUsageCount >= $coupon->uses_per_user) {
                        Log::debug('User usage count is greater than or equal to coupon uses per user.', ['user_usage_count' => $userUsageCount, 'coupon_uses_per_user' => $coupon->uses_per_user]);
                        return response()->json([
                            'valid'   => false,
                            'message' => 'You have already used this coupon the maximum allowed times.',
                        ]);
                    }

                    // 🔹 Split coupon code by "_", get the source slug (e.g., FB from FB_Athlete20)
                    $couponParts = explode('_', $validated['coupon_code']);
                    $sourceSlug  = $couponParts[0] ?? null;

                    $couponSource = null;
                    if ($sourceSlug) {
                        $couponSource = DB::table('coupon_source')->select('id', 'name')->where('slug', $sourceSlug)->first();
                    }

                    // 🔹 Determine discount
                    if ($coupon->type === Coupon::TYPE_PERCENT && $coupon->value == 100.00) {
                        $discount       = 'full';
                        $sectionElement = 'full_discount';
                        $couponType     = TrackingType::FREE_PLAN_COUPON;
                    } elseif ($coupon->type === Coupon::TYPE_PERCENT) {
                        $discount       = ($validated['price'] * $coupon->value) / 100;
                        $sectionElement = 'percentage_discount';
                        $couponType     = TrackingType::COUPON_APPLIED;
                    } elseif ($coupon->type === Coupon::TYPE_FIXED) {
                        $discount       = $coupon->value;
                        $sectionElement = 'fixed_discount';
                        $couponType     = TrackingType::COUPON_APPLIED;
                    }

                    Log::debug('Tracking coupon usage.', ['section_element' => $sectionElement, 'coupon_type' => $couponType]);

                    // 🔹 Track click
                    $click = ActivityTracker::click($sectionElement, $user->id);

                    // 🔹 Log in trackings with extra coupon source info
                    ActivityTracker::log($couponType, $user->id, [
                        'user_click_id'      => $click->id,
                        'section_element_id' => $click->section_element_id,
                        'coupon_code'        => $validated['coupon_code'],
                        'coupon_id'          => $coupon->id,
                        'discount'           => $discount,
                        'plan_id'            => $validated['plan_id'],
                        'coupon_source_id'   => $couponSource->id ?? null,
                        'coupon_source_name' => $couponSource->name ?? null,
                    ]);

                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code.']);
                }
            }

            $finalPrice = ($discount === 'full') ? 0 : max(0, $validated['price'] - $discount);
            Log::debug('Final price after discount.', ['final_price' => $finalPrice]);

            // Payment method validation removed - payment method is optional

            $paymentIntentId = null;
            $status          = 'discount_applied';

            // If payment is required, create Stripe payment intent
            if ($finalPrice > 0) {
                Log::debug('Creating Stripe payment intent.', ['amount' => $finalPrice * 100]);

                $paymentMethodId = null;
                
                // Create Stripe customer with payment method if provided
                $customer = $this->getOrCreateStripeCustomer($user, $validated['payment_method_id'] ?? null);
                Log::info('Stripe customer created/retrieved with payment method', [
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'payment_method_id' => $validated['payment_method_id'] ?? null
                ]);
                
                // Create payment method if provided
                if (!empty($validated['payment_method_id'])) {
                    try {
                        // Retrieve the existing payment method from Stripe
                        $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
                        
                        // Attach payment method to customer if not already attached
                        if (!$paymentMethod->customer) {
                            $paymentMethod->attach(['customer' => $customer->id]);
                        } elseif ($paymentMethod->customer !== $customer->id) {
                            // Payment method is attached to different customer
                            throw new \Exception('Payment method is already attached to another customer.');
                        }
                        
                        $paymentMethodId = $paymentMethod->id;
                        
                        Log::debug('Payment method retrieved and attached to customer', [
                            'payment_method_id' => $paymentMethodId,
                            'customer_id' => $customer->id
                        ]);
                        
                    } catch (\Exception $e) {
                        Log::error('Failed to handle payment method: ' . $e->getMessage());
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to handle payment method: ' . $e->getMessage()
                        ], 400);
                    }
                }

                $paymentIntentData = [
                    'amount' => $finalPrice * 100,
                    'currency' => 'aud',
                    'confirmation_method' => 'manual',
                ];

                // Add payment method if created
                if ($paymentMethodId) {
                    $paymentIntentData['payment_method'] = $paymentMethodId;
                    $paymentIntentData['confirm'] = true; // Only confirm if we have a payment method
                    $paymentIntentData['return_url'] = route('payment.success'); // Only add return_url when confirming
                } else {
                    $paymentIntentData['confirm'] = false; // Don't confirm without payment method
                }

                $paymentIntent = PaymentIntent::create($paymentIntentData);

                Log::debug('Stripe PaymentIntent created.', [
                    'status' => $paymentIntent->status,
                    'has_payment_method' => !empty($paymentMethodId)
                ]);

                // Handle different PaymentIntent statuses
                if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                    DB::rollBack();
                    return response()->json([
                        'requires_action'              => true,
                        'payment_intent_client_secret' => $paymentIntent->client_secret,
                    ]);
                } elseif ($paymentIntent->status === 'requires_payment_method') {
                    // PaymentIntent created but needs a payment method
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment method is required to complete this payment.',
                        'payment_intent_id' => $paymentIntent->id
                    ], 400);
                } elseif ($paymentIntent->status !== 'succeeded') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => 'Payment failed with status: ' . $paymentIntent->status
                    ], 400);
                }

                $paymentIntentId = $paymentIntent->id;
                $status          = $paymentIntent->status;
            } else {
                // Free plan (100% discount) - no payment required
                Log::debug('Free plan detected - skipping payment processing.', ['final_price' => $finalPrice]);
                $paymentIntentId = null;
                $status = 'free_plan';
            }

            // Save payment record (always, regardless of discount)
            $paymentId = DB::table('payments')->insertGetId([
                'user_id'           => $user->id,
                'plan_id'           => $validated['plan_id'],
                'price'             => $finalPrice,
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'phone'             => $validated['phone'],
                'payment_intent_id' => $paymentIntentId,
                'status'            => $status,
                'coupon_code'       => $validated['coupon_code'] ?? null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Track coupon usage
            if ($coupon) {
                $coupon->increment('usage_count');
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id'   => $user->id,
                ]);
            }

            $couponSource = null;
            if (isset($validated['coupon_code']) && ! empty($validated['coupon_code'])) {
                // 🔹 Split coupon code by "_", get the source slug (e.g., FB from FB_Athlete20)
                $couponParts = explode('_', $validated['coupon_code']);
                $sourceSlug  = $couponParts[0] ?? null;

                if ($sourceSlug) {
                    $couponSource = DB::table('coupon_source')->select('id', 'name')->where('slug', $sourceSlug)->first();
                }
            }

            // add tacking of plan purchased
            $click = ActivityTracker::click('plan_subscribed', $user->id);
            ActivityTracker::log(TrackingType::PLAN_SUBSCRIBED, $user->id, [
                'user_click_id'       => $click->id,
                'section_element_id'  => $click->section_element_id,
                'plan_id'             => $validated['plan_id'],
                'subscription_amount' => $finalPrice,
                'payment_id'          => $paymentId,
                'discount'            => $discount === 'full' ? $validated['price'] : $discount,
                'original_price'      => $validated['price'],
                'coupon_id'           => $coupon ? $coupon->id : 0,
                'coupon_source_id'    => $couponSource ? $couponSource->id : 0,
            ]);

            // check this user from user table if free_user is true then mark this to 0
            if ($user->free_user) {
                $user->free_user = 0;
                $user->save();
            }

            // 🔹 Ensure entry in user_plans if questionnaire already complete
            $hasCompletedQuestionnaire = DB::table('user_pre_plans')
                ->where('user_id', $user->id)
                ->where('is_complete', 1)
                ->exists();

            if ($hasCompletedQuestionnaire) {
                DB::table('user_plans')->updateOrInsert(
                    ['user_id' => $user->id, 'plan_id' => $validated['plan_id']],
                    [
                        'status'      => 'created',
                        'modified_by' => auth()->id(),
                        'updated_at'  => now(),
                        'created_at'  => now(),
                    ]
                );
            }

            DB::commit();
            Log::debug('Payment processed successfully.', ['payment_id' => $paymentId]);

            return response()->json([
                'success'      => true,
                'message'      => 'Payment processed successfully!',
                'data'         => [
                    'user_id'              => $user->id,
                    'payment_id'           => $paymentId,
                    'submit_questionnaire' => $submitQuestionnaire,
                ],
                'redirect_url' => route('front.pre-plan-details'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        // Handle Stripe redirect parameters
        $paymentIntentId = $request->get('payment_intent');
        $paymentIntentClientSecret = $request->get('payment_intent_client_secret');
        
        Log::info('Payment success page accessed', [
            'payment_intent_id' => $paymentIntentId,
            'payment_intent_client_secret' => $paymentIntentClientSecret,
            'user_id' => Auth::id()
        ]);
        
        // If we have payment intent details, we could verify the payment status
        if ($paymentIntentId) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
                
                Log::info('Payment intent verified', [
                    'payment_intent_id' => $paymentIntentId,
                    'status' => $paymentIntent->status,
                    'amount' => $paymentIntent->amount
                ]);
                
                // You could add additional logic here to update user status, send emails, etc.
                
            } catch (\Exception $e) {
                Log::error('Failed to verify payment intent: ' . $e->getMessage());
            }
        }
        
        return view('payment.success');
    }

    public function prePlanDetails(Request $request)
    {
        $userId    = $request->user_id;
        $paymentId = $request->id;

        // Check if user has an existing pre-plan
        $prePlan = DB::table('user_pre_plans')
            ->select('id')
            ->where('user_id', $userId)
            // ->where('payment_id', $paymentId)
            ->first();

        $userPrePlanId = $prePlan->id ?? null;
        $nextStep      = 1;         // Default to step 1
        $stepData      = collect(); // Default empty collection

        if ($userPrePlanId) {
            // Get the max step completed
            $completedSteps = DB::table('pre_plan_details')
                ->where('user_pre_plan_id', $userPrePlanId)
                ->max('step');

            // Determine the next step - if no steps completed, start from 1
            // If steps completed but less than 9, continue from next step
            // If all 9 steps completed, stay at step 9
            if ($completedSteps && $completedSteps < 9) {
                $nextStep = $completedSteps + 1;
            } elseif ($completedSteps == 9) {
                $nextStep = 9; // All steps completed
            }

            // Fetch existing step data for pre-filling
            $stepData = DB::table('pre_plan_details')
                ->where('user_pre_plan_id', $userPrePlanId)
                ->get()
                ->groupBy('step');
        }

        $sportCategories = SportCategory::select('id', 'name')->get();

        return view('front.pages.pre_plan_details', compact('userId', 'paymentId', 'nextStep', 'stepData', 'sportCategories'));
    }

    public function prePlanDetailsSave(Request $request)
    {
        $user_id    = $request->user_id ?? null;
        $payment_id = $request->payment_id ?? null;
        $questions  = $request->input('questions', []);
        $answers    = $request->input('ans', []);
        $step       = $request->input('step');
        $stepFill   = $request->input('step_fill') == true ? 1 : 0;

        DB::beginTransaction();
        try {
            // Check if user already has a pre-plan
            $prePlanId = DB::table('user_pre_plans')
                ->where('user_id', $user_id)
                // ->where('payment_id', $payment_id)
                ->value('id');

            if (! $prePlanId) {
                // Create new pre-plan if it doesn't exist
                $prePlanId = DB::table('user_pre_plans')->insertGetId([
                    'payment_id' => $payment_id,
                    'user_id'    => $user_id,
                    'dob'        => $request->ans['personal_details']['dob'] ?? null,
                    'occupation' => $request->ans['personal_details']['occupation'] ?? null,
                    'address'    => $request->ans['personal_details']['postcode'] ?? null,
                    'culture'    => null,
                    'referredBy' => $request->ans['personal_details']['referredBy'] ?? null,
                    'other'      => $request->other ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $click = ActivityTracker::click('questionnaire_started', $user_id);

                // Log in trackings with click reference
                ActivityTracker::log(TrackingType::QUESTIONNAIRE_STARTED, $user_id, [
                    'user_click_id'           => $click->id,
                    'section_element_id'      => $click->section_element_id,
                    'questionnaire_completed' => false,
                    'questionnaire_id'        => $prePlanId,
                    'payment_id'              => $payment_id,
                ]);
            }

            // Remove old data for this step to avoid duplicates
            if ($step !== null) {
                DB::table('pre_plan_details')
                    ->where('user_pre_plan_id', $prePlanId)
                    ->where('step', $step)
                    ->delete();
            }

            $dataToInsert = [];

            foreach ($questions as $section => $sectionQuestions) {
                $formattedSection = ucwords(str_replace('_', ' ', $section));
                foreach ($sectionQuestions as $key => $questionText) {
                    $questionAnswers = $answers[$section][$key] ?? null;

                    if (is_array($questionText)) {
                        foreach ($questionText as $qsnkey => $subQuestionText) {
                            $subQuestionAnswers = $questionAnswers[$qsnkey] ?? null;

                            if (! is_null($subQuestionAnswers)) {
                                $subQuestionAnswers = is_array($subQuestionAnswers)
                                ? json_encode($subQuestionAnswers, JSON_THROW_ON_ERROR)
                                : json_encode((string) $subQuestionAnswers, JSON_THROW_ON_ERROR);
                            }

                            $dataToInsert[] = [
                                'user_pre_plan_id' => $prePlanId,
                                'form_name'        => $formattedSection,
                                'form_slug'        => $section,
                                'question'         => $subQuestionText,
                                'answer'           => $subQuestionAnswers,
                                'step'             => $step,
                                'step_fill'        => $stepFill,
                                'created_at'       => now(),
                                'updated_at'       => now(),
                            ];
                        }
                    } else {
                        $questionAnswers = isset($answers[$section][$key])
                        ? (is_array($answers[$section][$key])
                            ? json_encode($answers[$section][$key], JSON_THROW_ON_ERROR)
                            : json_encode((string) $answers[$section][$key], JSON_THROW_ON_ERROR))
                        : null;

                        $dataToInsert[] = [
                            'user_pre_plan_id' => $prePlanId,
                            'form_name'        => $formattedSection,
                            'form_slug'        => $section,
                            'question'         => $questionText,
                            'answer'           => $questionAnswers,
                            'step'             => $step,
                            'step_fill'        => $stepFill,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ];
                    }
                }
            }

            DB::table('pre_plan_details')->insert($dataToInsert);

            DB::commit();

            $user     = User::find($user_id);
            // $payment  = Payment::with('user')->where('id', $payment_id)->first();
            $email    = $user->email;
            $user     = $user;

            if ($step == 9) {
                $click = ActivityTracker::click('questionnaire_completed', $user->id);

                // Log in trackings with click reference
                ActivityTracker::log(TrackingType::QUESTIONNAIRE_COMPLETED, $user->id, [
                    'user_click_id'           => $click->id,
                    'section_element_id'      => $click->section_element_id,
                    'questionnaire_completed' => true,
                    'questionnaire_id'        => $prePlanId,
                    'payment_id'              => $payment_id,
                ]);

                // Update the is_complete column in the user_pre_plans table
                DB::table('user_pre_plans')
                    ->where('id', $prePlanId)
                    ->update(['is_complete' => 1]);

                // ✅ Sync ALL past paid plans into user_plans
                $allPayments = DB::table('payments')
                    ->where('user_id', $user->id)
                    ->where('status', '!=', 'failed') // only successful payments
                    ->get();

                foreach ($allPayments as $pay) {
                    DB::table('user_plans')->updateOrInsert(
                        ['user_id' => $user->id, 'plan_id' => $pay->plan_id],
                        [
                            'status'      => 'created',
                            'modified_by' => auth()->id(),
                            'updated_at'  => now(),
                            'created_at'  => now(),
                        ]
                    );
                }

                // Log the user in after questionnaire completion
                Auth::guard('web')->login($user);
            }

            return response()->json([
                'success'      => true,
                'message'      => 'Step data saved successfully!',
                'user_id'      => $user->id,
                'redirect_url' => $step == 9 ? route('front.profile', $user->id) : null,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving step: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving step: ' . $e->getMessage(),
            ]);
        }
    }

    public function questionnaireSendMail(Request $request)
    {
        $userId    = $request->input('user_id');
        $paymentId = $request->input('payment_id');
        $user      = User::find($userId);
        $payment   = Payment::find($paymentId);
        $plan      = $payment->plan;

        if (! $user || ! $payment || ! $plan) {
            return response()->json(['success' => false, 'message' => 'Invalid data.']);
        }

        $userPrePlan = UserPrePlan::where('user_id', $user->id)
            ->where('payment_id', $payment->id)
            ->first();

        try {
            Mail::to($user->email)->send(new PlanPurchaseMail($user, $plan->name));
            Mail::to(config('constants.admin_email'))->send(new PrePlanDetailsSubmitMail($user, $plan->name));

            return response()->json([
                'success' => true,
                'message' => 'Mail sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Mail send failed.']);
        }
    }

    /**
     * Process plan purchase with optional consultation booking
     */
    public function processPlanPurchase(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to purchase a plan',
                'requires_auth' => true
            ]);
        }

        $user = Auth::user();

        // Set Stripe API key before customer creation
        Stripe::setApiKey(config('services.stripe.secret'));

        // Define validation rules
        $rules = [
            'plan_id' => 'required|integer|exists:plans,id',
            'plan_type' => 'required|string|in:main,powerplay,gameplan',
            'price' => 'required|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'payment_method_id' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'is_monthly' => 'required|in:true,false,1,0'
        ];

        $validated = $request->validate($rules);
        
        // Convert is_monthly to proper boolean
        $validated['is_monthly'] = filter_var($validated['is_monthly'], FILTER_VALIDATE_BOOLEAN);

        DB::beginTransaction();

        try {
            Log::debug('Plan purchase payment flow started.', [
                'request' => $request->all(),
                'plan_type' => $validated['plan_type'] ?? 'not provided',
                'plan_id' => $validated['plan_id'] ?? 'not provided'
            ]);

            // Check if user already has this plan in user_plans table
            $existingUserPlan = UserPlan::where('plan_id', $validated['plan_id'])
                ->where('user_id', $user->id)
                ->first();

            if ($existingUserPlan) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already purchased this plan. Please check your account to manage your existing plans.',
                ]);
            }

            $coupon = null;
            $discount = 0;

            // Handle coupon validation (exactly like consultation flow)
            $finalPrice = $validated['price'];
            $couponCode = $validated['coupon_code'];
            
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)
                    ->where('status', 1)
                    ->first();
                    
                if ($coupon) {
                    $currentDateTime = \Carbon\Carbon::now();
                    
                    // Check if coupon is valid
                    if ($currentDateTime->gte($coupon->start_date) && $currentDateTime->lte($coupon->end_date)) {
                        // Check usage limits
                        if ($coupon->max_uses == 0 || $coupon->usage_count < $coupon->max_uses) {
                            // Check user usage
                            $userUsageCount = CouponUsage::where('coupon_id', $coupon->id)
                                ->where('user_id', $user->id)
                                ->count();
                                
                            if ($coupon->uses_per_user == 0 || $userUsageCount < $coupon->uses_per_user) {
                                // Determine consultation ID based on plan type for coupon validation
                                $consultationId = null;
                                if ($validated['plan_type'] === 'powerplay') {
                                    $consultation = Consultation::where('time', 30)->first();
                                    $consultationId = $consultation ? $consultation->id : null;
                                } elseif ($validated['plan_type'] === 'gameplan') {
                                    $consultation = Consultation::where('time', 60)->first();
                                    $consultationId = $consultation ? $consultation->id : null;
                                }
                                
                                // Check if coupon is applicable to plan or consultation
                                $isApplicableToPlan = $coupon->plans()->where('plans.id', $validated['plan_id'])->exists();
                                $isApplicableToConsultation = false;
                                
                                if ($consultationId) {
                                    $isApplicableToConsultation = $coupon->consultations()->where('consultations.id', $consultationId)->exists();
                                }
                                
                                // Coupon must be applicable to either the plan OR the consultation
                                $isApplicable = $isApplicableToPlan || $isApplicableToConsultation;
                                
                                if ($isApplicable) {
                                    // Apply discount
                                    if ($coupon->type === 'percentage') {
                                        $discountAmount = ($finalPrice * $coupon->value) / 100;
                                        $finalPrice = max(0, $finalPrice - $discountAmount);
                                    } elseif ($coupon->type === 'fixed') {
                                        $finalPrice = max(0, $finalPrice - $coupon->value);
                                    }
                                    
                                    // 🔹 Track coupon application (from processPayment)
                                    $couponParts = explode('_', $couponCode);
                                    $sourceSlug = $couponParts[0] ?? null;
                                    $couponSource = null;
                                    
                                    if ($sourceSlug) {
                                        $couponSource = DB::table('coupon_source')->select('id', 'name')->where('slug', $sourceSlug)->first();
                                    }
                                    
                                    // Determine tracking type and section element
                                    if ($coupon->type === 'percentage' && $coupon->value == 100.00) {
                                        $discount = 'full';
                                        $sectionElement = 'full_discount';
                                        $couponType = TrackingType::FREE_PLAN_COUPON;
                                    } elseif ($coupon->type === 'percentage') {
                                        $discount = $discountAmount;
                                        $sectionElement = 'percentage_discount';
                                        $couponType = TrackingType::COUPON_APPLIED;
                                    } elseif ($coupon->type === 'fixed') {
                                        $discount = $coupon->value;
                                        $sectionElement = 'fixed_discount';
                                        $couponType = TrackingType::COUPON_APPLIED;
                                    }
                                    
                                    // Track coupon click and log
                                    $click = ActivityTracker::click($sectionElement, $user->id);
                                    ActivityTracker::log($couponType, $user->id, [
                                        'user_click_id' => $click->id,
                                        'section_element_id' => $click->section_element_id,
                                        'coupon_code' => $couponCode,
                                        'coupon_id' => $coupon->id,
                                        'discount' => $discount,
                                        'plan_id' => $validated['plan_id'],
                                        'coupon_source_id' => $couponSource->id ?? null,
                                        'coupon_source_name' => $couponSource->name ?? null,
                                    ]);
                                } else {
                                    $errorMessage = 'This coupon is not applicable to the selected plan';
                                    if ($consultationId) {
                                        $errorMessage .= ' or consultation';
                                    }
                                    $errorMessage .= '.';
                                    return response()->json(['success' => false, 'message' => $errorMessage]);
                                }
                            } else {
                                return response()->json(['success' => false, 'message' => 'You have already used this coupon the maximum allowed times.']);
                            }
                        } else {
                            return response()->json(['success' => false, 'message' => 'Coupon usage limit has been reached.']);
                        }
                    } else {
                        return response()->json(['success' => false, 'message' => 'Coupon is not valid at this time.']);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
                }
            }

            Log::debug('Final price after discount.', ['final_price' => $finalPrice]);

            // Payment method validation removed - payment method is optional

            $paymentIntentId = null;
            $status = 'discount_applied';

            // If payment is required, create Stripe payment intent
            if ($finalPrice > 0) {
                Log::debug('Creating Stripe payment intent.', ['amount' => $finalPrice * 100]);

                $paymentMethodId = null;
                
                // Create Stripe customer with payment method if provided
                $customer = $this->getOrCreateStripeCustomer($user, $validated['payment_method_id'] ?? null);
                Log::info('Stripe customer created/retrieved with payment method', [
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'payment_method_id' => $validated['payment_method_id'] ?? null
                ]);
                
                // Create payment method if provided
                if (!empty($validated['payment_method_id'])) {
                    try {
                        // Retrieve the existing payment method from Stripe
                        $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
                        
                        // Attach payment method to customer if not already attached
                        if (!$paymentMethod->customer) {
                            $paymentMethod->attach(['customer' => $customer->id]);
                        } elseif ($paymentMethod->customer !== $customer->id) {
                            // Payment method is attached to different customer
                            throw new \Exception('Payment method is already attached to another customer.');
                        }
                        
                        $paymentMethodId = $paymentMethod->id;
                        
                        Log::debug('Payment method retrieved and attached to customer', [
                            'payment_method_id' => $paymentMethodId,
                            'customer_id' => $customer->id
                        ]);
                        
                    } catch (\Exception $e) {
                        Log::error('Failed to handle payment method: ' . $e->getMessage());
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to handle payment method: ' . $e->getMessage()
                        ], 400);
                    }
                }

                $paymentIntentData = [
                    'amount' => (int) round($finalPrice * 100),
                    'currency' => 'aud',
                    'confirmation_method' => 'automatic',
                    'customer' => $customer->id,
                ];

                // Add payment method if created
                if ($paymentMethodId) {
                    $paymentIntentData['payment_method'] = $paymentMethodId;
                    $paymentIntentData['confirm'] = true; // Only confirm if we have a payment method
                    $paymentIntentData['return_url'] = route('payment.success'); // Only add return_url when confirming
                } else {
                    $paymentIntentData['confirm'] = true; // Don't confirm without payment method
                }

                $paymentIntent = PaymentIntent::create($paymentIntentData);

                Log::debug('Stripe PaymentIntent created.', [
                    'status' => $paymentIntent->status,
                    'has_payment_method' => !empty($paymentMethodId)
                ]);

                // Handle different PaymentIntent statuses
                if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                    DB::rollBack();
                    return response()->json([
                        'requires_action' => true,
                        'payment_intent_client_secret' => $paymentIntent->client_secret,
                    ]);
                } elseif ($paymentIntent->status === 'requires_payment_method') {
                    // PaymentIntent created but needs a payment method
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment method is required to complete this payment.',
                        'payment_intent_id' => $paymentIntent->id
                    ], 400);
                } elseif ($paymentIntent->status !== 'succeeded') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false, 
                        'message' => 'Payment failed with status: ' . $paymentIntent->status
                    ], 400);
                }

                $paymentIntentId = $paymentIntent->id;
                $status = $paymentIntent->status;
            } else {
                // Free plan (100% discount) - no payment required
                Log::debug('Free plan detected - skipping payment processing.', ['final_price' => $finalPrice]);
                $paymentIntentId = null;
                $status = 'free_plan';
            }

            // Determine consultation ID based on plan type
            $consultationId = null;
            Log::debug('Plan type: ' . $validated['plan_type']);
            
            if ($validated['plan_type'] === 'powerplay') {
                $consultation = Consultation::where('time', 30)->first();
                Log::debug('Power Play - Looking for 30min consultation', ['consultation' => $consultation]);
                $consultationId = $consultation ? $consultation->id : null;
                Log::debug('Power Play - Consultation ID: ' . $consultationId);
            } elseif ($validated['plan_type'] === 'gameplan') {
                $consultation = Consultation::where('time', 60)->first();
                Log::debug('Game Plan - Looking for 60min consultation', ['consultation' => $consultation]);
                $consultationId = $consultation ? $consultation->id : null;
                Log::debug('Game Plan - Consultation ID: ' . $consultationId);
            }

            // Create payment record (user_plan_id will be updated after UserPlan creation)
            $payment = Payment::create([
                'user_id' => $user->id,
                'user_plan_id' => null, // Will be updated after UserPlan creation
                'plan_id' => $validated['plan_id'],
                'consultation_id' => $consultationId,
                'price' => $validated['final_price'] ?? $finalPrice,
                'original_price' => $validated['price'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'payment_intent_id' => $paymentIntentId,
                'status' => $status,
                'coupon_code' => $validated['coupon_code']
            ]);

            // Track coupon usage (exactly like consultation flow)
            if ($couponCode && isset($coupon)) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'discount_amount' => $validated['price'] - $finalPrice
                ]);
                
                // Update coupon usage count
                $coupon->increment('usage_count');
            }

            // Add coupon source tracking (from processPayment)
            $couponSource = null;
            if (isset($validated['coupon_code']) && ! empty($validated['coupon_code'])) {
                // 🔹 Split coupon code by "_", get the source slug (e.g., FB from FB_Athlete20)
                $couponParts = explode('_', $validated['coupon_code']);
                $sourceSlug  = $couponParts[0] ?? null;

                if ($sourceSlug) {
                    $couponSource = DB::table('coupon_source')->select('id', 'name')->where('slug', $sourceSlug)->first();
                }
            }

            // Add tracking of plan purchased (enhanced with coupon source info)
            $click = ActivityTracker::click('plan_subscribed', $user->id);
            ActivityTracker::log(TrackingType::PLAN_SUBSCRIBED, $user->id, [
                'user_click_id' => $click->id,
                'section_element_id' => $click->section_element_id,
                'plan_id' => $validated['plan_id'],
                'subscription_amount' => $finalPrice,
                'payment_id' => $payment->id,
                'discount' => $discount,
                'original_price' => $validated['price'],
                'coupon_id' => $coupon ? $coupon->id : 0,
                'coupon_source_id' => $couponSource ? $couponSource->id : 0,
                'coupon_source_name' => $couponSource ? $couponSource->name : null,
                'plan_type' => $validated['plan_type'],
                'is_monthly' => $validated['is_monthly'] ?? false
            ]);

            // Mark user as no longer free user
            if ($user->free_user) {
                $user->free_user = 0;
                $user->save();
            }

            // Ensure entry in user_plans if questionnaire already complete
            $hasCompletedQuestionnaire = UserPrePlan::where('user_id', $user->id)
                ->where('is_complete', 1)
                ->exists();

            Log::debug('User questionnaire status', [
                'user_id' => $user->id,
                'has_completed' => $hasCompletedQuestionnaire,
                'plan_id' => $validated['plan_id']
            ]);

            if ($hasCompletedQuestionnaire) {
                $userPlan = UserPlan::updateOrCreate(
                    ['user_id' => $user->id, 'plan_id' => $validated['plan_id']],
                    [
                        'status' => 'active',
                        'modified_by' => auth()->id(),
                        'updated_at' => now(),
                    ]
                );
                Log::debug('UserPlan created/updated (active)', ['user_plan' => $userPlan]);
            } else {
                // Create user plan entry even if questionnaire not complete
                $userPlan = UserPlan::updateOrCreate(
                    ['user_id' => $user->id, 'plan_id' => $validated['plan_id']],
                    [
                        'status' => 'pending',
                        'modified_by' => auth()->id(),
                        'updated_at' => now(),
                    ]
                );
                Log::debug('UserPlan created/updated (pending)', ['user_plan' => $userPlan]);
            }

            // Update payment with user_plan_id
            $payment->update(['user_plan_id' => $userPlan->id]);

            // Handle monthly recurring payments with Stripe subscription
            if ($validated['is_monthly']) {
                // For free plans (100% discount), skip subscription creation
                if ($finalPrice <= 0) {
                    Log::debug('Free monthly plan detected - skipping subscription creation.', [
                        'final_price' => $finalPrice,
                        'is_monthly' => $validated['is_monthly']
                    ]);
                } else {
                    // Check if payment method is provided for paid monthly subscription
                    if (empty($validated['payment_method_id'])) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Payment method is required for monthly subscriptions.'
                        ], 400);
                    }
                    
                    try {
                        $subscriptionResult = $this->createStripeSubscription($userPlan, $user, $validated, $validated['payment_method_id'], $customer);
                        
                        // Check if subscription needs frontend confirmation or action
                        if (isset($subscriptionResult['requires_confirmation'])) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'requires_confirmation' => true,
                                'payment_intent_client_secret' => $subscriptionResult['client_secret'],
                                'subscription_id' => $subscriptionResult['subscription_id'],
                                'message' => 'Payment confirmation required for subscription.'
                            ], 200);
                        } elseif (isset($subscriptionResult['requires_action'])) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'requires_action' => true,
                                'payment_intent_client_secret' => $subscriptionResult['client_secret'],
                                'subscription_id' => $subscriptionResult['subscription_id'],
                                'message' => 'Payment action required for subscription.'
                            ], 200);
                        }
                    } catch (\Exception $e) {
                        // If subscription creation fails, rollback the entire transaction
                        DB::rollBack();
                        Log::error('Stripe subscription creation failed, rolling back transaction: ' . $e->getMessage(), [
                            'user_id' => $user->id,
                            'plan_id' => $validated['plan_id'],
                            'payment_method_id' => $validated['payment_method_id']
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to create recurring subscription: ' . $e->getMessage()
                        ], 400);
                    }
                }
            }

            // Create consultation booking if plan includes consultation
            if ($consultationId) {
                $userConsultation = UserConsultation::create([
                    'user_id' => $user->id,
                    'consultation_id' => $consultationId
                ]);
                Log::debug('UserConsultation created', ['user_consultation' => $userConsultation]);
            } else {
                Log::debug('No consultation ID found, skipping UserConsultation creation');
            }

            // Send plan purchase email notification
            try {
                Mail::to($user->email)->send(new PlanPurchaseMail($user, $payment?->plan?->name));
            } catch (\Exception $e) {
                Log::warning('Failed to send plan purchase email: ' . $e->getMessage());
            }

            DB::commit();
            Log::debug('Plan purchase processed successfully.', [
                'payment_id' => $payment->id,
                'user_plan_created' => isset($userPlan) ? $userPlan->id : 'not created',
                'user_consultation_created' => isset($userConsultation) ? $userConsultation->id : 'not created',
                'consultation_id' => $consultationId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Plan purchased successfully!',
                'data' => [
                    'user_id' => $user->id,
                    'payment_id' => $payment->id,
                    'plan_type' => $validated['plan_type'],
                    'has_consultation' => $consultationId ? true : false,
                    'consultation_id' => $consultationId
                ],
                'redirect_url' => route('front.pre-plan-details')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Plan purchase error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Plan purchase failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create Stripe subscription following proper hierarchy:
     * 1. Create Stripe Customer
     * 2. Create Product and Price in Stripe
     * 3. Set Up Subscription
     * 4. Handle Payment Method
     * 5. Confirm Payment Intent
     * 6. Handle Webhooks for Subscription Events
     */
    private function createStripeSubscription($userPlan, $user, $validated, $paymentMethodId, $customer)
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Use the final price (which is already the correct monthly amount for monthly subscriptions)
            // The frontend already calculates the correct monthly price and sends it as final_price
            $monthlyPrice = $validated['final_price'] ?? $validated['price'];

            Log::info('Starting Stripe recurring payment flow', [
                'user_id' => $user->id,
                'plan_id' => $validated['plan_id'],
                'monthly_price' => $monthlyPrice,
                'payment_method_id' => $paymentMethodId
            ]);

            // STEP 1: Create Stripe Customer (already done, but ensure it's properly set up)
            Log::info('Step 1: Stripe Customer', [
                'customer_id' => $customer->id,
                'customer_email' => $customer->email
            ]);

            // STEP 2: Create Product and Price in Stripe
            Log::info('Step 2: Creating Product and Price');
            
            $product = \Stripe\Product::create([
                'name' => $userPlan->plan->name . ' - Monthly Subscription',
                'description' => 'Monthly subscription for ' . $userPlan->plan->name,
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $validated['plan_id'],
                    'plan_type' => $validated['plan_type']
                ]
            ]);

            $price = \Stripe\Price::create([
                'product' => $product->id,
                'unit_amount' => round($monthlyPrice * 100), // Convert to cents
                'currency' => 'aud',
                'recurring' => [
                    'interval' => config('services.stripe.testing_mode', false) ? 'minute' : 'month',
                    'interval_count' => 1,
                ],
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $validated['plan_id'],
                    'monthly_price' => $monthlyPrice,
                    'testing_mode' => config('services.stripe.testing_mode', false)
                ]
            ]);

            Log::info('Product and Price created', [
                'product_id' => $product->id,
                'price_id' => $price->id,
                'monthly_amount' => $monthlyPrice,
                'interval' => config('services.stripe.testing_mode', false) ? 'minute' : 'month',
                'testing_mode' => config('services.stripe.testing_mode', false)
            ]);

            // STEP 3: Set Up Subscription
            Log::info('Step 3: Setting up Subscription');
            
            // Calculate billing cycle anchor (next month) - no immediate payment
            // Handle test clocks by ensuring the anchor is in the future relative to test clock
            $billingCycleAnchor = \Carbon\Carbon::now()->addMonth()->timestamp;
            
            // Check if we're in test mode and adjust for test clocks
            if (config('services.stripe.testing_mode', false)) {
                // For testing, use a shorter interval to avoid test clock issues
                $billingCycleAnchor = \Carbon\Carbon::now()->addMinutes(2)->timestamp;
                Log::info('Using test mode billing cycle anchor', [
                    'billing_cycle_anchor' => $billingCycleAnchor,
                    'billing_start_date' => \Carbon\Carbon::createFromTimestamp($billingCycleAnchor)->format('Y-m-d H:i:s'),
                    'current_time' => now()->format('Y-m-d H:i:s')
                ]);
            }
            
            // Validate the timestamp
            if ($billingCycleAnchor <= 0) {
                Log::error('Invalid billing cycle anchor timestamp', [
                    'timestamp' => $billingCycleAnchor,
                    'current_time' => now()->format('Y-m-d H:i:s')
                ]);
                throw new \Exception('Invalid billing cycle anchor timestamp');
            }
            
            Log::info('Billing cycle anchor calculated', [
                'billing_cycle_anchor' => $billingCycleAnchor,
                'billing_start_date' => \Carbon\Carbon::createFromTimestamp($billingCycleAnchor)->format('Y-m-d H:i:s'),
                'testing_mode' => config('services.stripe.testing_mode', false)
            ]);

            $cancelAt = \Carbon\Carbon::now()->addMonths(9)->timestamp;
            
            $subscription = \Stripe\Subscription::create([
                'customer' => $customer->id,
                'items' => [
                    [
                        'price' => $price->id,
                    ],
                ],
                'default_payment_method' => $paymentMethodId,
                'billing_cycle_anchor' => $billingCycleAnchor, // Start billing next month
                'proration_behavior' => 'none', // Don't prorate
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => [
                    'save_default_payment_method' => 'on_subscription',
                ],
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $validated['plan_id'],
                    'plan_type' => $validated['plan_type'],
                    'user_plan_id' => $userPlan->id,
                    'first_payment_processed' => 'immediate',
                    'subscription_start' => 'next_month'
                ],
                'cancel_at' => $cancelAt, // Auto-cancel at specific timestamp
            ]);

            Log::info('Subscription created with billing cycle anchor', [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
                'billing_cycle_anchor' => $billingCycleAnchor,
                'billing_start_date' => \Carbon\Carbon::createFromTimestamp($billingCycleAnchor)->format('Y-m-d H:i:s')
            ]);

            // STEP 4: Handle Payment Method
            Log::info('Step 4: Handling Payment Method');
            
            // Ensure payment method is properly attached to customer
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
            
            if (!$paymentMethod->customer) {
                $paymentMethod->attach(['customer' => $customer->id]);
                Log::info('Payment method attached to customer', [
                    'payment_method_id' => $paymentMethodId,
                    'customer_id' => $customer->id
                ]);
            }

            // STEP 5: Confirm Payment Intent
            Log::info('Step 5: Confirming Payment Intent');
            
            if ($subscription->latest_invoice && $subscription->latest_invoice->payment_intent) {
                $paymentIntent = $subscription->latest_invoice->payment_intent;
                
                Log::info('Payment Intent status', [
                    'payment_intent_id' => $paymentIntent->id,
                    'status' => $paymentIntent->status
                ]);

                if ($paymentIntent->status === 'requires_action') {
                    // Payment requires additional authentication (3D Secure)
                    Log::info('Payment requires action - returning to frontend');
                    return [
                        'requires_action' => true,
                        'client_secret' => $paymentIntent->client_secret,
                        'subscription_id' => $subscription->id
                    ];
                } elseif ($paymentIntent->status === 'requires_confirmation') {
                    // Payment requires confirmation from frontend
                    Log::info('Payment requires confirmation - returning to frontend');
                    return [
                        'requires_confirmation' => true,
                        'client_secret' => $paymentIntent->client_secret,
                        'subscription_id' => $subscription->id
                    ];
                } elseif ($paymentIntent->status === 'succeeded') {
                    // Payment succeeded
                    Log::info('Payment succeeded - subscription active');
                    $subscriptionStatus = 'active';
                } else {
                    // Payment failed
                    Log::error('Payment failed', [
                        'payment_intent_status' => $paymentIntent->status,
                        'subscription_id' => $subscription->id
                    ]);
                    throw new \Exception('Payment failed with status: ' . $paymentIntent->status);
                }
            } else {
                Log::warning('No payment intent found for subscription');
                $subscriptionStatus = 'incomplete';
            }

            // Update UserPlan with subscription details
            $userPlan->update([
                'status' => $subscriptionStatus === 'active' ? 'active' : 'pending'
            ]);

            // Create RecurringPayment record with validation
            $nextPaymentDate = \Carbon\Carbon::createFromTimestamp($billingCycleAnchor);
            
            // Validate the next payment date
            if (!$nextPaymentDate->isFuture()) {
                Log::error('Next payment date is not in the future', [
                    'billing_cycle_anchor' => $billingCycleAnchor,
                    'next_payment_date' => $nextPaymentDate->format('Y-m-d H:i:s'),
                    'current_time' => now()->format('Y-m-d H:i:s')
                ]);
                throw new \Exception('Next payment date must be in the future');
            }
            
            // Calculate total payments expected based on testing mode
            $totalPaymentsExpected = config('services.stripe.testing_mode', false) ? 8 : 8; // Same for both modes
            
            RecurringPayment::create([
                'user_plan_id' => $userPlan->id,
                'stripe_subscription_id' => $subscription->id,
                'total_payments' => 1, // First payment already processed immediately
                'total_payments_expected' => $totalPaymentsExpected, // Total 8 payments (1 immediate + 7 monthly)
                'next_payment_date' => $nextPaymentDate, // Next billing cycle
                'last_payment_date' => now(), // First payment was just processed
                'payment_status' => 'active', // Active because first payment was processed
            ]);

            Log::info('Step 6: Subscription setup completed', [
                'subscription_id' => $subscription->id,
                'subscription_status' => $subscriptionStatus,
                'user_plan_id' => $userPlan->id,
                'billing_cycle_anchor' => $billingCycleAnchor,
                'next_payment_date' => \Carbon\Carbon::createFromTimestamp($billingCycleAnchor)->format('Y-m-d H:i:s'),
                'recurring_payments' => '1 of 8 (first payment processed immediately, 7 recurring payments remaining)',
                'testing_mode' => config('services.stripe.testing_mode', false),
                'interval' => config('services.stripe.testing_mode', false) ? 'minute' : 'month'
            ]);

            // Note: Step 6 (Webhooks) will be handled separately via webhook endpoints

            return ['success' => true, 'subscription_id' => $subscription->id, 'status' => 'trialing'];

        } catch (\Exception $e) {
            Log::error('Failed to create Stripe subscription: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'plan_id' => $validated['plan_id'],
                'payment_method_id' => $paymentMethodId,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw the exception to trigger transaction rollback
            throw $e;
        }
    }

    /**
     * Check if user already has a specific plan
     */
    public function checkExistingPlan(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to check plan status',
                'requires_auth' => true
            ]);
        }

        $user = Auth::user();
        $planId = $request->input('plan_id');

        if (!$planId) {
            return response()->json([
                'success' => false,
                'message' => 'Plan ID is required'
            ], 400);
        }

        // Check if user already has this plan
        $existingUserPlan = UserPlan::where('plan_id', $planId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingUserPlan) {
            return response()->json([
                'success' => false,
                'has_plan' => true,
                'message' => 'You have already purchased this plan. Please check your account to manage your existing plans.',
                'plan_status' => $existingUserPlan->status
            ]);
        }

        return response()->json([
            'success' => true,
            'has_plan' => false,
            'message' => 'Plan is available for purchase'
        ]);
    }

    /**
     * Get or create Stripe customer for user
     */
    private function getOrCreateStripeCustomer($user, $paymentMethodId = null)
    {
        try {
            // Check if user already has a Stripe customer ID
            if ($user->stripe_customer_id) {
                try {
                    $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                    
                    // Update customer metadata with payment method if provided
                    if ($paymentMethodId) {
                        $customer->metadata['default_payment_method_id'] = $paymentMethodId;
                        $customer->save();
                        Log::info('Updated customer metadata with payment method', [
                            'customer_id' => $customer->id,
                            'payment_method_id' => $paymentMethodId
                        ]);
                    }
                    
                    Log::info('Retrieved existing Stripe customer', [
                        'customer_id' => $customer->id,
                        'user_id' => $user->id
                    ]);
                    return $customer;
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    // Customer doesn't exist in Stripe, create a new one
                    Log::info('Stripe customer not found, creating new one', [
                        'stored_customer_id' => $user->stripe_customer_id,
                        'user_id' => $user->id
                    ]);
                }
            }

            // Prepare customer metadata
            $metadata = [
                'user_id' => $user->id,
            ];
            
            // Add payment method ID to metadata if provided
            if ($paymentMethodId) {
                $metadata['default_payment_method_id'] = $paymentMethodId;
            }

            // Create new customer
            $customer = \Stripe\Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'phone' => $user->phone,
                'metadata' => $metadata,
            ]);

            // Save customer ID to user record
            $user->update(['stripe_customer_id' => $customer->id]);
            
            Log::info('Created new Stripe customer and saved to user', [
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethodId
            ]);

            return $customer;

        } catch (\Exception $e) {
            Log::error('Failed to get or create Stripe customer: ' . $e->getMessage());
            throw $e;
        }
    }
}

