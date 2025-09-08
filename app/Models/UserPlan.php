<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;

    protected $table = 'user_plans';

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'is_recurring',
        'stripe_subscription_id',
        'total_payments',
        'total_payments_expected',
        'next_payment_date',
        'last_payment_date',
        'payment_status',
        'canceled_at',
        'cancelation_reason',
        'modified_by',
        'is_mail_sent',
        'mail_sent_at',
        'nutrition_info_flag'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
        'total_payments' => 'integer',
        'total_payments_expected' => 'integer',
        'next_payment_date' => 'datetime',
        'last_payment_date' => 'datetime',
        'canceled_at' => 'datetime',
        'is_mail_sent' => 'boolean',
        'mail_sent_at' => 'datetime',
        'nutrition_info_flag' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function userCategories()
    {
        return $this->hasMany(UserCategory::class, 'user_plan_id');
    }

    public function userSubCategories()
    {
        return $this->hasMany(UserSubCategory::class, 'user_plan_id');
    }

    public function userMeals()
    {
        return $this->hasMany(UserMeal::class, 'user_plan_id');
    }

    public function userItems()
    {
        return $this->hasMany(UserItem::class, 'user_plan_id');
    }
}
