<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $users = Staff::where('merchant_id',intval($id))->get();

        return response(['users', $users], 200);
    }

    public function create_staff(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:staffs,email',
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string',
            'merchant_id' => 'required|integer',
        ]);

        if ($validator->fails()) return response(['message' => $validator->errors()->first()], 400);

        $hashedPassword = Hash::make($request->password);

        Staff::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => $hashedPassword,
            'merchant_id' => $request->merchant_id
        ]);

        return response(['message' => 'Staff was created successfully'],201);

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

        if($staff) return response(['message' => 'Staff not found'], 400);

        DB::transaction(function () use ($staff, $request){
            $staff->role = $request->role;
            $staff->save();
        });

        return response(['message'=> 'Staff role has been updated'], 202);
    }

}
