<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement_Account extends Model
{
    use HasFactory;
    protected $fillable = [
        'account_name', 'account_number','bank_code','merchant_id'
    ];
    public function merchant () {
        return $this->belongsTo(Merchant::class);
    }
}
