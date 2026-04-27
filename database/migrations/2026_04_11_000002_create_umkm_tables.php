<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('umkms')) {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');
            $table->string('pemilik');
            $table->string('no_ktp')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('desa');
            $table->string('alamat');
            $table->decimal('latitude', 10, 8)->nullable(); // Koordinat lokasi
            $table->decimal('longitude', 11, 8)->nullable(); // Koordinat lokasi
            $table->string('kategori');
            $table->string('lama_usaha')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('produk_utama')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_tempat')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken()->nullable();
            $table->string('status')->default('pending'); // pending, disetujui, ditolak
            $table->decimal('omzet_bulanan', 15, 2)->nullable();
            $table->string('foto_toko')->nullable();
            $table->timestamps();
        });
    }
    
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('umkm_id')->constrained()->onDelete('cascade');
                $table->string('nama_produk');
                $table->text('deskripsi');
                $table->string('metode_pemesanan')->default('siap_jadi'); // siap_jadi, po, keduanya
                $table->decimal('harga', 10, 2);
                $table->integer('stok')->default(0);
                $table->string('satuan'); // kg, pcs, dll
                $table->string('foto_produk')->nullable();
                $table->string('status')->default('aktif'); // aktif, nonaktif
                $table->string('kategori')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('desas')) {
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah_umkm')->default(0);
            $table->timestamps();
        });
    }

        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kategori');
                $table->text('deskripsi')->nullable();
                $table->string('icon')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('umkms', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('umkms', 'no_ktp')) {
                $table->string('no_ktp')->nullable()->after('pemilik');
            }
            if (!Schema::hasColumn('umkms', 'lama_usaha')) {
                $table->string('lama_usaha')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('umkms', 'status')) {
                $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('kategori');
            }
            if (!Schema::hasColumn('umkms', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('umkms');
        Schema::dropIfExists('desas');
        Schema::dropIfExists('categories');
    }
};
