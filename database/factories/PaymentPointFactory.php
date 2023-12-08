<?php

namespace Database\Factories;

use App\Models\PaymentPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentPoint>
 */
class PaymentPointFactory extends Factory
{
    protected $model = PaymentPoint::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'merchant_id' => function () {
                return \App\Models\Merchant::factory()->create()->id;
            },
            'staff_id' => function () {
                return \App\Models\Staff::factory()->create()->id;
            },
            'status' => $this->faker->boolean,
            'token' => $this->faker->unique()->uuid,
        ];
    }
}
