<?php

namespace App\Http\Controllers;

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
}
