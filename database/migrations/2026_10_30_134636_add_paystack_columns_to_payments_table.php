<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('paystack_reference')
                ->nullable()
                ->unique();

            $table->string('paystack_transaction_id')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique([
                'paystack_reference',
            ]);

            $table->dropColumn([
                'paystack_reference',
                'paystack_transaction_id',
            ]);
        });
    }
};