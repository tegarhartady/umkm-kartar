## ✅ UMKM System Implementation Checklist

### 1. Database & Migrations
- [ ] Run: `php artisan migrate`
- [ ] Verify kolom `password` ada di tabel `umkms`
- [ ] Verify tabel `products` exists dengan kolom `umkm_id`

### 2. Configuration
- [ ] Update `config/auth.php` dengan guard 'umkm' ✅ (sudah di file ini)
- [ ] Update `app/Providers/AuthServiceProvider.php` untuk register ProductPolicy
- [ ] Run: `php artisan config:cache`

### 3. Controllers
- [ ] ✅ Create: `app/Http/Controllers/Auth/UmkmAuthController.php`
- [ ] ✅ Update: `app/Http/Controllers/UmkmController.php` (method approve)
- [ ] ✅ Create: `app/Http/Controllers/ProductController.php`

### 4. Models
- [ ] ✅ Update: `app/Models/Umkm.php` (add Authenticatable methods)
- [ ] ✅ Create: `app/Models/Product.php`

### 5. Routes
- [ ] ✅ Update: `routes/web.php` (add UMKM routes)

### 6. Views
- [ ] ✅ Create: `resources/views/umkm/login.blade.php`
- [ ] ✅ Create: `resources/views/umkm/dashboard.blade.php`
- [ ] ✅ Create: `resources/views/umkm/products/index.blade.php`
- [ ] ✅ Create: `resources/views/umkm/products/create.blade.php`
- [ ] ✅ Create: `resources/views/umkm/products/edit.blade.php`

### 7. Policies & Middleware
- [ ] ✅ Create: `app/Policies/ProductPolicy.php`
- [ ] ✅ Create: `app/Http/Middleware/UmkmAuthenticated.php`
- [ ] Register ProductPolicy di AuthServiceProvider

### 8. Storage
- [ ] Run: `php artisan storage:link` (untuk public file access)
- [ ] Verify: `public/storage` symlink exists
- [ ] Create: `storage/app/public/products/` folder

### 9. Testing
- [ ] Test: UMKM registration form
- [ ] Test: Admin approve UMKM (check password generated)
- [ ] Test: UMKM login dengan email + password
- [ ] Test: UMKM tambah produk
- [ ] Test: UMKM edit produk
- [ ] Test: UMKM hapus produk
- [ ] Test: UMKM logout

### 10. Final Steps
- [ ] Run: `php artisan route:clear`
- [ ] Run: `php artisan view:clear`
- [ ] Run: `php artisan cache:clear`
- [ ] Test login di browser

---

## 🎯 Quick Start Commands

```bash
# 1. Run migration
php artisan migrate

# 2. Clear caches
php artisan route:clear
php artisan view:clear
php artisan config:cache

# 3. Create storage symlink
php artisan storage:link

# 4. Start server (development)
php artisan serve

# 5. Test login UMKM
# Go to: http://localhost:8000/umkm/login
```

---

## 🔗 Important URLs

| URL | Tujuan | Role |
|-----|--------|------|
| `/daftar-umkm` | Form registrasi UMKM | Publik |
| `/umkm/login` | Form login UMKM | Publik |
| `/umkm/dashboard` | Dashboard UMKM | UMKM (auth:umkm) |
| `/umkm/products` | List produk UMKM | UMKM (auth:umkm) |
| `/dashboard/admin/umkm` | Manage UMKM | Admin |
| `/dashboard/admin/umkm/verifikasi` | Approve UMKM | Admin |

---

## 💾 Database Struktur

### Umkm Model
```php
protected $fillable = [
    'nama_toko', 'pemilik', 'email', 'phone', 'desa',
    'alamat', 'kategori', 'deskripsi', 'password',
    'status', 'omzet_bulanan', 'foto_toko', 'no_ktp', 'lama_usaha',
];

public function products() { ... }
```

### Product Model
```php
protected $fillable = [
    'umkm_id', 'nama_produk', 'deskripsi', 'harga',
    'stok', 'satuan', 'kategori', 'foto', 'status',
];

public function umkm() { ... }
```

---

## 📧 Password Generation

Default password format: `umkm` + random 4 digits

Contoh:
- umkm1234
- umkm5678
- umkm9999

**Lokasi code:** `app/Http/Controllers/UmkmController.php@approve()`

```php
$defaultPassword = 'umkm' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
```

---

## 🛡️ Authorization & Policies

ProductPolicy rules:
- ✅ UMKM hanya bisa edit produk miliknya sendiri
- ✅ UMKM hanya bisa hapus produk miliknya sendiri
- ✅ UMKM hanya bisa view produk miliknya sendiri

---

## 📌 Important Notes

1. **Password Reset:** Admin tidak perlu kirim email, password ditampilkan langsung
2. **Foto Produk:** Optional, tapi disarankan untuk meningkatkan penjualan
3. **Status Produk:** Default `pending`, berubah menjadi `aktif` setelah admin approve
4. **Session Timeout:** Gunakan setting `.env` SESSION_LIFETIME
5. **File Upload:** Pastikan `storage/app/public/` writable

---

## 🎓 File Reference

```
📁 app/
├── 📁 Http/
│   ├── 📁 Controllers/
│   │   ├── 📁 Auth/
│   │   │   └── UmkmAuthController.php ✅
│   │   ├── ProductController.php ✅
│   │   └── UmkmController.php ✅
│   └── 📁 Middleware/
│       └── UmkmAuthenticated.php ✅
├── 📁 Models/
│   ├── Umkm.php ✅
│   └── Product.php ✅
└── 📁 Policies/
    └── ProductPolicy.php ✅

📁 resources/views/
└── 📁 umkm/
    ├── login.blade.php ✅
    ├── dashboard.blade.php ✅
    └── 📁 products/
        ├── index.blade.php ✅
        ├── create.blade.php ✅
        └── edit.blade.php ✅

📁 config/
└── auth.php ✅

📁 routes/
└── web.php ✅

📁 database/migrations/
└── 2024_01_01_000000_add_password_to_umkms.php ✅
```

---

## ✨ Final Verification

```bash
# 1. Check routes
php artisan route:list | grep umkm

# 2. Check models
php artisan tinker
>>> App\Models\Umkm::first()

# 3. Check auth
Auth::guard('umkm')->check()

# 4. Test password hash
Hash::make('test123')
```

---

✅ **Semua file sudah dibuat dan dikonfigurasi!**

Tinggal run migration dan test sistem.
