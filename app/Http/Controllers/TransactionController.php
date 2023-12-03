<?php

namespace App\Http\Controllers;

use App\Models\PaymentPoint;
use App\Models\Staff;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{
    public function index()
{
    // Retrieve all transactions with virtual account information
    $transactions = Transaction::with('virtualAccount')->get();

    // Retrieve unique payment point IDs from transactions
    $paymentPointIds = $transactions->pluck('payment_point_id')->unique();

    // Retrieve staff information for the unique payment point IDs
    $staff = Staff::whereIn('id', $paymentPointIds)->get()->keyBy('id');

    // Add staff_phone_number to each transaction
    $transactions->transform(function ($transaction) use ($staff) {
        $transaction->staff_phone_number = $staff[$transaction->payment_point_id]->phone_number ?? null;
        return $transaction;
    });

    $response = [
        'success' => true,
        'statusCode' => 200,
        'message' => $transactions->isNotEmpty() ? 'Successfully fetched all transactions' : 'No transactions found',
        'data' => $transactions,
    ];

    return response()->json($response);
}


    public function create(){
        // Show the form to create a new transaction
    }

    public function store(Request $request){
        // Store a new transaction in the database
    }

    public function show(Transaction $transaction){
        // Show details of a specific transaction
    }

    public function edit(Transaction $transaction){
        // Show the form to edit a transaction
    }

    public function update(Request $request, Transaction $transaction){
        // Update the function in the database
    }

    public function destroy(Transaction $transaction){
        // Restrict deletion of transactions abort(403, 'Deletion of transactions is not allowed.');
    }
}
