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
        // Only add password column if table exists
        if (Schema::hasTable('umkms')) {
            Schema::table('umkms', function (Blueprint $table) {
                if (!Schema::hasColumn('umkms', 'password')) {
                    $table->string('password')->nullable()->after('email');
                }
                
                if (!Schema::hasColumn('umkms', 'foto_ktp')) {
                    $table->string('foto_ktp')->nullable()->after('no_ktp');
                }
                
                if (!Schema::hasColumn('umkms', 'foto_tempat')) {
                    $table->string('foto_tempat')->nullable()->after('foto_ktp');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            if (Schema::hasColumn('umkms', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};
