<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class RecurringPaymentsController extends Controller
{
    /**
     * Display a listing of recurring payments
     */
    public function index()
    {
        $recurringPlans = UserPlan::with(['user', 'plan'])
            ->where('is_recurring', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.recurring-payments.index', compact('recurringPlans'));
    }

    /**
     * Show details of a specific recurring payment
     */
    public function show($id)
    {
        $userPlan = UserPlan::with(['user', 'plan'])
            ->where('is_recurring', true)
            ->findOrFail($id);

        return view('admin.recurring-payments.show', compact('userPlan'));
    }

    /**
     * Cancel a recurring subscription
     */
    public function cancel(Request $request, $id)
    {
        $userPlan = UserPlan::where('is_recurring', true)->findOrFail($id);
        
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            if ($userPlan->stripe_subscription_id) {
                $subscription = \Stripe\Subscription::retrieve($userPlan->stripe_subscription_id);
                $subscription->cancel();
            }

            $userPlan->update([
                'status' => 'deactivated',
                'payment_status' => 'canceled',
                'canceled_at' => now(),
                'cancelation_reason' => 'Canceled by admin: ' . $request->input('reason', 'No reason provided')
            ]);

            return redirect()->back()->with('success', 'Subscription canceled successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }
}