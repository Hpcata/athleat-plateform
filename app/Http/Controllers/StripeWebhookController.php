<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
use App\Models\RecurringPayment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public $currentPeriodEnd = null;
    public $currentPeriodStart = null;
    public $subscriptionId = null;

    public function handleWebhook(Request $request)
    {
        Log::info('Stripe webhook received', ['request' => $request->all()]);
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        Log::info('Stripe webhook received', [
            'signature' => $sigHeader,
            'payload_size' => strlen($payload),
            'endpoint_secret' => $endpointSecret ? 'configured' : 'missing'
        ]);

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload in Stripe webhook: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid signature in Stripe webhook: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook event processed', [
            'event_id' => $event->id,
            'event_type' => $event->type,
            'created' => $event->created,
            'livemode' => $event->livemode,
            '$event' => $event
        ]);


        // Extract subscription ID and period data from the event data structure
        if (isset($event->data->object->parent->subscription_details->subscription)) {
            $this->subscriptionId = $event->data->object->parent->subscription_details->subscription;
        } elseif (isset($event->data->object->subscription)) {
            $this->subscriptionId = $event->data->object->subscription;
        }
        
        // Extract period data from various possible locations
        if (isset($event->data->object->current_period_end)) {
            $this->currentPeriodEnd = $event->data->object->current_period_end;
        } elseif (isset($event->data->object->period_end)) {
            $this->currentPeriodEnd = $event->data->object->period_end;
        } elseif (isset($event->data->object->lines->data[0]->period->end)) {
            $this->currentPeriodEnd = $event->data->object->lines->data[0]->period->end;
        }
        
        if (isset($event->data->object->current_period_start)) {
            $this->currentPeriodStart = $event->data->object->current_period_start;
        } elseif (isset($event->data->object->period_start)) {
            $this->currentPeriodStart = $event->data->object->period_start;
        } elseif (isset($event->data->object->lines->data[0]->period->start)) {
            $this->currentPeriodStart = $event->data->object->lines->data[0]->period->start;
        }
        
        Log::info('Extracted subscription and period data from event', [
            'subscription_id' => $this->subscriptionId,
            'current_period_end' => $this->currentPeriodEnd,
            'current_period_start' => $this->currentPeriodStart,
            'event_type' => $event->type,
            'has_parent' => isset($event->data->object->parent),
            'parent_type' => $event->data->object->parent->type ?? null,
            'period_end_date' => $this->currentPeriodEnd ? \Carbon\Carbon::createFromTimestamp($this->currentPeriodEnd)->format('Y-m-d H:i:s') : null,
            'period_start_date' => $this->currentPeriodStart ? \Carbon\Carbon::createFromTimestamp($this->currentPeriodStart)->format('Y-m-d H:i:s') : null
        ]);

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object, $this->currentPeriodEnd);
                break;
            
            case 'invoice.paid':
                $this->handleInvoicePaid($event->data->object, $this->currentPeriodEnd);
                break;
            
            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;
            
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
            
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;
            
            default:
                Log::info('Unhandled Stripe webhook event type: ' . $event->type, [
                    'event_id' => $event->id,
                    'event_type' => $event->type
                ]);
        }

        return response('OK', 200);
    }

    private function handleSubscriptionCreated($subscription)
    {
        Log::info('Subscription created via webhook', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
            'customer_id' => $subscription->customer,
            'billing_cycle_anchor' => $subscription->billing_cycle_anchor,
            'current_period_start' => $subscription->current_period_start,
            'current_period_end' => $subscription->current_period_end
        ]);

        // Find RecurringPayment record
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $subscription->id);
            return;
        }

        // Get current_period_end from the subscription object
        $currentPeriodEnd = null;
        if (isset($subscription->current_period_end)) {
            $currentPeriodEnd = $subscription->current_period_end;
        } elseif (isset($subscription->items) && isset($subscription->items->data[0]->current_period_end)) {
            // Fallback to get it from subscription items
            $currentPeriodEnd = $subscription->items->data[0]->current_period_end;
        }

        // Update subscription details
        $recurringPayment->update([
            'payment_status' => $subscription->status,
            'next_payment_date' => \Carbon\Carbon::createFromTimestamp($currentPeriodEnd)
        ]);

        Log::info('Updated RecurringPayment for subscription creation', [
            'recurring_payment_id' => $recurringPayment->id,
            'subscription_status' => $subscription->status,
            'next_payment_date' => \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)->format('Y-m-d H:i:s')
        ]);
    }

    private function handleInvoicePaymentSucceeded($invoice, $extractedPeriodEnd = null)
    {
        Log::info('Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'subscription_id' => $invoice->subscription,
            'amount_paid' => $invoice->amount_paid,
            'currency' => $invoice->currency,
            'customer_id' => $invoice->customer,
            'payment_intent' => $invoice->payment_intent,
            'billing_reason' => $invoice->billing_reason ?? 'subscription_cycle',
            'period_start' => $invoice->period_start ?? null,
            'period_end' => $invoice->period_end ?? null
        ]);

        if (!$invoice->subscription) {
            Log::info('Invoice payment succeeded but no subscription found', [
                'invoice_id' => $invoice->id
            ]);
            return;
        }

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $invoice->subscription, [
                'invoice_id' => $invoice->id,
                'subscription_id' => $invoice->subscription
            ]);
            return;
        }

        // Get subscription details for comprehensive updates
        $subscription = $this->getSubscriptionDetails($this->subscriptionId);
        
        // Calculate next payment date using extracted period data or fallback methods
        $nextPaymentDate = $this->calculateNextPaymentDateFromEvent($invoice, $subscription, $extractedPeriodEnd);
        
        // Update RecurringPayment record - increment total payments
        $recurringPayment->increment('total_payments');
        
        // Check if all expected payments are completed
        $isCompleted = $recurringPayment->total_payments >= $recurringPayment->total_payments_expected;
        
        // Determine payment status
        $paymentStatus = $isCompleted ? 'completed' : 'active';
        
        // Prepare comprehensive update data
        $updateData = [
            'last_payment_date' => now(),
            'payment_status' => $paymentStatus,
            'next_payment_date' => $isCompleted ? null : $nextPaymentDate,
        ];
        
        // If subscription is canceled or completed, update cancellation fields
        if ($isCompleted || ($subscription && in_array($subscription->status, ['canceled', 'incomplete_expired']))) {
            $updateData['canceled_at'] = now();
            $updateData['cancelation_reason'] = $isCompleted ? 'All payments completed' : 'Subscription canceled';
        }
        
        // Update RecurringPayment record with all fields
        $recurringPayment->update($updateData);

        // Update UserPlan status based on payment completion
        $userPlan = $recurringPayment->userPlan;
        Log::info('UserPlan found', [
            'user_plan_id' => $userPlan->id,
            'user_plan_status' => $userPlan->status
        ]);
        if ($userPlan) {
            $userPlanStatus = $isCompleted ? 'completed' : 'active';
            $userPlan->update(['status' => $userPlanStatus]);
        }

        $userPlanId = $recurringPayment->user_plan_id;
        $userPlan = UserPlan::find($userPlanId);
        $userId = $userPlan->user_id;
        $planId = $userPlan->plan_id;

        // Get customer details from Stripe to populate missing fields
        $customer = $this->getCustomerDetails($invoice->customer);
        
        // Create payment record for tracking
        Payment::create([
            'user_id' => $userId,
            'user_plan_id' => $userPlanId,
            'plan_id' => $planId,
            'price' => $invoice->amount_paid / 100, // Convert from cents
            'original_price' => $this->getOriginalPriceFromSubscription($invoice->subscription),
            'name' => $customer->name ?? $invoice->customer_name ?? 'Recurring Payment',
            'email' => $customer->email ?? $invoice->customer_email ?? '',
            'phone' => $customer->phone ?? '',
            'payment_intent_id' => $invoice->payment_intent,
            'status' => 'succeeded',
            'coupon_code' => null
        ]);

        Log::info('Recurring payment updated successfully - All fields updated', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_id' => $invoice->subscription,
            'total_payments' => $recurringPayment->total_payments,
            'total_payments_expected' => $recurringPayment->total_payments_expected,
            'payment_status' => $recurringPayment->payment_status,
            'last_payment_date' => $recurringPayment->last_payment_date?->format('Y-m-d H:i:s'),
            'next_payment_date' => $recurringPayment->next_payment_date?->format('Y-m-d H:i:s'),
            'canceled_at' => $recurringPayment->canceled_at?->format('Y-m-d H:i:s'),
            'cancelation_reason' => $recurringPayment->cancelation_reason,
            'is_completed' => $isCompleted,
            'remaining_payments' => max(0, $recurringPayment->total_payments_expected - $recurringPayment->total_payments),
            'progress' => $recurringPayment->total_payments . ' of ' . $recurringPayment->total_payments_expected,
            'invoice_id' => $invoice->id,
            'amount_paid' => $invoice->amount_paid / 100,
            'billing_reason' => $invoice->billing_reason ?? 'subscription_cycle',
            'testing_mode' => config('services.stripe.testing_mode', false),
            'subscription_canceled' => $isCompleted
        ]);
    }

    private function handleInvoicePaid($invoice, $extractedPeriodEnd = null)
    {
        $invoice->subscription = $this->subscriptionId;
        Log::info('Invoice paid webhook received', [
            'invoice_id' => $invoice->id,
            'subscription_id' => $this->subscriptionId,
            'amount_paid' => $invoice->amount_paid,
            'currency' => $invoice->currency,
            'customer_id' => $invoice->customer,
            'payment_intent' => $invoice->payment_intent,
            'billing_reason' => $invoice->billing_reason ?? 'subscription_cycle',
            'period_start' => $invoice->period_start ?? null,
            'period_end' => $invoice->period_end ?? null,
            'status' => $invoice->status ?? 'paid',
            'invoice' => $invoice
        ]);

        if (!$invoice->subscription) {
            Log::info('Invoice paid but no subscription found', [
                'invoice_id' => $invoice->id
            ]);
            return;
        }

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $invoice->subscription, [
                'invoice_id' => $invoice->id,
                'subscription_id' => $invoice->subscription
            ]);
            return;
        }

        // Get subscription details for comprehensive updates
        $subscription = $this->getSubscriptionDetails($this->subscriptionId);
        
        // Calculate next payment date using extracted period data or fallback methods
        $nextPaymentDate = $this->calculateNextPaymentDateFromEvent($invoice, $subscription, $extractedPeriodEnd);
        
        // Update RecurringPayment record - increment total payments
        $recurringPayment->increment('total_payments');
        
        // Check if all expected payments are completed
        $isCompleted = $recurringPayment->total_payments >= $recurringPayment->total_payments_expected;
        
        // Determine payment status
        $paymentStatus = $isCompleted ? 'completed' : 'active';
        
        // Prepare comprehensive update data
        $updateData = [
            'last_payment_date' => now(),
            'payment_status' => $paymentStatus,
            'next_payment_date' => $isCompleted ? null : $nextPaymentDate,
        ];
        
        // If all payments are completed, automatically cancel the subscription
        if ($isCompleted) {
            $updateData['canceled_at'] = now();
            $updateData['cancelation_reason'] = 'All payments completed - subscription automatically canceled';
            
            // Cancel the Stripe subscription
            $this->cancelStripeSubscription($invoice->subscription, 'All 8 payments completed');
        } elseif ($subscription && in_array($subscription->status, ['canceled', 'incomplete_expired'])) {
            $updateData['canceled_at'] = now();
            $updateData['cancelation_reason'] = 'Subscription canceled';
        }
        
        // Update RecurringPayment record with all fields
        $recurringPayment->update($updateData);

        // Update UserPlan status based on payment completion
        $userPlan = $recurringPayment->userPlan;
        Log::info('UserPlan found', [
            'user_plan_id' => $userPlan->id,
            'user_plan_status' => $userPlan->status
        ]);
        if ($userPlan) {
            $userPlanStatus = $isCompleted ? 'completed' : 'active';
            $userPlan->update(['status' => $userPlanStatus]);
        }

        $userPlanId = $recurringPayment->user_plan_id;
        $userPlan = UserPlan::find($userPlanId);
        $userId = $userPlan->user_id;
        $planId = $userPlan->plan_id;

        // Get customer details from Stripe to populate missing fields
        $customer = $this->getCustomerDetails($invoice->customer);
        
        // Create payment record for tracking
        Payment::create([
            'user_id' => $userId,
            'user_plan_id' => $userPlanId,
            'plan_id' => $planId,
            'price' => $invoice->amount_paid / 100, // Convert from cents
            'original_price' => $this->getOriginalPriceFromSubscription($invoice->subscription),
            'name' => $customer->name ?? $invoice->customer_name ?? 'Recurring Payment',
            'email' => $customer->email ?? $invoice->customer_email ?? '',
            'phone' => $customer->phone ?? '',
            'payment_intent_id' => $invoice->payment_intent,
            'status' => 'succeeded',
            'coupon_code' => null
        ]);

        Log::info('Recurring payment updated successfully via invoice.paid - All fields updated', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_id' => $invoice->subscription,
            'total_payments' => $recurringPayment->total_payments,
            'total_payments_expected' => $recurringPayment->total_payments_expected,
            'payment_status' => $recurringPayment->payment_status,
            'last_payment_date' => $recurringPayment->last_payment_date?->format('Y-m-d H:i:s'),
            'next_payment_date' => $recurringPayment->next_payment_date?->format('Y-m-d H:i:s'),
            'canceled_at' => $recurringPayment->canceled_at?->format('Y-m-d H:i:s'),
            'cancelation_reason' => $recurringPayment->cancelation_reason,
            'is_completed' => $isCompleted,
            'remaining_payments' => max(0, $recurringPayment->total_payments_expected - $recurringPayment->total_payments),
            'progress' => $recurringPayment->total_payments . ' of ' . $recurringPayment->total_payments_expected,
            'invoice_id' => $invoice->id,
            'amount_paid' => $invoice->amount_paid / 100,
            'billing_reason' => $invoice->billing_reason ?? 'subscription_cycle',
            'testing_mode' => config('services.stripe.testing_mode', false),
            'webhook_event' => 'invoice.paid',
            'subscription_canceled' => $isCompleted
        ]);
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Invoice payment failed', [
            'invoice_id' => $invoice->id, 
            'subscription_id' => $invoice->subscription,
            'amount_due' => $invoice->amount_due ?? null,
            'currency' => $invoice->currency ?? null,
            'customer_id' => $invoice->customer ?? null,
            'billing_reason' => $invoice->billing_reason ?? 'subscription_cycle'
        ]);

        if (!$invoice->subscription) {
            Log::info('Invoice payment failed but no subscription found', [
                'invoice_id' => $invoice->id
            ]);
            return;
        }

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $invoice->subscription, [
                'invoice_id' => $invoice->id,
                'subscription_id' => $invoice->subscription
            ]);
            return;
        }

        // Get subscription details for comprehensive updates
        $subscription = $this->getSubscriptionDetails($this->subscriptionId);
        
        // Prepare comprehensive update data for failed payment
        $updateData = [
            'payment_status' => 'past_due',
            'last_payment_date' => now(), // Update last attempt date
        ];
        
        // If subscription is canceled due to failed payment, update cancellation fields
        if ($subscription && in_array($subscription->status, ['canceled', 'incomplete_expired', 'unpaid'])) {
            $updateData['canceled_at'] = now();
            $updateData['cancelation_reason'] = 'Payment failed - subscription canceled';
        }
        
        // Update RecurringPayment record with all fields
        $recurringPayment->update($updateData);

        // Update UserPlan status based on subscription status
        $userPlan = $recurringPayment->userPlan;
        Log::info('UserPlan found', [
            'user_plan_id' => $userPlan->id,
            'user_plan_status' => $userPlan->status
        ]);
        if ($userPlan) {
            $userPlanStatus = ($subscription && in_array($subscription->status, ['canceled', 'incomplete_expired', 'unpaid'])) ? 'cancelled' : 'pending';
            $userPlan->update(['status' => $userPlanStatus]);
        }

        Log::warning('Payment failed - recurring payment updated comprehensively', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_id' => $invoice->subscription,
            'payment_status' => $recurringPayment->payment_status,
            'last_payment_date' => $recurringPayment->last_payment_date?->format('Y-m-d H:i:s'),
            'canceled_at' => $recurringPayment->canceled_at?->format('Y-m-d H:i:s'),
            'cancelation_reason' => $recurringPayment->cancelation_reason,
            'subscription_status' => $subscription->status ?? 'unknown',
            'invoice_id' => $invoice->id,
            'amount_due' => $invoice->amount_due ?? null
        ]);
    }

    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Subscription updated', [
            'subscription_id' => $subscription->id, 
            'status' => $subscription->status, 
            'current_period_end' => $subscription->current_period_end,
            'current_period_start' => $subscription->current_period_start ?? null,
            'billing_cycle_anchor' => $subscription->billing_cycle_anchor ?? null,
            'cancel_at_period_end' => $subscription->cancel_at_period_end ?? false
        ]);

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $subscription->id);
            return;
        }

        // Get current_period_end from the subscription object
        $currentPeriodEnd = null;
        if (isset($subscription->current_period_end)) {
            $currentPeriodEnd = $subscription->current_period_end;
        } elseif (isset($subscription->items) && isset($subscription->items->data[0]->current_period_end)) {
            // Fallback to get it from subscription items
            $currentPeriodEnd = $subscription->items->data[0]->current_period_end;
        }

        Log::info('Current period end extracted', [
            'subscription_id' => $subscription->id,
            'current_period_end_timestamp' => $currentPeriodEnd,
            'current_period_end_date' => $currentPeriodEnd ? \Carbon\Carbon::createFromTimestamp($currentPeriodEnd)->format('Y-m-d H:i:s') : null
        ]);

        // Calculate next payment date
        $nextPaymentDate = null;
        if ($currentPeriodEnd && $currentPeriodEnd > 0) {
            $nextPaymentDate = \Carbon\Carbon::createFromTimestamp($currentPeriodEnd);
            if (!$nextPaymentDate->isFuture()) {
                $nextPaymentDate = null; // Don't set past dates
            }
        }

        // Prepare comprehensive update data
        $updateData = [
            'payment_status' => $subscription->status,
        ];
        
        // Only update next_payment_date if it's valid and in the future
        if ($nextPaymentDate) {
            $updateData['next_payment_date'] = $nextPaymentDate;
        }
        
        // Handle cancellation scenarios
        if (in_array($subscription->status, ['canceled', 'unpaid', 'incomplete_expired'])) {
            $updateData['canceled_at'] = now();
            $updateData['cancelation_reason'] = 'Subscription canceled or expired';
            $updateData['next_payment_date'] = null; // No more payments
        }

        // Update RecurringPayment record with all fields
        $recurringPayment->update($updateData);

        // Update UserPlan status based on subscription status
        $userPlan = $recurringPayment->userPlan;
        Log::info('UserPlan found', [
            'user_plan_id' => $userPlan->id,
            'user_plan_status' => $userPlan->status
        ]);
        if ($userPlan) {
            if (in_array($subscription->status, ['canceled', 'unpaid', 'incomplete_expired'])) {
                $userPlan->update([
                    'status' => 'cancelled',
                    'canceled_at' => now(),
                    'cancelation_reason' => 'Payment declined or subscription canceled'
                ]);
            } else {
                $status = $subscription->status === 'active' ? 'active' : 'pending';
                $userPlan->update(['status' => $status]);
            }
        }

        Log::info('Recurring payment updated comprehensively from subscription', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_status' => $subscription->status,
            'payment_status' => $recurringPayment->payment_status,
            'next_payment_date' => $recurringPayment->next_payment_date?->format('Y-m-d H:i:s'),
            'canceled_at' => $recurringPayment->canceled_at?->format('Y-m-d H:i:s'),
            'cancelation_reason' => $recurringPayment->cancelation_reason,
            'cancel_at_period_end' => $subscription->cancel_at_period_end ?? false
        ]);
    }

    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Subscription deleted', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status ?? 'deleted',
            'canceled_at' => $subscription->canceled_at ?? null,
            'cancel_at_period_end' => $subscription->cancel_at_period_end ?? false
        ]);

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $subscription->id);
            return;
        }

        // Prepare comprehensive update data for deletion
        $updateData = [
            'payment_status' => 'canceled',
            'canceled_at' => now(),
            'cancelation_reason' => 'Subscription canceled by user or admin',
            'next_payment_date' => null, // No more payments
        ];

        // Update RecurringPayment record with all fields
        $recurringPayment->update($updateData);

        // Update UserPlan status to cancelled
        $userPlan = $recurringPayment->userPlan;
        Log::info('UserPlan found', [
            'user_plan_id' => $userPlan->id,
            'user_plan_status' => $userPlan->status
        ]);
        if ($userPlan) {
            $userPlan->update([
                'status' => 'cancelled',
                'canceled_at' => now(),
                'cancelation_reason' => 'Subscription canceled by user or admin'
            ]);
        }

        Log::info('Recurring payment cancelled comprehensively', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_id' => $subscription->id,
            'payment_status' => $recurringPayment->payment_status,
            'canceled_at' => $recurringPayment->canceled_at?->format('Y-m-d H:i:s'),
            'cancelation_reason' => $recurringPayment->cancelation_reason,
            'next_payment_date' => $recurringPayment->next_payment_date?->format('Y-m-d H:i:s'),
            'total_payments' => $recurringPayment->total_payments,
            'total_payments_expected' => $recurringPayment->total_payments_expected
        ]);
    }

    private function getSubscriptionDetails($subscriptionId)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            return \Stripe\Subscription::retrieve($subscriptionId);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve subscription details: ' . $e->getMessage(), [
                'subscription_id' => $subscriptionId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function calculateNextPaymentDateFromEvent($invoice, $subscription, $extractedPeriodEnd = null)
    {
        // First try to use the extracted period_end from webhook event
        if ($extractedPeriodEnd && $extractedPeriodEnd > 0) {
            $nextPaymentDate = \Carbon\Carbon::createFromTimestamp($extractedPeriodEnd);
            if ($nextPaymentDate->isFuture()) {
                Log::info('Using extracted period_end from webhook event for next payment date', [
                    'extracted_period_end' => $extractedPeriodEnd,
                    'next_payment_date' => $nextPaymentDate->format('Y-m-d H:i:s')
                ]);
                return $nextPaymentDate;
            }
        }
        
        // Fallback to original method
        return $this->calculateNextPaymentDate($invoice, $subscription);
    }

    private function calculateNextPaymentDate($invoice, $subscription)
    {
        // First try to get next payment date from invoice period_end
        if ($invoice->period_end && $invoice->period_end > 0) {
            $nextPaymentDate = \Carbon\Carbon::createFromTimestamp($invoice->period_end);
            if ($nextPaymentDate->isFuture()) {
                Log::info('Using invoice period_end for next payment date', [
                    'period_end' => $invoice->period_end,
                    'next_payment_date' => $nextPaymentDate->format('Y-m-d H:i:s')
                ]);
                return $nextPaymentDate;
            }
        }
        
        // Fallback to subscription current_period_end
        if ($subscription && $subscription->current_period_end && $subscription->current_period_end > 0) {
            $nextPaymentDate = \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
            if ($nextPaymentDate->isFuture()) {
                Log::info('Using subscription current_period_end for next payment date', [
                    'current_period_end' => $subscription->current_period_end,
                    'next_payment_date' => $nextPaymentDate->format('Y-m-d H:i:s')
                ]);
                return $nextPaymentDate;
            }
        }
        
        // Final fallback based on testing mode
        $fallbackInterval = config('services.stripe.testing_mode', false) ? 'addMinutes' : 'addMonth';
        $fallbackAmount = config('services.stripe.testing_mode', false) ? 1 : 1;
        
        $fallbackDate = now()->$fallbackInterval($fallbackAmount);
        
        Log::warning('Using fallback for next payment date', [
            'testing_mode' => config('services.stripe.testing_mode', false),
            'fallback_method' => $fallbackInterval,
            'fallback_amount' => $fallbackAmount,
            'fallback_date' => $fallbackDate->format('Y-m-d H:i:s')
        ]);
        
        return $fallbackDate;
    }

    private function cancelStripeSubscription($subscriptionId, $reason = 'All payments completed')
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            Log::info('Attempting to cancel Stripe subscription', [
                'subscription_id' => $subscriptionId,
                'reason' => $reason
            ]);
            
            // Update the subscription to set metadata before actual cancellation
            $subscription = \Stripe\Subscription::update($subscriptionId, [
                'cancel_at_period_end' => false, // Cancel immediately, not at period end
                'metadata' => [
                    'cancellation_reason' => $reason,
                    'cancellation_date' => now()->format('Y-m-d H:i:s'),
                    'cancellation_source' => 'webhook_automatic'
                ]
            ]);
            
            // Actually cancel the subscription immediately
            $canceledSubscription = $subscription->cancel(['prorate' => false]); // Do not prorate
            
            Log::info('Stripe subscription canceled successfully', [
                'subscription_id' => $subscriptionId,
                'canceled_at' => $canceledSubscription->canceled_at,
                'status' => $canceledSubscription->status,
                'reason' => $reason
            ]);
            
            return $canceledSubscription;
            
        } catch (\Exception $e) {
            Log::error('Failed to cancel Stripe subscription: ' . $e->getMessage(), [
                'subscription_id' => $subscriptionId,
                'reason' => $reason,
                'error' => $e->getMessage()
            ]);
            
            // Don't throw exception - we don't want to break the webhook processing
            // The subscription will remain active, but our database will be updated
            return null;
        }
    }

    /**
     * Get customer details from Stripe
     */
    private function getCustomerDetails($customerId)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $customer = \Stripe\Customer::retrieve($customerId);
            
            return (object) [
                'name' => $customer->name ?? null,
                'email' => $customer->email ?? null,
                'phone' => $customer->phone ?? null
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get customer details: ' . $e->getMessage(), [
                'customer_id' => $customerId
            ]);
            return (object) [
                'name' => null,
                'email' => null,
                'phone' => null
            ];
        }
    }

    /**
     * Get original price from subscription or plan
     */
    private function getOriginalPriceFromSubscription($subscriptionId)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);
            
            if ($subscription && $subscription->items && $subscription->items->data) {
                $price = $subscription->items->data[0]->price->unit_amount ?? null;
                return $price ? $price / 100 : null; // Convert from cents
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get original price from subscription: ' . $e->getMessage(), [
                'subscription_id' => $subscriptionId
            ]);
            return null;
        }
    }
}