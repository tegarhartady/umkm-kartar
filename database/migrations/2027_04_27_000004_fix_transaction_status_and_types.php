<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add delivery_status if not exists
            if (!Schema::hasColumn('transactions', 'delivery_status')) {
                $table->string('delivery_status')->default('pending')->after('status');
            }
            
            // Change status to string to allow various statuses like 'paid', 'failed', etc.
            $table->string('status')->default('pending')->change();
            
            // Change enums to strings to avoid value mismatch errors
            $table->string('order_type')->default('langsung')->change();
            $table->string('delivery_type')->default('take_away')->change();
        });
    }

    public function down(): void
    {
        // No need to revert
    }
};
