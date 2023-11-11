<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Implement logic for retrieving a list of merchants
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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

        $newMerchant = Merchant::create([
            'name' => $request->name,
            'business_name' => $request->business_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $hashedPassword,
            'referred_by' => $request->referred_by,
            't_and_c' => (bool) $request->t_and_c,
            'email_verified_at' => null,
        ]);

        // Send OTP to user's email
        $this->sendOtpEmail($newMerchant->email);

        return response(['message' => 'OTP sent to your email for verification'], 200);
    }

    /**
     * Send OTP to the user's email.
     */
    private function sendOtpEmail($email)
    {
        $otp = rand(100000, 999999);
        $merchant = Merchant::where('email', $email)->first();

        if ($merchant) {
            // // Log if merchant is found
            // info('Merchant Found: ' . json_encode($merchant));

            // // Log the OTP value before update
            // info('Before OTP Update: ' . $merchant->otp);

            // Update the user's OTP
            $merchant->update(['otp' => $otp]);

            // // Log the OTP value after update
            // info('After OTP Update: ' . $merchant->otp);

            // Send OTP to user's email
            Mail::to($email)->send(new OtpMail($otp));
        } else {
            // Log if merchant is not found
            info('Merchant Not Found for Email: ' . $email);
        }
    }


    /**
     * Verify the merchant email.
     */
    public function verifyEmail(Request $request)
    {
        info('Verification Request Data: ' . json_encode($request->all()));
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $merchant = Merchant::where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('email_verified_at')
            ->first();

        if ($merchant) {
            // Update user status to verified
            $merchant->update([
                'email_verified_at' => now(),
                'otp' => null, // Optionally, you can clear the stored OTP
            ]);

            // Generate a token for the verified merchant
            $token = $merchant->createToken('merchant-token')->plainTextToken;

            // Use the MerchantResource to format the response
            return (new MerchantResource($merchant))->additional(['token' => $token, 'message' => 'Email verified successfully'], 200);
        } else {
            return response(['message' => 'Invalid OTP or email'], 400);
        }
    }

    /**
     * Resend OTP to the user's email.
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $merchant = Merchant::where('email', $request->email)
            ->whereNull('email_verified_at')
            ->first();

        if ($merchant) {
            // Check if there is a recent OTP resend attempt (within the last minute)
            $recentResendAttempt = DB::table('otp_resend_attempts')
                ->where('email', $request->email)
                ->where('created_at', '>', now()->subMinute(5))
                ->first();

            if ($recentResendAttempt) {
                // Resend attempt too soon, return an error
                return response(['message' => 'Too many resend attempts. Try again later.'], 429);
            }

            // Generate a new OTP
            $otp = rand(100000, 999999);

            // Update the user's OTP
            $merchant->update(['otp' => $otp]);

            // Record the resend attempt
            DB::table('otp_resend_attempts')->insert([
                'email' => $request->email,
                'created_at' => now(),
            ]);

            // Send OTP to user's email
            $this->sendOtpEmail($request->email);

            return response(['message' => 'OTP resent to your email for verification'], 200);
        } else {
            return response(['message' => 'Invalid email or email already verified'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Implement logic for retrieving a specific merchant
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Implement logic for editing a specific merchant
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Implement logic for updating a specific merchant
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implement logic for deleting a specific merchant
    }
}
