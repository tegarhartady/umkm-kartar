<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jika column password belum ada, tambahkan
        if (Schema::hasTable('umkms') && !Schema::hasColumn('umkms', 'password')) {
            Schema::table('umkms', function (Blueprint $table) {
                $table->string('password')->nullable()->after('kategori');
            });
        }

        // Jika column remember_token belum ada, tambahkan
        if (Schema::hasTable('umkms') && !Schema::hasColumn('umkms', 'remember_token')) {
            Schema::table('umkms', function (Blueprint $table) {
                $table->rememberToken()->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('umkms')) {
            Schema::table('umkms', function (Blueprint $table) {
                if (Schema::hasColumn('umkms', 'password')) {
                    $table->dropColumn('password');
                }
                if (Schema::hasColumn('umkms', 'remember_token')) {
                    $table->dropColumn('remember_token');
                }
            });
        }
    }
};
