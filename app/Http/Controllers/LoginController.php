<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Http\Resources\StaffResources;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class LoginController extends Controller
{
    //
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
        if ($validator->fails()) return response(['message' => $validator->errors()->first()], 400);
        $staff = Staff::where('email',$request->email)->first();
        if($staff){
            return $this->staffLogin($request->email,$request->password);
        }

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


    private function staffLogin ($email,$password) {
        $staff = Staff::where('email',$email)->first();
        $passwordMatch = Hash::check($password, $staff->password);

        if ( !$passwordMatch) return response(['message'=>"Invalid Login credentials"],401);
        $token = $staff->createToken("token")->plainTextToken;
        $staffResource = new StaffResources($staff);

        return response([
            'message'=>'Logged in successfully',
            'token'=>$token,
            'data'=>$staffResource
        ],200);



    }
    /* public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response(['message'=>"Logged out successfully"],200);
    } */
}
