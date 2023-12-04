<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\PaymentPoint;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentPointController extends Controller
{
    //
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'phone_number'=> 'required|string',
            'merchant_id'=> 'required|integer',
            'staff_email'=>'required|string'
        ]);
        if ($validator -> fails()){
            return response(['message'=> $validator->errors()->first()],400);
        }
        $merchant = Merchant::where('id',intval($request->merchant_id))
            ->first();

        $staff = Staff::where('email',$request->staff_email)
            ->where('merchant_id', intval($request->merchant_id))
            ->first();

        if(!$merchant || !$staff) return response(['message'=>'Invalid merchant or staff'],400);
        $newPayPoint = PaymentPoint::create([
            'name'=>$request['name'],
            'phone_number'=>$request['phone_number'],
            'merchant_id'=> $request['merchant_id'],
            'staff_id'=> $staff['id']
        ]);

        return response(['message'=>'Paypoint created', $newPayPoint],201);
    }

    public function getSinglePaypoint(string $id){
        $payment__point = PaymentPoint::where('id', intval($id))
            ->first();
        if (!$payment__point) return response(['message'=> 'Payment Point does not exist'],400) ;
        return response(['data'=>$payment__point],200);
    }

    public function getAllPaymentPoint (string $id){
        $merchant = Merchant::where('id', intval($id))
            ->first();
        if(!$merchant) return response(['message' => 'Merchant not found'], 400);
        $paymentsPoints = PaymentPoint::where('merchant_id', intval($id))->get();
        return response(['payment_points' => $paymentsPoints]);
    }

    public function editPaymentPoint (Request $request, $id){
        $validator = Validator::make($request->all(),[
            'name' => 'nullable|string',
            'status' => 'nullable|boolean',
            'staff_email' => 'nullable|string',
            'merchant_id' => 'required|string',
        ]);
        if ($validator -> fails()){
            return response(['message'=> $validator->errors()->first()],400);
        }
        $merchant = Merchant::where('id', intval($request->merchant_id));
        $staff = Staff::where('email',$request->staff_email)->where('merchant_id', intval($request->merchant_id));
        if(!$merchant || !$staff) return response(['message'=>'Invalid merchant or staff'],400);
        $payment_point = PaymentPoint::where('id', intval($id))
        ->where("merchant_id", $merchant->id)
        ->where('staff_id', $staff->id)
        ->first();
        if (!$payment_point) return response(['message'=> 'Paymentpoint not found'],400);

        DB::transaction(function () use ($payment_point,$request,$staff){
            $payment_point->name = $request->name ? $request->name : $payment_point->name;
            $payment_point->status = $request->status ? $request->status : $payment_point->status;
            $payment_point->staff_id = $request->staff_email ? $staff->id : $payment_point->staff_id;
        });
        return response(['message'=> 'payment poin has been updated'], 202);
    }

    public function deletePaymentPoint ($id){
        $payment_point = PaymentPoint::where('id', intval($id));
        if(!$payment_point) return response(['message' => 'Payment point does not exist'],401);
        DB::transaction(function () use ($payment_point){
            $payment_point->delete();
        });
        return response(['message'=> 'Payment point was deleted successfully']);
    }
}
