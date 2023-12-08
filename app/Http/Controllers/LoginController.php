<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
        if ($validator->fails()) return response(['message' => $validator->errors()->first()], 400);

        $merchant = Merchant::where('email',$request->email)->first();

        $passwordMatch = Hash::check($request->password,$merchant->password);

        if(!$merchant || !$passwordMatch) return response(['message'=>"Invalid Login credentials"],401);

        $token = $merchant->createToken('token')->plainTextToken;
        $loginResource = new LoginResource($merchant);
        return response([
            'message'=>'Logged in successfully',
            'token'=>$token,
            'data'=>$loginResource
        ],200);

    }

    /* public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response(['message'=>"Logged out successfully"],200);
    } */
}
