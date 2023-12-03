<?php

namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    protected $model = Staff::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'role' => $this->faker->randomElement(['Admin', 'Manager', 'Staff']),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // Change 'password' to the desired default password
            'phone_number' => $this->faker->phoneNumber,
            'merchant_id' => function () {
                return \App\Models\Merchant::factory()->create()->id;
            },
            'token' => null,
            'otp' => $this->faker->randomNumber(6),
            'isVerified' => $this->faker->boolean,
        ];
    }
}
