<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_point_id' => function () {
                return \App\Models\PaymentPoint::factory()->create()->id;
            },
            'virtual_account_id' => function () {
                return \App\Models\VirtualAccount::factory()->create()->id;
            },
            'transaction_description' => $this->faker->sentence,
            'transaction_type' => $this->faker->randomElement(['TypeA', 'TypeB', 'TypeC']),
            'transaction_ref' => $this->faker->uuid,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['Pending', 'Completed', 'Failed']),
        ];
    }
}
