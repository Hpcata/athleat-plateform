<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\RecurringPayment;
use App\Models\UserPlan;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckRecurringPaymentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting recurring payment status check job');

        // Get all active recurring payments that are not cancelled
        $recurringPayments = RecurringPayment::where('payment_status', 'active')
            ->whereNull('canceled_at')
            ->with(['userPlan.user', 'userPlan.plan'])
            ->get();

        Log::info("Found {$recurringPayments->count()} active recurring payments to check");

        $cancelledCount = 0;
        $checkedCount = 0;

        foreach ($recurringPayments as $recurringPayment) {
            $checkedCount++;
            
            try {
                $shouldCancel = $this->shouldCancelSubscription($recurringPayment);
                
                if ($shouldCancel) {
                    $this->cancelSubscription($recurringPayment);
                    $cancelledCount++;
                    
                    Log::info("Cancelled subscription for user plan {$recurringPayment->user_plan_id}", [
                        'user_id' => $recurringPayment->userPlan->user_id,
                        'plan_id' => $recurringPayment->userPlan->plan_id,
                        'total_payments' => $recurringPayment->total_payments,
                        'expected_payments' => $recurringPayment->total_payments_expected
                    ]);
                }
            } catch (\Exception $e) {
                Log::error("Error checking recurring payment {$recurringPayment->id}: " . $e->getMessage(), [
                    'recurring_payment_id' => $recurringPayment->id,
                    'user_plan_id' => $recurringPayment->user_plan_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("Recurring payment status check completed", [
            'checked' => $checkedCount,
            'cancelled' => $cancelledCount
        ]);
    }

    /**
     * Check if subscription should be cancelled based on payment history
     */
    private function shouldCancelSubscription(RecurringPayment $recurringPayment): bool
    {
        // Get all payments for this user plan
        $payments = Payment::where('user_plan_id', $recurringPayment->user_plan_id)
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($payments->isEmpty()) {
            Log::warning("No payments found for user plan {$recurringPayment->user_plan_id}");
            return true; // Cancel if no payments found
        }

        $firstPaymentDate = $payments->first()->created_at;
        $lastPaymentDate = $payments->last()->created_at;
        $totalPayments = $payments->count();

        // Calculate months since first payment
        $monthsSinceStart = $firstPaymentDate->diffInMonths(now());
        $monthsSinceLastPayment = $lastPaymentDate->diffInMonths(now());

        Log::debug("Checking payment status for user plan {$recurringPayment->user_plan_id}", [
            'first_payment_date' => $firstPaymentDate->format('Y-m-d H:i:s'),
            'last_payment_date' => $lastPaymentDate->format('Y-m-d H:i:s'),
            'total_payments' => $totalPayments,
            'months_since_start' => $monthsSinceStart,
            'months_since_last_payment' => $monthsSinceLastPayment,
            'expected_payments' => $recurringPayment->total_payments_expected
        ]);

        // If it's been more than 2 months since last payment, cancel subscription
        if ($monthsSinceLastPayment >= 2) {
            Log::info("Subscription overdue - more than 2 months since last payment", [
                'user_plan_id' => $recurringPayment->user_plan_id,
                'months_since_last_payment' => $monthsSinceLastPayment
            ]);
            return true;
        }

        // Check if we have enough payments for the elapsed time
        // Expected payments = months since start + 1 (for current month)
        $expectedPayments = max(1, $monthsSinceStart + 1);
        
        // If we're missing more than 1 payment, cancel subscription
        $paymentGap = $expectedPayments - $totalPayments;
        
        if ($paymentGap > 1) {
            Log::info("Subscription overdue - missing {$paymentGap} payments", [
                'user_plan_id' => $recurringPayment->user_plan_id,
                'expected_payments' => $expectedPayments,
                'actual_payments' => $totalPayments,
                'payment_gap' => $paymentGap
            ]);
            return true;
        }

        // Check if subscription has reached its expected duration (8 months)
        if ($totalPayments >= $recurringPayment->total_payments_expected) {
            Log::info("Subscription completed - all {$recurringPayment->total_payments_expected} payments made", [
                'user_plan_id' => $recurringPayment->user_plan_id,
                'total_payments' => $totalPayments
            ]);
            return true;
        }

        return false;
    }

    /**
     * Cancel the subscription
     */
    private function cancelSubscription(RecurringPayment $recurringPayment): void
    {
        $reason = $this->getCancellationReason($recurringPayment);
        
        // Update recurring payment
        $recurringPayment->update([
            'payment_status' => 'canceled',
            'canceled_at' => now(),
            'cancelation_reason' => $reason
        ]);

        // Update user plan status
        $userPlan = $recurringPayment->userPlan;
        if ($userPlan) {
            $userPlan->update([
                'status' => 'cancelled'
            ]);
        }

        // TODO: Cancel Stripe subscription if needed
        // $this->cancelStripeSubscription($recurringPayment->stripe_subscription_id);
    }

    /**
     * Get cancellation reason based on payment status
     */
    private function getCancellationReason(RecurringPayment $recurringPayment): string
    {
        $payments = Payment::where('user_plan_id', $recurringPayment->user_plan_id)
            ->whereNotNull('created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($payments->isEmpty()) {
            return 'No payments found';
        }

        $lastPaymentDate = $payments->last()->created_at;
        $monthsSinceLastPayment = $lastPaymentDate->diffInMonths(now());
        $totalPayments = $payments->count();

        if ($monthsSinceLastPayment >= 2) {
            return "No payment for {$monthsSinceLastPayment} months";
        }

        if ($totalPayments >= $recurringPayment->total_payments_expected) {
            return 'All payments completed';
        }

        $firstPaymentDate = $payments->first()->created_at;
        $monthsSinceStart = $firstPaymentDate->diffInMonths(now());
        $expectedPayments = max(1, $monthsSinceStart + 1);
        $paymentGap = $expectedPayments - $totalPayments;

        return "Missing {$paymentGap} payments";
    }
}