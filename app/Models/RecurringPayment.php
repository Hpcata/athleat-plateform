<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_plan_id',
        'stripe_subscription_id',
        'total_payments',
        'total_payments_expected',
        'next_payment_date',
        'last_payment_date',
        'payment_status',
        'canceled_at',
        'cancelation_reason'
    ];

    protected $casts = [
        'total_payments' => 'integer',
        'total_payments_expected' => 'integer',
        'next_payment_date' => 'datetime',
        'last_payment_date' => 'datetime',
        'canceled_at' => 'datetime'
    ];

    /**
     * Get the user plan that owns the recurring payment
     */
    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class);
    }

    /**
     * Get the user through the user plan
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, UserPlan::class, 'id', 'id', 'user_plan_id', 'user_id');
    }

    /**
     * Get the plan through the user plan
     */
    public function plan()
    {
        return $this->hasOneThrough(Plan::class, UserPlan::class, 'id', 'id', 'user_plan_id', 'plan_id');
    }

    /**
     * Scope to get active recurring payments
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'active');
    }

    /**
     * Scope to get canceled recurring payments
     */
    public function scopeCanceled($query)
    {
        return $query->where('payment_status', 'canceled');
    }

    /**
     * Scope to get payments due soon (within next 7 days)
     */
    public function scopeDueSoon($query)
    {
        return $query->where('next_payment_date', '<=', now()->addDays(7))
                    ->where('payment_status', 'active');
    }
}
