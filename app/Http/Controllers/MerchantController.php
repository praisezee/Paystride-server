<?php

namespace App\Http\Controllers;

use App\Mail\ForgetPassword;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        // $requestData = json_decode($request->getContent(), true);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'business_name' => 'required|string',
            'email' => 'required|email|unique:merchants,email',
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'referred_by' => 'nullable|string',
            't_and_c' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $hashedPassword = Hash::make($request->password);

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $newMerchant = Merchant::create([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $hashedPassword,
            'referred_by' => $request->referred_by,
            't_and_c' => (bool) $request->t_and_c,
            'otp' => $otp,
        ]);

        // Send OTP to user's email
        $this->sendOtpEmail($newMerchant->email, $otp);

        return response(['message' => 'OTP sent to your email for verification'], 200);
    }

    private function sendOtpEmail($email, $otp)
    {
        Mail::to($email)->send(new OtpMail($otp));
    }

    /**
     * Verify the merchant email.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $merchant = Merchant::where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('email_verified_at') // Ensure email is not already verified
            ->first();

        if ($merchant) {
            // Update user status to verified
            $merchant->update(['email_verified_at' => now()]);

            // Optionally, you can clear the stored OTP
            $merchant->update(['otp' => null]);

            // Generate a token for the verified merchant
            $token = $merchant->createToken('merchant-token')->plainTextToken;

            return response(['message' => 'Email verified successfully', 'data' => $merchant, 'token' => $token], 200);
        } else {
            return response(['message' => 'Invalid OTP or email'], 400);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    // Updating a merchant password
    public function reset_password(Request $request){
        //checking the necessary data was passwd through the body
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'old_password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $merchant = Merchant::where('email', $request->email)
            ->where('password', $request->old_password)
            ->first();

        if (!$merchant){
            return response(['message' => 'Merchant not found'], 400);
        }else{
            $newHashedPassword = Hash::make($request->new_password); //Hashing the new password before storage

            $merchant->password = $newHashedPassword; // updating the password stored in the database to the newly hashed password

            $merchant->save();

            return response(['message' => 'Merchant password has be changed successfully'], 200);
        }
    }

    //Merchant Forget password logic
    public function forgot_password(Request $request){
        //checking the necessary data was passwd through the body
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
        ]);

        if ($validator->fails()){
            return response(['message' => $validator->errors()->first()], 400);
        }

        $merchant = Merchant::where('email', $request->email)->first();

        if (!$merchant){
            return response(['message' => 'Merchant not found'], 400);
        }else{
            $token = $merchant->createToken('merchant-token')->plainTextToken;
            $url = route('/forgot-password',$token);
            $merchant->otp = $token; // saving the token to the db for verification purpose
            $merchant->save();
            Mail::to($request->email)->send(new ForgetPassword($url));
            return response(['message' => 'Merchant password link has been sent'], 200);
        }
    }
}
