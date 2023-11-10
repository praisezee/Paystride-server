<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MerchantController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function register(Request $request)
    {

        if(count($request->all()) < 1){
            return new Response([
                'message' => 'All fields are required'
            ],400);
        };

        $duplicateUser = Merchant::where('email' , $request->email);

        if(count($duplicateUser->get()) >= 1) {
            return new Response([
                'message' => 'Email already used'
            ],409);
        };

        $hahedPassword = Hash::make($request->password);
        $newMerchant = Merchant::create([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $hahedPassword,
            'referred_by' => $request->referred_by,
            't_and_c' => $request->t_and_c,
            'token' => $request->token
        ]);

        return $newMerchant;
    }

    // Function to handle a merchant forgotten password

    public function forget_password(Request $request){
        if(count($request->all()) < 1){
            return new Response([
                'message' => 'All fields are required'
            ],400);
        };

        $user = Merchant::where('email', $request->email);
        if(!$user->exists()){
            return new Response([
                'message' => 'Invalid email'
            ],401);
        }

        $token = [Str::random(20)];

        $url = route('/api/reset_password',$token);
        return Mail::to($request->email)->send(new ForgetPassword($url));

    }

    public function reset_password(Request $request){
        if(count($request->all()) < 1){
            return new Response([
                'message' => 'All fields are required'
            ],400);
        };

        $user = Merchant::where('email', $request->email);
        if(!$user->exists()){
            return new Response([
                'message' => 'Invalid email'
            ],401);
        }

        $match = Hash::check($request->old_password, $user->password);
        if (!$match) {
            return new Response([
                'message' => 'Old Password is incorrect'
            ],403);
        }

        $newPassword = Hash::make($request->new_password);

        $user->password = $newPassword;
        $user->save();

        return new Response([
            'message' => 'Password has been updated successfully.'
        ],200);
    }

}
