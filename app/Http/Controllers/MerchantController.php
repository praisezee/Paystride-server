<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
