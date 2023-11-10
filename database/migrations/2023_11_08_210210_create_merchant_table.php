<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_name')->nullable(false);
            $table->string('email')->unique()->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->string('password')->nullable(false);
            $table->string('referred_by')->nullable();
            $table->boolean('t_and_c')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
