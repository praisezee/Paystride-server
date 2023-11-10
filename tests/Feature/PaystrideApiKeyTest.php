<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ApiKey;
use App\Http\Middleware\VerifyPaystrideApiKey;


class PaystrideApiKeyTest extends TestCase
{
    use RefreshDatabase; // Use database refreshing for testing

    public function test_fail_without_api_key(): void
    {
        $response = $this->postJson('/api/v1/just/an/example');

        $response->assertStatus(403);
    }

    public function test_fail_with_wrong_api_key(): void
    {
        $response = $this->postJson('/api/v1/just/an/example', [], [
            'X-API-Key' => 'a-wrong-key',
        ]);

        $response->assertStatus(403);
    }

    public function test_success_with_correct_api_key(): void
    {
        // Fetch an existing valid API key from the database
        $apiKeyModel = ApiKey::where('key', 'KAYTDMzdL8gsSloW0PN2OgLgBaIWoJfKXKCEA4YqXapCuGptXgbrbplgUwjW4ApP')->first(); // Replace with an actual valid API key

        // Ensure the API key exists in the database
        $this->assertNotNull($apiKeyModel);

        $response = $this->postJson('/api/v1/just/an/example', [], [
            'X-API-Key' => $apiKeyModel->key,
        ]);

        $response->assertStatus(200);
    }
}
