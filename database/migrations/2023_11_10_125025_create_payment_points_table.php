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
            $table->foreignId('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreignId('staff_id')->references('id')->on('staff')->onDelete('cascade');
            $table->boolean('status')->default(true);
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
