<?php

namespace Database\Seeders;

use App\Models\VirtualAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VirtualAccount::factory()->count(10)->create();
    }
}
