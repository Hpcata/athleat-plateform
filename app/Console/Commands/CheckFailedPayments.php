<?php

namespace App\Console\Commands;

use App\Models\UserPlan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class CheckFailedPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check-failed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for failed recurring payments and deactivate plans';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for failed recurring payments...');

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Get all active recurring plans
            $recurringPlans = UserPlan::where('is_recurring', true)
                ->where('status', 'active')
                ->whereNotNull('stripe_subscription_id')
                ->get();

            $deactivatedCount = 0;

            foreach ($recurringPlans as $userPlan) {
                try {
                    // Get subscription from Stripe
                    $subscription = \Stripe\Subscription::retrieve($userPlan->stripe_subscription_id);

                    // Check if subscription is in a failed state
                    if (in_array($subscription->status, ['canceled', 'unpaid', 'incomplete_expired', 'past_due'])) {
                        $this->warn("Deactivating plan for user {$userPlan->user_id} - Subscription status: {$subscription->status}");

                        $userPlan->update([
                            'status' => 'deactivated',
                            'payment_status' => $subscription->status,
                            'canceled_at' => now(),
                            'cancelation_reason' => 'Payment failed - Subscription status: ' . $subscription->status
                        ]);

                        $deactivatedCount++;

                        Log::warning('Plan deactivated due to failed payment', [
                            'user_id' => $userPlan->user_id,
                            'plan_id' => $userPlan->plan_id,
                            'subscription_id' => $userPlan->stripe_subscription_id,
                            'subscription_status' => $subscription->status
                        ]);
                    } else {
                        // Update payment status if it's different
                        if ($userPlan->payment_status !== $subscription->status) {
                            $userPlan->update([
                                'payment_status' => $subscription->status
                            ]);

                            $this->info("Updated payment status for user {$userPlan->user_id}: {$subscription->status}");
                        }
                    }

                } catch (\Exception $e) {
                    $this->error("Error checking subscription {$userPlan->stripe_subscription_id}: " . $e->getMessage());
                    Log::error('Error checking subscription', [
                        'subscription_id' => $userPlan->stripe_subscription_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $this->info("Completed checking {$recurringPlans->count()} recurring plans.");
            $this->info("Deactivated {$deactivatedCount} plans due to failed payments.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error checking failed payments: ' . $e->getMessage());
            Log::error('Failed to check payments', ['error' => $e->getMessage()]);
            return Command::FAILURE;
        }
    }
}