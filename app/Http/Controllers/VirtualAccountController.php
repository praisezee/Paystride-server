<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\PaymentPoint;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    /**
     * Display a list of virtual accounts.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $virtualAccounts = VirtualAccount::all();
        return view('virtual_accounts.index', compact('virtualAccounts'));
    }

    /**
     * Show the form for creating a new virtual account.
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //You can fetch the list of merchants and payment points if need be
        $merchants = Merchant::all();
        $paymentPoints = PaymentPoint::all();

        return view('virtual_accounts.create', compact('merchants', 'paymentPoints'));
    }

    /**
     * Store a newly created virtual account in the database.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     */
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

    /**
     * Display the specified virtual account.
     * 
     * @param  \APP\Models\VirtualAccount $virtualAccount
     * @return \Illuminate\Http\Response
     */
    public function show(VirtualAccount $virtualAccount){
        return ('virtual_accounts.show', compact('virtualAccount'));
    }

    //Add other methods like edit, update and destroy as needed
    
}
