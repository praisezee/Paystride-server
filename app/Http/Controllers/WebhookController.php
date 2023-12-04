<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Validate the request signature
        $isValidSignature = $this->validateWebhookSignature($request);

        if (!$isValidSignature) {
            // Invalid signature, respond with an error
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 401);
        }
        // Parse the incoming JSON payload
        $payload = $request->json()->all();

        // Extract relevant transaction details from the payload
        $transactionDetails = [
            'amount' => $payload['amount'],
            'transaction_description' => $payload['transaction_description'],
            'transaction_type' => $payload['initiate_type'],
            'transaction_ref' => $payload['transaction_ref'],
            'status' => $payload['status'],
            'payment_point_id' => 1,
            'virtual_account_id' => 1,
        ];

        // Save transaction details to your local database
        Transaction::create($transactionDetails);

        // Respond with a success status
        return response()->json(['success' => true]);
    }

    private function validateWebhookSignature(Request $request)
    {
        // Define your Squad secret key
        $squadSecretKey = config('services.squad.api_key');

        // Verify the signature
        $payload = json_decode($request->getContent());
        $receivedSignature = $request->header('x-squad-signature');
        $expectedSignature = hash_hmac('sha512', json_encode($payload, JSON_UNESCAPED_SLASHES), $squadSecretKey);

        if ($receivedSignature !== $expectedSignature) {
            // Log the invalid signature for debugging
            Log::error('Invalid webhook signature', ['received' => $receivedSignature, 'expected' => $expectedSignature]);
            return false;
        }

        return true;
    }
}
