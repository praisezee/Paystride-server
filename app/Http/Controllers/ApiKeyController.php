<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApiKey;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function generateApiKey()
    {
        $apiKey = Str::random(32); // You can adjust the key length as needed

        ApiKey::create(['key' => $apiKey]);

        return response()->json(['api_key' => $apiKey]);
    }
}
