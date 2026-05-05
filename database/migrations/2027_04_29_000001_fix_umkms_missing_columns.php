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
        Schema::table('umkms', function (Blueprint $table) {
            if (!Schema::hasColumn('umkms', 'omzet_bulanan')) {
                $table->decimal('omzet_bulanan', 15, 2)->nullable()->after('status');
            }
            if (!Schema::hasColumn('umkms', 'no_rekening')) {
                $table->string('no_rekening')->nullable()->after('omzet_bulanan');
            }
            if (!Schema::hasColumn('umkms', 'tipe_rekening')) {
                $table->string('tipe_rekening')->nullable()->after('no_rekening');
            }
            if (!Schema::hasColumn('umkms', 'nama_pemilik_rekening')) {
                $table->string('nama_pemilik_rekening')->nullable()->after('tipe_rekening');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn(['omzet_bulanan', 'no_rekening', 'tipe_rekening', 'nama_pemilik_rekening']);
        });
    }
};
