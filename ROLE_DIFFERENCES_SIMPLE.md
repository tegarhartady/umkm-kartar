# 📊 PERBEDAAN ROLE SUPER ADMIN, ADMIN, DAN UMKM

## 🎯 Quick Summary

| Fitur | Super Admin | Admin | UMKM |
|-------|-----------|-------|------|
| **Akses Dashboard** | `/superadmin/dashboard` | `/dashboard/admin` | `/dashboard/umkm` |
| **Kelola Admin** | ✅ CRUD | ❌ Tidak bisa | ❌ Tidak bisa |
| **Kelola UMKM** | ❌ Tidak | ✅ CRUD + Verifikasi | ❌ Hanya lihat milik sendiri |
| **Moderasi Produk** | ❌ Tidak | ✅ Approve/Reject | ❌ Tidak bisa |
| **Pengaturan Sistem** | ✅ Cache, Database | ❌ Tidak bisa | ❌ Tidak bisa |
| **Kelola Desa** | ❌ Tidak | ✅ CRUD | ❌ Tidak bisa |
| **Lihat Laporan** | ✅ Sistem keseluruhan | ✅ UMKM & Produk | ❌ Hanya punya sendiri |
| **Data Isolation** | Lihat SEMUA | Lihat SEMUA | Lihat HANYA milik sendiri |

---

## 🔐 SUPER ADMIN

**Role:** `superadmin`  
**Default Path:** `/superadmin/dashboard`  
**Protection:** `Route::middleware(['auth', 'role:superadmin'])`

### Tugas Utama:
1. **Manage Admin Users**
   - Create admin baru
   - Edit admin (nama, email, password)
   - Delete admin
   - View admin list

2. **System Settings**
   - Cache management
   - Database optimization
   - System configuration
   - Platform maintenance

### Menu di Sidebar:
```
SUPER ADMIN
├── Dashboard
├── Kelola Admin
│   ├── Daftar Admin
│   ├── Tambah Admin
│   └── Edit/Delete Admin
└── Pengaturan Sistem
```

### Controller: `SuperAdminController`
```php
- index()              // Dashboard
- manageAdmins()       // List admins
- createAdmin()        // Form create
- storeAdmin()         // Save admin
- editAdmin()          // Form edit
- updateAdmin()        // Update admin
- destroyAdmin()       // Delete admin
- settings()           // System settings
- updateSettings()     // Save settings
```

### Database Access:
- Bisa lihat: SEMUA users, SEMUA UMKM, SEMUA products
- Tidak bisa: Edit UMKM/Products langsung (hanya admin yang bisa)

---

## 🛡️ ADMIN

**Role:** `admin`  
**Default Path:** `/dashboard/admin`  
**Protection:** `Route::middleware(['auth', 'admin.superadmin'])`

### Tugas Utama:
1. **UMKM Management**
   - Create UMKM baru
   - View semua UMKM
   - Edit UMKM
   - Delete UMKM
   - Verifikasi UMKM pending
   - Approve/Reject UMKM

2. **Product Moderation**
   - Approve produk baru
   - Reject produk
   - View semua produk
   - Publish/Unpublish produk

3. **Desa/Kecamatan Management**
   - Create desa
   - Edit desa
   - Delete desa

4. **Laporan & Analytics**
   - Dashboard dengan stats lengkap
   - Total omzet semua UMKM
   - Jumlah UMKM terdaftar
   - Produk pending vs published
   - Data per desa

5. **Company Settings**
   - Logo, nama perusahaan
   - Deskripsi
   - Kontak, alamat
   - Social media links

### Menu di Sidebar:
```
MENU UTAMA
├── Dashboard
├── Kelola UMKM
│   ├── Daftar UMKM
│   ├── Verifikasi UMKM
│   └── Tambah UMKM
├── Produk
│   ├── Semua Produk
│   └── Moderasi Produk
├── Kelola Desa
├── Laporan
└── Pengaturan (Company Profile)
```

### Controller: `DashboardController@admin`
```php
public function admin()
{
    // Get statistics:
    - Total omzet dari semua UMKM
    - Jumlah UMKM terdaftar
    - Jumlah UMKM baru (7 hari terakhir)
    - Total produk published
    - Produk menunggu verifikasi
    - Breakdown per desa
    - UMKM pending approval
    - Produk terbaru
}
```

### Database Access:
- Bisa lihat: SEMUA UMKM, SEMUA products, SEMUA users
- Bisa edit: UMKM dan Product status
- Tidak bisa: Access `/superadmin/*`, kelola admin lain

---

## 👨‍💼 UMKM (Pemilik Toko)

**Role:** `umkm`  
**Default Path:** `/dashboard/umkm`  
**Protection:** `Route::middleware(['auth', 'role:umkm'])`

### Tugas Utama:
1. **Dashboard Pribadi**
   - Lihat statistik toko mereka saja
   - Total penjualan
   - Jumlah produk aktif vs pending
   - Rating toko
   - Total pesanan

2. **Kelola Produk**
   - Lihat produk mereka (5 terbaru)
   - Lihat produk dengan stok menipis
   - Alert untuk produk pending

### Menu di Sidebar:
```
MENU UTAMA
├── Dashboard UMKM
```

