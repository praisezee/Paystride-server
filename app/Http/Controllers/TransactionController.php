<?php

namespace App\Http\Controllers;

use App\Models\PaymentPoint;
use App\Models\Staff;
use App\Models\Transaction;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        // Show details of a specific transaction
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
