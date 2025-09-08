<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
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

        $userPlan = UserPlan::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$userPlan) {
            Log::warning('UserPlan not found for subscription: ' . $invoice->subscription);
            return;
        }

        // Update payment tracking
        $userPlan->increment('total_payments');
        $userPlan->update([
            'last_payment_date' => now(),
            'payment_status' => 'active',
            'next_payment_date' => $this->getNextPaymentDate($invoice->subscription)
        ]);

        // Create payment record for tracking
        Payment::create([
            'user_id' => $userPlan->user_id,
            'plan_id' => $userPlan->plan_id,
            'price' => $invoice->amount_paid / 100, // Convert from cents
            'original_price' => $invoice->amount_paid / 100,
            'name' => $invoice->customer_name ?? 'Recurring Payment',
            'email' => $invoice->customer_email ?? '',
            'phone' => '',
            'payment_intent_id' => $invoice->payment_intent,
            'status' => 'succeeded',
            'coupon_code' => null
        ]);

        Log::info('Payment tracking updated', [
            'user_plan_id' => $userPlan->id,
            'total_payments' => $userPlan->total_payments,
            'payment_status' => $userPlan->payment_status
        ]);
    }

    private function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Invoice payment failed', ['invoice_id' => $invoice->id, 'subscription_id' => $invoice->subscription]);

        if (!$invoice->subscription) {
            return;
        }

        $userPlan = UserPlan::where('stripe_subscription_id', $invoice->subscription)->first();
        
        if (!$userPlan) {
            Log::warning('UserPlan not found for subscription: ' . $invoice->subscription);
            return;
        }

        // Update payment status to past_due
        $userPlan->update([
            'payment_status' => 'past_due'
        ]);

        Log::warning('Payment failed - plan status updated to past_due', [
            'user_plan_id' => $userPlan->id,
            'subscription_id' => $invoice->subscription
        ]);
    }

    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Subscription updated', ['subscription_id' => $subscription->id, 'status' => $subscription->status]);

        $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$userPlan) {
            Log::warning('UserPlan not found for subscription: ' . $subscription->id);
            return;
        }

        $userPlan->update([
            'payment_status' => $subscription->status
        ]);

        // If subscription is canceled or unpaid, deactivate the plan
        if (in_array($subscription->status, ['canceled', 'unpaid', 'incomplete_expired'])) {
            $userPlan->update([
                'status' => 'deactivated',
                'canceled_at' => now(),
                'cancelation_reason' => 'Payment declined or subscription canceled'
            ]);

            Log::warning('Plan deactivated due to subscription status', [
                'user_plan_id' => $userPlan->id,
                'subscription_status' => $subscription->status
            ]);
        }
    }

    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Subscription deleted', ['subscription_id' => $subscription->id]);

        $userPlan = UserPlan::where('stripe_subscription_id', $subscription->id)->first();
        
        if (!$userPlan) {
            Log::warning('UserPlan not found for subscription: ' . $subscription->id);
            return;
        }

        $userPlan->update([
            'status' => 'deactivated',
            'payment_status' => 'canceled',
            'canceled_at' => now(),
            'cancelation_reason' => 'Subscription canceled by user or admin'
        ]);

        Log::info('Plan deactivated due to subscription deletion', [
            'user_plan_id' => $userPlan->id,
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