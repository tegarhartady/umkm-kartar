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
        if (Schema::hasTable('transactions') && !Schema::hasColumn('transactions', 'po_date')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->date('po_date')->nullable()->after('order_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transactions') && Schema::hasColumn('transactions', 'po_date')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn('po_date');
            });
        }
    }
};
