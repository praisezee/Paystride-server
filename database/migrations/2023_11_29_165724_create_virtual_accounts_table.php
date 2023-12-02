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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_number')->unique();
            $table->string('bank_name');

            $table->unsignedBigInteger('merchant_id')->unique();
            $table->unsignedBigInteger('payment_point_id')->unique();

            $table->foreign('merchant_id')->references('id')->on('merchants');

            $table->foreign('payment_point_id')->references('id')->on('payment_points');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};
