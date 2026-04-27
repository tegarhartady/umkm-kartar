## 🎯 UMKM Authentication & Product Management System

Dokumentasi lengkap untuk mengintegrasikan sistem login UMKM dan manajemen produk.

---

## 📋 Alur Lengkap

```
1. UMKM Mendaftar
   └─> /daftar-umkm (Form Publik)
   └─> UmkmController@registerFromPublic
   └─> Status: "pending" ⏳

2. Admin Verifikasi
   └─> /dashboard/admin/umkm/verifikasi
   └─> Klik "Setujui"
   └─> UmkmController@approve
   └─> Status: "disetujui" ✅
   └─> Password otomatis: umkm + 4 digit random (contoh: umkm1234)

3. UMKM Login
   └─> /umkm/login
   └─> Input email + password dari admin
   └─> UmkmAuthController@authenticate
   └─> ✅ Login berhasil → Redirect ke dashboard UMKM

4. UMKM Kelola Produk
   └─> /umkm/dashboard
   └─> Tambah/Edit/Hapus produk
   └─> Produk dengan status "pending" perlu di-approve admin
   └─> Produk dengan status "aktif" sudah bisa dilihat publik
```

---

## 🔧 File-file yang Dibuat/Diupdate

### Controllers
```
✅ app/Http/Controllers/Auth/UmkmAuthController.php        (BARU)
✅ app/Http/Controllers/UmkmController.php                 (UPDATE)
✅ app/Http/Controllers/ProductController.php              (UPDATE)
```

### Models
```
✅ app/Models/Umkm.php                                     (UPDATE)
✅ app/Models/Product.php                                  (BARU)
```

### Views
```
✅ resources/views/umkm/login.blade.php                    (BARU)
✅ resources/views/umkm/dashboard.blade.php                (BARU)
✅ resources/views/umkm/products/index.blade.php           (BARU)
✅ resources/views/umkm/products/create.blade.php          (BARU)
✅ resources/views/umkm/products/edit.blade.php            (BARU)
```

### Routes
```
✅ routes/web.php                                          (UPDATE)
```

### Config
```
✅ config/auth.php                                         (UPDATE)
```

### Migrations
```
✅ database/migrations/2024_01_01_000000_add_password_to_umkms.php (BARU)
```

### Policies & Middleware
```
✅ app/Policies/ProductPolicy.php                          (BARU)
✅ app/Http/Middleware/UmkmAuthenticated.php              (BARU)
```

---

## 🚀 Setup Instructions

### 1️⃣ Run Migration
```bash
php artisan migrate
```

**Apa yang dilakukan:**
- Menambahkan kolom `password` ke tabel `umkms` (jika belum ada)

---

### 2️⃣ Register AuthServiceProvider

Buka `app/Providers/AuthServiceProvider.php` dan pastikan ada:

```php
use App\Policies\ProductPolicy;
use App\Models\Product;

public function boot(): void
{
    $this->registerPolicies();
    
    Gate::policy(Product::class, ProductPolicy::class);
}
```

---

### 3️⃣ Clear Cache

```bash
php artisan config:cache
php artisan route:clear
php artisan view:clear
```

---

## 🔐 Route Mapping

| Route | Method | Controller | Deskripsi |
|-------|--------|-----------|-----------|
| `/umkm/login` | GET | UmkmAuthController@showLoginForm | Tampil form login UMKM |
| `/umkm/login` | POST | UmkmAuthController@authenticate | Process login UMKM |
| `/umkm/logout` | POST | UmkmAuthController@logout | Logout UMKM |
| `/umkm/dashboard` | GET | Dashboard view | Dashboard UMKM |
| `/umkm/products` | GET | ProductController@index | List produk UMKM |
| `/umkm/products/create` | GET | ProductController@create | Form tambah produk |
| `/umkm/products` | POST | ProductController@store | Simpan produk baru |
| `/umkm/products/{id}/edit` | GET | ProductController@edit | Form edit produk |
| `/umkm/products/{id}` | PUT | ProductController@update | Update produk |
| `/umkm/products/{id}` | DELETE | ProductController@destroy | Hapus produk |

---

## 🔑 Authentication Guard

**Guard yang digunakan:** `umkm`

Konfigurasi ada di `config/auth.php`:

```php
'guards' => [
    'umkm' => [
        'driver' => 'session',
        'provider' => 'umkms',
    ],
],

'providers' => [
    'umkms' => [
        'driver' => 'eloquent',
        'model' => App\Models\Umkm::class,
    ],
],
```

---

## 📝 Cara Menggunakan

### Untuk Admin:

1. Buka `/dashboard/admin/umkm/verifikasi`
2. Lihat daftar UMKM dengan status "pending"
3. Klik tombol "✅ Setujui"
4. Lihat password default yang ditampilkan
5. Informasikan ke pemilik UMKM via WhatsApp/Email

### Untuk UMKM:

1. Login ke `/umkm/login`
2. Gunakan email + password yang diberikan admin
3. Klik "Masuk"
4. Akses dashboard → lihat overview produk
5. Klik "Tambah Produk Baru" untuk input produk
6. Isi form lengkap → klik "Tambah Produk"
7. Produk masuk status "pending" menunggu admin approve
8. Kelola produk di menu "Kelola Produk"

---

## 🗄️ Database Schema

### Tabel: umkms
```sql
id, nama_toko, pemilik, email, phone, desa, alamat, kategori, 
deskripsi, password, status, omzet_bulanan, foto_toko, 
no_ktp, lama_usaha, created_at, updated_at
```

**Status values:** `pending`, `disetujui`, `ditolak`

### Tabel: products
```sql
id, umkm_id (FK), nama_produk, deskripsi, harga, stok, satuan, 
kategori, foto, status, created_at, updated_at
```

**Status values:** `aktif`, `pending` (default)

---

## 🎨 Frontend Features

✅ Login UMKM dengan validasi status
✅ Dashboard dengan stats card
✅ Info toko terintegrasi
✅ Tabel produk responsif
✅ Form upload foto produk
✅ Edit & hapus produk
✅ Pagination untuk list produk
✅ Alert messages (success, error, warning)
✅ Password toggle visibility
✅ Mobile-friendly design

---

## 🔒 Security Features

✅ Hash password dengan bcrypt
✅ CSRF protection (semua form pakai @csrf)
✅ Authorization policy untuk product
✅ Guard separation (user vs umkm)
✅ Session management
✅ Validation di semua form
✅ File upload validation

---

## 🐛 Troubleshooting

### "Unauthorized" saat edit/hapus produk
```
✅ Pastikan ProductPolicy sudah registered di AuthServiceProvider
✅ Pastikan umkm_id di products table benar sesuai login UMKM
```

### Password tidak bisa di-hash saat approve
```
✅ Import: use Illuminate\Support\Facades\Hash;
✅ Di UmkmController.php
```

### Login berulang kali tidak berhasil
```
✅ Check: Auth::guard('umkm')->check()
✅ Debug di UmkmAuthController@authenticate
✅ Pastikan password di database sudah ter-hash
```

### Foto produk tidak tampil
```
✅ Pastikan: storage/app/public/products/ folder ada
✅ Run: php artisan storage:link
✅ Check: .env FILE_PATH setting
```

---

## 📞 Support

Untuk bantuan lebih lanjut, lihat dokumentasi:
- Laravel Authentication: https://laravel.com/docs/guards
- Policy & Authorization: https://laravel.com/docs/authorization
- Session Management: https://laravel.com/docs/session
