<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment_point extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','merchant_id','staff_id'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function staff(){
        return $this->belongsTo(Staff::class);
    }
    public function transaction(){
        return $this->hasMany(Transaction::class);
    }
}
