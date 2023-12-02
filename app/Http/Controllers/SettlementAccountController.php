<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Settlement_Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettlementAccountController extends Controller
{
    //
    public function getAllSettlementAccounts (string $id){
        $settlements = Settlement_Account::where('merchant_id', intval($id))->get();
        return response(['accounts'=>$settlements],200);
    }




    public function createSettlementAccount (Request $request){
        $validator = Validator::make($request->all(),[
            'account_name' => 'required|string',
            'bank_name' => 'required|string',
            'account_number'=>'required|numeric',
            'merchant_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $merchant = Merchant::where('id',$request->merchant_id)->first();
        if (!$merchant) return response(['message'=> 'Merchant does not exist'],400);

        $settlement_account = Settlement_Account::where('merchant_id', $request->merchant_id)
        ->where('account_name', $request->account_name)
        ->where('account_number',$request->account_number)
        ->where('bank_name',$request->bamk_name)
        ->first();

        if ($settlement_account) return response(['message'=> 'This account already exists for this merchant'],400);


        Settlement_Account::create([
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'merchant_id' => $request->merchant_id
        ]);

        return response(['message'=> 'Settlement account added successfully'],201);
    }

    public function editSettlementAccount (Request $request, string $id){
        $validator = Validator::make($request->all(),[
            'account_name' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'account_number'=>'nullable|numeric',
            'merchant_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $settlement_account = Settlement_Account::where('id', intval($id))->where('merchant_id',$request->merchant_id)->first();

        if (!$settlement_account) return response(['message'=> 'Settlement account not found'],400);

        DB::transaction(function () use ($settlement_account,$request){
            $settlement_account->bank_name = $request->bank_name ? $request->bank_name : $settlement_account->bank_name;
            $settlement_account->account_name = $request->account_name ? $request->account_name : $settlement_account->account_name;
            $settlement_account->account_number = $request->account_number ? $request->account_number : $settlement_account->account_number;
            $settlement_account->save();
        });

        return response(['message' => 'Settlement account updated successfully'],200);
    }

    public function deleteSettlementAccount (string $id){
        $settlement_account = Settlement_Account::where('id', intval($id))->first();

        if (!$settlement_account) return response(['message'=> 'Settlement account not found'],400);

        $settlement_account->delete();

        return response(['message'=>"Account deleted sucessfully"]);
    }
}
