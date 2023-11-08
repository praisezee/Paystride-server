<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiKey;

class ApiKeyController extends Controller
{
    public function generate(Request $request)
    {
        $apiKey = ApiKey::generateKey();
        $apiKeyModel = ApiKey::create(['key' => $apiKey]);

        return response()->json(['api_key' => $apiKeyModel->key])
        ->header('Content-Type', 'application/json');
    }
}
