<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','business_name','email','phone_number','password', 'referred_by', 't_and_c', 'token'
    ];

    public function staff () {
        return $this->hasMany(Staff::class);
    }

    public function payment_point(){
        return $this->hasMany(Payment_point::class);
    }
}
