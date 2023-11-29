<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Merchant extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name', 'business_name', 'email', 'phone_number', 'password', 'referred_by', 't_and_c', 'otp'
    ];

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function payment_points()
    {
        return $this->hasMany(Payment_point::class);
    }
}
