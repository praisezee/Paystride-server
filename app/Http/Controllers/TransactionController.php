<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $postData = [
            'amount' => $request->amount,
            'email' => $request->email,
            "currency" => $request->currency,
            "initiate_type" => $request->initiate_type,
            "transaction_ref" => $request->transaction_ref,
            "callback_url" => $request->callback_url
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.squad.api_key'),
            'Content-Type' => 'application/json', // Set the content type based on Squad's requirements
        ])->post(config('services.squad.api_base_url') . '/transaction/initiate', $postData);

        $data = $response->json();

        if ($response->successful()) {
            $responseData = $response->json();
            $transaction = new Transaction();
            $transaction->payment_point_id = 1;
            $transaction->virtual_account_id = 1;
            $transaction->transaction_ref = $postData['transaction_ref'];
            $transaction->amount = $postData['amount'];
            $transaction->transaction_description = 'Payment initiated';
            $transaction->transaction_type = $postData['initiate_type'];
            $transaction->status = 'initiated'; 
            $transaction->save();
        } else {
            $errorData = $response->json();
            // Handle errors
        }
        return $data;
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
