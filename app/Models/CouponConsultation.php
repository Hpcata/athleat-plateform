<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponConsultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'consultation_id',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
