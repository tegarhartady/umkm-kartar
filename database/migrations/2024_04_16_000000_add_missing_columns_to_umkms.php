<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            if (!Schema::hasColumn('umkms', 'produk_utama')) {
                $table->string('produk_utama')->nullable();
            }
            if (!Schema::hasColumn('umkms', 'foto_ktp')) {
                $table->string('foto_ktp')->nullable();
            }
            if (!Schema::hasColumn('umkms', 'foto_tempat')) {
                $table->string('foto_tempat')->nullable();
            }
            if (!Schema::hasColumn('umkms', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Skip
    }
};