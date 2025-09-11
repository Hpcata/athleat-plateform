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
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload in Stripe webhook: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid signature in Stripe webhook: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        Log::info('Stripe webhook received: ' . $event->type, ['event_id' => $event->id]);

        switch ($event->type) {
            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
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
            
            default:
                Log::info('Unhandled Stripe webhook event type: ' . $event->type);
        }

        return response('OK', 200);
    }

    private function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Invoice payment succeeded', ['invoice_id' => $invoice->id, 'subscription_id' => $invoice->subscription]);

        if (!$invoice->subscription) {
            return;
        }

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $invoice->subscription);
            return;
        }

        // Update RecurringPayment record
        $recurringPayment->increment('total_payments');
        
        // Check if all expected payments are completed
        $isCompleted = $recurringPayment->total_payments >= $recurringPayment->total_payments_expected;
        
        $recurringPayment->update([
            'last_payment_date' => now(),
            'payment_status' => $isCompleted ? 'completed' : 'active',
            'next_payment_date' => $isCompleted ? null : $this->getNextPaymentDate($invoice->subscription)
        ]);

        // Update UserPlan status to active
        $userPlan = $recurringPayment->userPlan;
        if ($userPlan) {
            $userPlan->update(['status' => 'active']);
        }

        // Create payment record for tracking
        Payment::create([
            'user_id' => $recurringPayment->user_id,
            'plan_id' => $recurringPayment->plan_id,
            'price' => $invoice->amount_paid / 100, // Convert from cents
            'original_price' => $invoice->amount_paid / 100,
            'name' => $invoice->customer_name ?? 'Recurring Payment',
            'email' => $invoice->customer_email ?? '',
            'phone' => '',
            'payment_intent_id' => $invoice->payment_intent,
            'status' => 'succeeded',
            'coupon_code' => null
        ]);

        Log::info('Recurring payment updated', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'total_payments' => $recurringPayment->total_payments,
            'total_payments_expected' => $recurringPayment->total_payments_expected,
            'payment_status' => $recurringPayment->payment_status,
            'is_completed' => $isCompleted,
            'remaining_payments' => max(0, $recurringPayment->total_payments_expected - $recurringPayment->total_payments),
            'progress' => $recurringPayment->total_payments . ' of ' . $recurringPayment->total_payments_expected
        ]);
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Invoice payment failed', ['invoice_id' => $invoice->id, 'subscription_id' => $invoice->subscription]);

        if (!$invoice->subscription) {
            return;
        }

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $invoice->subscription);
            return;
        }

        // Update RecurringPayment status to past_due
        $recurringPayment->update([
            'payment_status' => 'past_due'
        ]);

        // Update UserPlan status to pending
        $userPlan = $recurringPayment->userPlan;
        if ($userPlan) {
            $userPlan->update(['status' => 'pending']);
        }

        Log::warning('Payment failed - recurring payment marked as past due', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_id' => $invoice->subscription
        ]);
    }

    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Subscription updated', ['subscription_id' => $subscription->id, 'status' => $subscription->status]);

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $subscription->id);
            return;
        }

        // Update RecurringPayment status
        $recurringPayment->update([
            'payment_status' => $subscription->status,
            'next_payment_date' => \Carbon\Carbon::createFromTimestamp($subscription->current_period_end)
        ]);

        // Update UserPlan status based on subscription status
        $userPlan = $recurringPayment->userPlan;
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

        Log::info('Recurring payment updated from subscription', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_status' => $subscription->status
        ]);
    }

    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Subscription deleted', ['subscription_id' => $subscription->id]);

        // Find RecurringPayment record using the new structure
        $recurringPayment = RecurringPayment::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$recurringPayment) {
            Log::warning('RecurringPayment not found for subscription: ' . $subscription->id);
            return;
        }

        // Update RecurringPayment status
        $recurringPayment->update([
            'payment_status' => 'canceled',
            'canceled_at' => now(),
            'cancelation_reason' => 'Subscription canceled by user or admin'
        ]);

        // Update UserPlan status to cancelled
        $userPlan = $recurringPayment->userPlan;
        if ($userPlan) {
            $userPlan->update([
                'status' => 'cancelled',
                'canceled_at' => now(),
                'cancelation_reason' => 'Subscription canceled by user or admin'
            ]);
        }

        Log::info('Recurring payment cancelled', [
            'recurring_payment_id' => $recurringPayment->id,
            'user_plan_id' => $userPlan->id ?? null,
            'subscription_id' => $subscription->id
        ]);
    }

    private function getNextPaymentDate($subscriptionId)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $subscription = \Stripe\Subscription::retrieve($subscriptionId);
            return \Carbon\Carbon::createFromTimestamp($subscription->current_period_end);
        } catch (\Exception $e) {
            Log::error('Failed to get next payment date: ' . $e->getMessage());
            return now()->addMonth();
        }
    }
}