<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPrePlan extends Model
{
    use HasFactory;

    protected $table = 'user_pre_plans';

    protected $fillable = [
        'payment_id',
        'user_id',
        'dob',
        'occupation',
        'address',
        'other',
        'referredBy',
        'culture',
        'sport_image',
    ];

    // Relationship with the User model (belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the Payment model (belongsTo)
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Relationship with the PrePlanDetail model (hasMany)
    public function prePlanDetails()
    {
        return $this->hasMany(PrePlanDetail::class);
    }

    public function PrePlanQuesionFile()
    {
        return $this->hasMany(PrePlanQuesionFile::class);
    }

    public function getUserAge()
    {
        $dob = $this->dob;
        return Carbon::parse($dob)->age;
    }

}
