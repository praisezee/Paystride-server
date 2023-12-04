<?php

namespace Database\Seeders;

use App\Models\PaymentPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentPoint::factory()->count(10)->create();
    }
}
