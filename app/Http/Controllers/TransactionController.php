<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Retrieve all transactions with virtual account and payment point information
        $transactions = Transaction::with(['virtualAccount', 'paymentPoint.staff'])->get();

        // Add staff_phone_number and virtual_account_number to each transaction
        $transactions->transform(function ($transaction) {
            $transaction->staff_phone_number = optional($transaction->paymentPoint->staff)->phone_number;
            $transaction->virtual_account_number = optional($transaction->virtualAccount)->account_number;
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



    public function create()
    {
        // Show the form to create a new transaction
    }

    public function store(Request $request)
    {
        // Store a new transaction in the database
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['virtualAccount', 'paymentPoint.staff']);
    
        $response = [
            'success' => true,
            'statusCode' => 200,
            'message' => 'Transaction details fetched successfully',
            'data' => [
                'transaction' => $transaction->toArray(),
                'virtual_account' => optional($transaction->virtualAccount)->only(['id', 'account_number', 'bank_name']),
                'staff' => optional($transaction->paymentPoint->staff)->only(['id', 'name', 'role', 'email', 'phone_number']),
            ],
        ];
    
        return response()->json($response);
    }
    

    public function edit(Transaction $transaction)
    {
        // Show the form to edit a transaction
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Update the function in the database
    }

    public function destroy(Transaction $transaction)
    {
        // Restrict deletion of transactions abort(403, 'Deletion of transactions is not allowed.');
    }
}
