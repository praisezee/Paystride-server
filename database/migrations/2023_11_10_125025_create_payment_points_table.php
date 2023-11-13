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
        Schema::create('payment_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number');
            $table->decimal('amount', 10, 2)->default(0); // Adjusted for currency values
            $table->foreignId('staff_id')->references('id')->on('staffs')->onDelete('cascade');
            $table->string('token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_points');
    }
};
