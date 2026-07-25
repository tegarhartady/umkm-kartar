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
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('transactions', 'payment_proof')) {
                    $table->string('payment_proof')->nullable()->after('snap_token');
                }
                // We will use string for status to allow more flexible statuses
                $table->string('status')->default('pending')->change();
                
                if (!Schema::hasColumn('transactions', 'delivery_status')) {
                    $table->string('delivery_status')->default('pending')->after('status');
                } else {
                    $table->string('delivery_status')->default('pending')->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('payment_proof');
            });
        }
    }
};
