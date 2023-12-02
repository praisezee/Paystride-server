<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantResource;
use App\Mail\ForgetPassword;
use App\Models\Merchant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Transform the data using the MerchantResource if needed
        $merchantResources = MerchantResource::collection(Merchant::all());

        $response = [
            'success' => true,
            'statusCode' => 200,
            'message' => count($merchantResources) > 0 ? 'Successfully fetched all merchants' : 'No merchants found',
            'data' => $merchantResources,
        ];

        return Response::json($response);
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

    private function sendOtpEmail($email,$otp)
    {
        $merchant = Merchant::where('email', $email)->first();

        if ($merchant) {
            $merchant->update(['otp' => $otp]);

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
            ->whereNull('email_verified_at') // Ensure email is not already verified
            ->first();

        if ($merchant) {
            // Update user status to verified
            $merchant->update([
                'email_verified_at' => now(),
                'otp' => null, // Optionally, you can clear the stored OTP
            ]);

            // Generate a token for the verified merchant
            $token = $merchant->createToken('merchant-token')->plainTextToken;
            // Transform the data using the MerchantResource
            $merchantResource = new MerchantResource($merchant);

            return response(['message' => 'Email verified successfully', 'data' => $merchantResource,], 200);
            return Response::json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Email verified successfully',
                'token' => $token,
                'data' => $merchantResource,
            ]);
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
            $this->sendOtpEmail($request->email,$otp);

            return response(['message' => 'OTP resent to your email for verification'], 200);
        } else {
            return response(['message' => 'Invalid email or email already verified'], 400);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $merchant = Merchant::find($id);
        if (!$merchant) {
            return response()->json([
                "status" => '',
                "message" => 'Not found',
            ], 404
        );
        }
        
        $merchantResource = new MerchantResource($merchant);
        



        $response = [
            'success' => true,
            'statusCode' => 200,
            'message' => 'Successfully fetched all merchant',
            'data' => $merchantResource,
        ];

        return Response::json($response);
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
    public function reset_password(Request $request)
    {
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
            ->first();

        if (!$merchant || !Hash::check($request->old_password, $merchant->password)) {
            return response(['message' => 'Invalid email or old password'], 400);
        }
        // Check if the new password is different from the current one
        if (Hash::check($request->new_password, $merchant->password)) {
            return response(['message' => 'New password must be different from the current password'], 400);
        }

        $newHashedPassword = Hash::make($request->new_password); //Hashing the new password before storage

        DB::transaction(function () use ($merchant, $newHashedPassword) {
            $merchant->password = $newHashedPassword;
            $merchant->save();
        });

        return response(['message' => 'Merchant password has been changed successfully'], 200);
    }

    //Merchant Forget password logic
    public function forgot_password(Request $request)
    {
        //checking the necessary data was passwd through the body
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first()], 400);
        }

        $merchant = Merchant::where('email', $request->email)->first();

        if (!$merchant) {
            return response(['message' => 'Merchant not found'], 400);
        } else {
            $token = Str::random(60);

            $merchant->otp = Hash::make($token); // saving the token to the db for verification purpose
            $merchant->save();

            $url = url('/forgot-password', ['token' => $token]);
            Mail::to($request->email)->send(new ForgetPassword($url));
            return response(['message' => 'Merchant password reset link has been sent'], 200);
        }
    }
}
