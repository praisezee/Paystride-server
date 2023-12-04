<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'role', 'email', 'password', 'merchant_id','token'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function payment_points(){
        return $this->hasOne(PaymentPoint::class);
    }
}
