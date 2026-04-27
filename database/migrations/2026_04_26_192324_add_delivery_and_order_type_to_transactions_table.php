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
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('order_type', ['instant', 'po'])->default('instant')->after('total_price');
            $table->enum('delivery_type', ['takeaway', 'delivery'])->default('takeaway')->after('order_type');
            $table->decimal('delivery_fee', 15, 2)->default(0)->after('delivery_type');
            $table->decimal('grand_total', 15, 2)->nullable()->after('delivery_fee');
            
            // Re-define payment_method to include qris
            $table->string('payment_method')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'delivery_type', 'delivery_fee', 'grand_total']);
        });
    }
};
