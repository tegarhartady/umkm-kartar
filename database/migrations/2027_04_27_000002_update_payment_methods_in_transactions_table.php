<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Using raw SQL because changing enum in SQLite/MySQL can be tricky via Blueprint
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('transfer', 'ewallet', 'cod', 'qris', 'midtrans')");
        } else {
            // For SQLite (testing/local), we usually just let it be string if we can't change enum easily
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('payment_method')->change();
            });
        }
    }

    public function down(): void
    {
        // No need to revert for now
    }
};
