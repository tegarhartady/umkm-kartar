# Fix Register UMKM Tidak Masuk Database

## Masalah
Data register UMKM tidak tersimpan ke database

## Solusi

### Step 1: Fresh Migration (Jika belum ada data penting)
Jika database masih kosong/baru, jalankan:
```bash
php artisan migrate:fresh
```

### Step 2: Jika sudah ada data sebelumnya
Jalankan migration baru:
```bash
php artisan migrate
```

### Step 3: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Step 4: Cek Database
Buka database dan verifikasi:
- Tabel `umkms` sudah terbuat
- Column `password` sudah ada
- Column `remember_token` sudah ada

### Step 5: Test Register
1. Buka `http://localhost:8000/register`
2. Isi form dengan data:
   ```
   Nama Toko: Toko Saya
   Pemilik: Nama Pemilik
   Email: test@example.com
   No. Telepon: 08123456789
   Desa: Desa Test
   Alamat: Jl. Test No.1
   Kategori: Makanan
   Password: password123
   Konfirmasi Password: password123
   ```
3. Submit form

### Step 6: Verifikasi Data Masuk
- Cek di database, apakah data sudah masuk ke tabel `umkms`
- Cek apakah redirect ke dashboard berhasil
- Cek di dashboard apakah nama toko muncul

## Jika Masih Error

### Debugging:
1. Check Laravel logs:
```bash
tail -f storage/logs/laravel.log
```

2. Check apakah routes sudah benar:
```bash
php artisan route:list | grep -i register
```

3. Cek apakah Model Umkm sudah di-update:
```bash
php artisan tinker
>>> App\Models\Umkm::all();
```

## File yang Sudah Diupdate

✅ `/database/migrations/2026_04_11_000002_create_umkm_tables.php` - Ditambah column password
✅ `/app/Models/Umkm.php` - Fillable sudah lengkap dengan password
✅ `/database/migrations/2026_04_11_000003_add_password_to_umkms.php` - Migration tambahan untuk safety
