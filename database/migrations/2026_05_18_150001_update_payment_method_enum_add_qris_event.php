<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change payment_method to VARCHAR so we don't have to deal with ENUM limitations
        // ENUMs are annoying to update.
        // Or we can just run ALTER TABLE again to include qris_event
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('transfer', 'ewallet', 'cod', 'qris', 'midtrans', 'qris_event')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('transfer', 'ewallet', 'cod', 'qris', 'midtrans')");
    }
};
