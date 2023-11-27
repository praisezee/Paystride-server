<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Payment_point;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentPointController extends Controller
{
    //
    public function create(Request $request){
        $validaator = Validator::make($request->all(),[
            'name' => 'required|string',
            'phone_number'=> 'required|string',
            'merchant_id'=> 'required|integer',
            'staff_email'=>'required|string'
        ]);
        if ($validaator -> fails()){
            return response(['message'=> $validaator->errors()->first()],400);
        }
        $merchant = Merchant::where('id',intval($request->merchant_id))
            ->first();

        $staff = Staff::where('email',$request->staff_email)
            ->where('merchant_id', intval($request->merchant_id))
            ->first();

        if(!$merchant && !$staff) return response(['message'=>'Invalid merchant or staff'],400);
        $newPayPoint = Payment_point::create([
            'name'=>$request['name'],
            'phone_number'=>$request['phone_number'],
            'merchant_id'=> $request['merchant_id'],
            'staff_id'=> $staff['id']
        ]);

        return response(['message'=>'Paypoint created', $newPayPoint],201);
    }

    public function getSinglePaypoint(string $id){
        $payment__point = Payment_point::where('id', intval($id))
            ->first();
        if (!$payment__point) return response(['message'=> 'Payment Point does not exist'],400) ;
        return response(['data'=>$payment__point],200);
    }

    public function getAllPaymentPoint (string $id){
        $merchant = Merchant::where('id', intval($id))
            ->first();
        if(!$merchant) return response(['message' => 'Merchant not found'], 400);
        $paymentsPoints = Payment_point::where('merchant_id', intval($id))->get();
        return response(['payment_points' => $paymentsPoints]);
    }
}