### Controller: `DashboardController@umkm`
```php
public function umkm()
{
    // Ambil UMKM user yang sedang login
    $user = auth()->user();
    $umkm = $user->umkms()->first();
    
    // Hitung statistik HANYA untuk UMKM mereka:
    - Total products (milik mereka)
    - Active products (milik mereka)
    - Pending products (milik mereka)
    - Total sales (dari produk mereka)
    - Shop rating
    - Total orders (milik mereka)
}
```

### Database Access:
- Bisa lihat: HANYA UMKM milik mereka + produk mereka saja
- Tidak bisa: Lihat UMKM lain, edit apapun, access admin panel
- Data isolation ketat: Hanya query dengan `WHERE user_id = auth()->id()`

---

## 🔄 ALUR LOGIN

```
User Login
    ↓
Check Credentials
    ↓
Set Session (role disimpan)
    ↓
┌─────────────────────────────────────┐
│                                     │
├─ Role: superadmin                  │
│  → /superadmin/dashboard           │
│                                     │
├─ Role: admin                        │
│  → /dashboard/admin                │
│                                     │
├─ Role: umkm                         │
│  → /dashboard/umkm                 │
│                                     │
└─────────────────────────────────────┘
```

---

## 🛡️ MIDDLEWARE PROTECTION

### Routes Configuration (`routes/web.php`):

```php
// Super Admin Only
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', ...);
    Route::get('/superadmin/admins', ...);
    // ... super admin routes
});

// Admin & Super Admin
Route::middleware(['auth', 'admin.superadmin'])->group(function () {
    Route::get('/dashboard/admin', ...);
    Route::get('/admin/umkm', ...);
    Route::get('/admin/products', ...);
    // ... admin routes
});

// UMKM Only
Route::middleware(['auth', 'role:umkm'])->get('/dashboard/umkm', ...);
```

### Middleware Classes:

**`app/Http/Middleware/RoleMiddleware.php`**
- Cek apakah user punya role yang diminta
- Jika tidak: abort(403)

**`app/Http/Middleware/AdminOrSuperAdminMiddleware.php`**
- Cek apakah user adalah admin atau superadmin
- Jika bukan: abort(403)

---

## 📊 STATISTIK DI DASHBOARD

### Admin Dashboard Menampilkan:
```
Total Omzet              // Sum of all UMKM.omzet_bulanan
UMKM Terdaftar           // Count all UMKM
UMKM Baru (7 hari)       // Count where created_at >= 7 days ago
Total Produk             // Count where status = 'published'
Menunggu Verifikasi      // Count where status = 'pending'
Peningkatan per Desa     // Grouped by desa (top 10)
UMKM Pending Approval    // List of UMKM with status = 'pending'
Produk Terbaru           // Latest 5 products
```

### UMKM Dashboard Menampilkan:
```
Total Penjualan          // Sum(harga * stok) untuk produk mereka
Produk Aktif             // Count own products where status = 'published'
Produk Pending           // Count own products where status = 'pending'
Total Pesanan            // Total orders (mock data)
Rating Toko              // Shop rating (mock data)
Produk Terbaru           // Latest 5 own products
Stok Menipis             // Own products where stok < 5
```

---

## ⚠️ PENTING: DATA ISOLATION

### Security Guarantee:

**Admin Tidak Bisa:**
```
❌ Access /superadmin/*
❌ Kelola admin lain
❌ Lihat system settings
❌ Clear cache / database
```

**UMKM Tidak Bisa:**
```
❌ Access /dashboard/admin
❌ Access /superadmin/*
❌ Lihat UMKM lain
❌ Lihat produk UMKM lain
❌ Kelola apapun (read-only dashboard)
```

### Database Queries:
```php
// Admin: Bisa lihat semua
$umkms = Umkm::all();
$products = Product::all();

// UMKM: Otomatis di-filter di controller
$umkms = auth()->user()->umkms()->get();
$products = $umkm->products()->get();
```

---

## 🧪 TEST USERS

Setelah seeding, gunakan credentials berikut untuk test:

```
Super Admin:
Email: superadmin@test.com
Password: password
→ Akses: /superadmin/dashboard

Admin:
Email: admin@test.com
Password: password
→ Akses: /dashboard/admin

UMKM User 1:
Email: umkm1@test.com
Password: password
→ Akses: /dashboard/umkm

UMKM User 2:
Email: umkm2@test.com
Password: password
→ Akses: /dashboard/umkm
```

---

## 📝 SETUP INSTRUCTIONS

### 1. Jalankan Migration:
```bash
php artisan migrate
```

### 2. Seed Data (Optional):
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=UmkmSeeder
php artisan db:seed --class=ProductSeeder
```

### 3. Test Setiap Role:
- Login dengan super admin → check `/superadmin/dashboard`
- Login dengan admin → check `/dashboard/admin`
- Login dengan UMKM → check `/dashboard/umkm`

### 4. Verifikasi Isolasi:
- UMKM hanya lihat produk sendiri ✓
- Admin lihat semua tapi tidak bisa akses super admin ✓
- Super admin akses semua termasuk manage admin ✓

---

## 🎯 KEY TAKEAWAYS

1. **Super Admin**: Mengelola SISTEM dan ADMIN users
2. **Admin**: Mengelola UMKM dan PRODUK
3. **UMKM**: Hanya lihat dashboard toko sendiri (read-only)

Perbedaan utama adalah:
- **Scope**: Super admin > Admin > UMKM
- **Data Access**: All > All > Own only
- **Features**: System > Business > Personal
