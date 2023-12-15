<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffResources;
use App\Mail\OtpMail;
use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show_staff(string $id)
    {
        //
        $merchant = Merchant::where('id', intval($id))
            ->first();

        if(!$merchant) return response(['message' => 'Merchant not found'], 400);

        $users = Staff::where('merchant_id',intval($id))->get()->map(function($staff){
            return new StaffResources($staff);
        });
        /* foreach($users as $staff){
            $staffResource = new StaffResources($staff);
            return $staffResource;
        }; */

        return response(['users'=> $users], 200);
    }

    public function create_staff(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:staff,email',
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string',
            'merchant_id' => 'required|integer',
        ]);

        if ($validator->fails()) return response(['message' => $validator->errors()->first()], 400);

        $hashedPassword = Hash::make($request->password);

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $newStaff = Staff::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $hashedPassword,
            'otp'=> $otp,
            'merchant_id' => $request->merchant_id
        ]);

        $this->setOtpEmail($newStaff->email,$otp);
        return response(['message' => 'Staff was created successfully'],201);

    }

    private function setOtpEmail($email,$otp){
        $staff = Staff::where('email',$email)->first();
        if ($staff) {
            $staff->update(['otp' => $otp]);
            Mail::to($email)->send(new OtpMail($otp));
        } else {
            // Log if staffs is not found
            info('Staff Not Found for Email: ' . $email);
        }
    }

    public function verifyEmail(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string',
            'otp'=>'required|numeric'
        ]);
        if ($validator->fails()) return response(['message' => $validator->errors()->first()], 400);

        $staff = Staff::where('email',$request->email)
        ->where('otp',$request->otp)
        ->first();

        if (!$staff) return response(['message' => 'Invalid OTP or email'], 400);

        DB::transaction(function () use ($staff){
            $staff->update([
                'is_verified' => true,
                'otp'=>null
            ]);
        });
        return response(['message' => 'Verification Successful'],200);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $staff = Staff::where('email', $request->email)
            ->where('isVerified',false)
            ->first();

        if ($staff) {
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
            $staff->update(['otp' => $otp]);

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

    public function update_role(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'merchant_id' => 'required|integer',
            'role' => 'required|string'
        ]);

        if ($validator->fails()) return response(['message' => $validator->errors()->first()], 400);

        $staff = Staff::where('id',intval($id))
            ->where('merchant_id',$request->merchant_id)
            ->first();

        if(!$staff) return response(['message' => 'Staff not found'], 400);

        DB::transaction(function () use ($staff, $request){
            $staff->role = $request->role;
            $staff->save();
        });

        return response(['message'=> 'Staff role has been updated'], 202);
    }

    public function deleteStaff ($id){
        $staff = Staff::where('id', $id)->first();
        if (!$staff) return response(['message'=>'Staff does not exist'],400);
        $staff->delete();
        return response(['message'=>'Staff has been deleted successfully'],202);
    }

    public function getSingleStaff ($id){
        $staff = Staff::where('id', $id)->first();
        if (!$staff) return response(['message'=>'Staff does not exist'],400);
        $staffResource = new StaffResources($staff);
        return response(['staff' => $staffResource],200);
    }
}
