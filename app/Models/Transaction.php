<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_point_id',
        'virtual_account_id',
        'transaction_description',
        'transaction_type',
        'transaction_ref',
        'amount',
        'status',
    ];

    //Define the relationship with the PaymentPoint model
    public function paymentPoint(){
        return $this->belongsTo(PaymentPoint::class);
    }

    //define the relationship with the VirtualAccount model
    public function virtualAccount() {
        return $this->belongsTo(VirtualAccount::class);
    }
}
