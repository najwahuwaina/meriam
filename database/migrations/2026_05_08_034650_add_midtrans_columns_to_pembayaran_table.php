<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->string('snap_token')->nullable();

            $table->string('transaction_id')->nullable();

            $table->string('payment_type')->nullable();

            $table->string('transaction_status')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {

            $table->dropColumn([
                'snap_token',
                'transaction_id',
                'payment_type',
                'transaction_status',
            ]);

        });
    }
};