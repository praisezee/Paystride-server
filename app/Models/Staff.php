<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function payment_point(){
        return $this->hasOne(Payment_point::class);
    }
}
