<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserPlan;
use App\Models\RecurringPayment;
use Illuminate\Http\Request;

class RecurringPaymentsController extends Controller
{
    /**
     * Display a listing of recurring payments
     */
    public function index()
    {
        $recurringPayments = RecurringPayment::with(['userPlan.user', 'userPlan.plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.recurring-payments.index', compact('recurringPayments'));
    }

    /**
     * Show details of a specific recurring payment
     */
    public function show($id)
    {
        $recurringPayment = RecurringPayment::with(['userPlan.user', 'userPlan.plan'])
            ->findOrFail($id);

        return view('admin.recurring-payments.show', compact('recurringPayment'));
    }

    /**
     * Cancel a recurring subscription
     */
    public function cancel(Request $request, $id)
    {
        $recurringPayment = RecurringPayment::findOrFail($id);
        
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            if ($recurringPayment->stripe_subscription_id) {
                $subscription = \Stripe\Subscription::retrieve($recurringPayment->stripe_subscription_id);
                $subscription->cancel();
            }

            $recurringPayment->update([
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