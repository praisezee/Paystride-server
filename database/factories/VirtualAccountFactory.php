<?php

namespace Database\Factories;

use App\Models\VirtualAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VirtualAccount>
 */
class VirtualAccountFactory extends Factory
{
    protected $model = VirtualAccount::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_number' => $this->faker->unique()->bankAccountNumber,
            'bank_name' => $this->faker->word, // Adjust based on your needs
            'merchant_id' => function () {
                return \App\Models\Merchant::factory()->create()->id;
            },
            'payment_point_id' => function () {
                return \App\Models\PaymentPoint::factory()->create()->id;
            },
        ];
    }
}
