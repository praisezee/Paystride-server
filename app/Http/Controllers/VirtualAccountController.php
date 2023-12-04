<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\PaymentPoint;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function index(){
        $virtualAccounts = VirtualAccount::all();
        return view('virtual_accounts.index', compact('virtualAccounts'));
    }

    public function create(){
        //You can fetch the list of merchants and payment points if need be
        $merchants = Merchant::all();
        $paymentPoints = PaymentPoint::all();

        return view('virtual_accounts.create', compact('merchants', 'paymentPoints'));
    }

    public function store(Request $request){
        $validatedData = $request->validate(['account_number' => 'required|string',
        'bank_name' => 'required|string',
        'merchant_id' => 'required|exists:merchants,id',
        'payment_point_id' => 'required|exists:payment_points,id',
    ]);

    VirtualAccount::create($validatedData);

        return
        redirect()->route('virtual-accounts.index')->with('success', 'Virtual Account created successfully!');
    }

    //Add other methods like edit, update and destroy as needed

}
