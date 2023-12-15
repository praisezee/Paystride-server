<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('support_requests')->insert([
            'first_name' => 'Josh',
            'last_name' => 'Smith',
            'email' => 'josh.smith@outlook.com',
            'topic' => 'Technical Issues',
            'message' => 'unable to login with an error message, saying "wrong details"',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
