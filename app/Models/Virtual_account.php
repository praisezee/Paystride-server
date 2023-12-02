<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Virtual_account extends Model
{
    use HasFactory;

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function paymentPoint(){
        return $this->belongTo(PaymentPoint::class);
    }
}
