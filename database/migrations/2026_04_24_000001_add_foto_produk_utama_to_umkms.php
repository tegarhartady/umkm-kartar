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
            if (!Schema::hasColumn('umkms', 'foto_tempat')) {
                $table->string('foto_tempat')->nullable()->after('foto_ktp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkms', function (Blueprint $table) {
            if (Schema::hasColumn('umkms', 'foto_tempat')) {
                $table->dropColumn('foto_tempat');
            }
        });
    }
};
