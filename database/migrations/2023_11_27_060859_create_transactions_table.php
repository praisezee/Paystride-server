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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_type'); // invoice, payment, deposit, withdrawal
            $table->foreignId('payment_point_id')->references('id')->on('payment_points')->onDelete('cascade');
            $table->foreignId('virtual_account_id')->references('id')->on('virtual_accounts')->onDelete('cascade');
            $table->string('transaction_ref');
            $table->text('transaction_description');
            $table->decimal('amount', 10, 2);
            $table->dateTime('datetime');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
