# Panduan Implementasi UMKM Registration & Product Management

## Fitur yang Telah Dibuat

### 1. **Autentikasi UMKM**
   - Halaman Register untuk UMKM baru
   - Halaman Login untuk UMKM
   - Password hashing dengan bcrypt
   - Session management

### 2. **Dashboard UMKM**
   - Informasi toko dan pemilik
   - Statistik produk (total, aktif, stok)
   - Quick access ke manajemen produk

### 3. **Manajemen Produk**
   - Tambah produk dengan foto
   - Edit produk
   - Hapus produk
   - Daftar produk dengan grid view
   - Upload foto dengan validasi

## File yang Telah Dibuat

### Controllers
- `/app/Http/Controllers/AuthController.php` - Authentication logic
- `/app/Http/Controllers/ProductController.php` - Product management

### Models
- `/app/Models/Umkm.php` - UMKM model dengan Authenticatable
- `/app/Models/Product.php` - Product model dengan relationship

### Views
- `/resources/views/auth/register.blade.php` - Register form
- `/resources/views/auth/login.blade.php` - Login form
- `/resources/views/umkm/dashboard.blade.php` - Dashboard
- `/resources/views/umkm/products/index.blade.php` - Product list
- `/resources/views/umkm/products/create.blade.php` - Create product form
- `/resources/views/umkm/products/edit.blade.php` - Edit product form

### Policies
- `/app/Policies/ProductPolicy.php` - Authorization untuk product

### Config Updates
- `config/auth.php` - Ditambahkan guard 'umkm' dan provider 'umkms'

## Steps untuk Setup

### 1. Import AuthController
```php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
```

### 2. Jalankan Migration
```bash
php artisan migrate
```

### 3. Setup Filesystem (untuk foto upload)
```bash
php artisan storage:link
```

### 4. Routes yang Tersedia

**Public Routes (Tanpa Login):**
- `GET /register` - Halaman register
- `POST /register` - Submit register
- `GET /login` - Halaman login
- `POST /login` - Submit login

**Protected Routes (Perlu Login):**
- `GET /dashboard` - Dashboard UMKM
- `GET /products` - Daftar produk
- `GET /products/create` - Form tambah produk
- `POST /products` - Submit tambah produk
- `GET /products/{id}/edit` - Form edit produk
- `PUT /products/{id}` - Submit edit produk
- `DELETE /products/{id}` - Hapus produk
- `POST /logout` - Logout

## Cara Menggunakan

### Register UMKM Baru
1. Kunjungi `/register`
2. Isi form dengan data UMKM
3. Tentukan password
4. Submit, akan otomatis login dan redirect ke dashboard

### Login UMKM
1. Kunjungi `/login`
2. Masukkan email dan password
3. Submit untuk login

### Manajemen Produk
1. Di dashboard, klik "Manajemen Produk" atau "+ Tambah Produk"
2. Isi form produk (nama, deskripsi, harga, stok, dll)
3. Upload foto produk (optional)
4. Submit untuk menambah
5. Dari daftar produk, bisa edit atau hapus produk

## Validasi yang Diterapkan

### Register
- Nama toko: required
- Email: required, unique
- Phone: required
- Desa: required
- Kategori: required
- Password: min 6 karakter, confirmation

### Produk
- Nama produk: required
- Deskripsi: required
- Harga: required, numeric, min 0
- Kategori: required
- Stok: required, integer, min 0
- Satuan: required
- Foto: optional, image, max 2MB

## Security

- Password di-hash menggunakan bcrypt
- Authorization check untuk product (UMKM hanya bisa edit/delete produknya sendiri)
- CSRF protection di semua form
- Session based authentication

## Next Steps (Optional)

1. Tambahkan middleware untuk check status 'approved' pada akses tertentu
2. Tambahkan notifikasi email saat register
3. Tambahkan export produk ke CSV/Excel
4. Tambahkan pagination untuk daftar produk
5. Tambahkan fitur kategori produk dinamis
