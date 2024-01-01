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
        Schema::create('settlement_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->integer('account_number');
            $table->string('bank_name');
            $table->integer('bank_code')->nullable()->comment('Bank Code');
            $table->foreignId('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settlement_accounts');
    }
};
