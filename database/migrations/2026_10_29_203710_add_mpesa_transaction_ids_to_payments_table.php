<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('merchant_request_id')->nullable();
            $table->string('checkout_request_id')->nullable()->index();
            $table->string('mpesa_result_code')->nullable();
            $table->text('mpesa_result_description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['checkout_request_id']);
            $table->dropColumn([
                'merchant_request_id',
                'checkout_request_id',
                'mpesa_result_code',
                'mpesa_result_description',
            ]);
        });
    }
};