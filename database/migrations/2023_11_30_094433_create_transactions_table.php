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
            
            $table->unsignedBigInteger('payment_point_id');

            $table->unsignedBigInteger('virtual_account_id')->nullable();

            $table->text('transaction_description');

            $table->string('transaction_type');
            $table->string('transaction_ref');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status');
            $table->timestamps();


            $table->foreign('payment_point_id')->references('id')->on('payment_points')->onDelete('restrict');

            $table->foreign('virtual_account_id')->references('id')->on('virtual_accounts')->onDelete('restrict');
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
