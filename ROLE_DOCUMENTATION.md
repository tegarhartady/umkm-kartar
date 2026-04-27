# 📊 DOKUMENTASI SISTEM ROLE & DASHBOARD

## Ringkasan Perubahan

Dashboard UMKM telah diimplementasikan dengan fitur **real-time data** dari database. Sekarang ada 3 level user dengan dashboard berbeda:
- **Super Admin** - Mengelola seluruh sistem
- **Admin** - Mengelola UMKM dan produk
- **UMKM** - Mengelola toko dan produk mereka sendiri

---

## 🔐 PERBEDAAN ROLE YANG JELAS

### 1. SUPER ADMIN (Role: `superadmin`)
**Akses:** `/superadmin/*`

#### Wewenang:
- ✅ Mengelola semua Admin (Create, Edit, Delete)
- ✅ Pengaturan sistem aplikasi
- ✅ Melihat laporan keseluruhan sistem
- ✅ Manajemen database dan cache
- ✅ Kontrol penuh terhadap platform

#### Dashboard:
- **Route:** `/superadmin/dashboard`
- **View:** `resources/views/superadmin/dashboard.blade.php`
- **Menu:**
  - Kelola Admin (Create, Edit, Delete)
  - Pengaturan Sistem
  - Laporan Sistem

#### Fitur Khusus:
```
- User Management (hanya untuk admin)
- System Settings (cache, config optimization)
- Audit log viewing
- Platform-wide analytics
```

#### Controller: `SuperAdminController@index`

---

### 2. ADMIN (Role: `admin`)
**Akses:** `/dashboard/admin` dan `/admin/*`

#### Wewenang:
- ✅ Mengelola semua UMKM yang terdaftar
- ✅ Moderasi produk (publish/reject)
- ✅ Verifikasi UMKM baru
- ✅ Mengelola desa/kecamatan
- ✅ Melihat laporan UMKM
- ✅ Pengaturan konten company
- ✅ **TIDAK** bisa mengelola admin lain
- ✅ **TIDAK** bisa akses `/superadmin/*`

#### Dashboard:
- **Route:** `/dashboard/admin`
- **View:** `resources/views/pages/dashboard_admin.blade.php`
- **Menu:**
  - Dashboard Overview
  - Kelola UMKM (Daftar, Verifikasi, Create/Edit/Delete)
  - Produk (Daftar, Moderasi)
  - Kelola Desa
  - Laporan
  - Pengaturan (Company Profile)

#### Fitur Khusus:
```
- Real-time UMKM statistics
- Product moderation workflow
- UMKM verification pipeline
- Regional data management
- Company branding settings
- Product approval dashboard
```

#### Statistics yang Ditampilkan:
```php
- Total Omzet: Sum of all UMKM's monthly turnover
- UMKM Terdaftar: Count of all UMKM
- UMKM Baru (7 hari): Count of new UMKM registrations
- Total Produk: Count of published products
- Produk Menunggu: Count of pending products
- Data per Desa: Breakdown by village
- UMKM Pending Verification: List of pending verifications
- Produk Terbaru: Latest 5 products
```

#### Controller: `DashboardController@admin`

---

### 3. UMKM (Role: `umkm`)
**Akses:** `/dashboard/umkm` SAJA

#### Wewenang:
- ✅ Melihat dashboard toko mereka sendiri
- ✅ Melihat produk mereka saja
- ✅ **TIDAK** bisa akses dashboard admin
- ✅ **TIDAK** bisa akses `/superadmin/*`
- ✅ **TIDAK** bisa melihat data UMKM lain
- ✅ **TIDAK** bisa mengelola UMKM lain

#### Dashboard:
- **Route:** `/dashboard/umkm`
- **View:** `resources/views/pages/dashboard_umkm.blade.php`
- **Akses:** `Route::middleware(['auth', 'role:umkm'])`

#### Fitur:
```
- Dashboard pribadi toko
- Statistik penjualan toko
- Daftar produk toko (5 terbaru)
- Produk dengan stok menipis
- Status verifikasi toko
- Alert untuk produk menunggu verifikasi
```

#### Statistics yang Ditampilkan:
```php
// Data yang ditampilkan adalah HANYA milik UMKM user tersebut
- Total Penjualan: Revenue dari produk mereka
- Total Produk Aktif: Count produk dengan status 'published'
- Total Produk Pending: Count produk dengan status 'pending'
- Total Pesanan: Total orders for their products
- Rating Toko: Shop rating
- Produk Terbaru: 5 produk terbaru mereka
- Stok Menipis: Produk dengan stok < 5
- Status Verifikasi: Status UMKM (pending/active/rejected)
```

#### Controller: `DashboardController@umkm`

---

## 🔄 ALUR MIDDLEWARE PROTECTION

### Authentication Flow:
```
1. User Login
   ↓
2. DashboardController -> redirect based on role
   ↓
3. Admin/Super Admin → /dashboard/admin
   UMKM → /dashboard/umkm
```

### Route Protection:
```php
// Admin & Super Admin routes
Route::middleware(['auth', 'admin.superadmin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
    // ... other admin routes
});

// Super Admin only
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index']);
    // ... other superadmin routes
});

// UMKM only
Route::middleware(['auth', 'role:umkm'])->get('/dashboard/umkm', 
    [DashboardController::class, 'umkm']
);
```

### Middleware Classes:
```
app/Http/Middleware/
├── RoleMiddleware.php              // General role checking
└── AdminOrSuperAdminMiddleware.php // Admin + Super Admin
```

---

## 📊 DATABASE RELATIONS

### User Model
```php
public function umkms()
{
    return $this->hasMany(Umkm::class);
}
```

### Umkm Model
```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function products()
{
    return $this->hasMany(Product::class);
}
```

### Migration Added:
```sql
ALTER TABLE umkms ADD COLUMN user_id BIGINT UNSIGNED AFTER id;
ALTER TABLE umkms ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
```

---

## 🎯 FITUR PER ROLE

### SUPER ADMIN Features:
```
✅ Admin Management (CRUD)
✅ System Settings
✅ System Audit & Logs
✅ Database Optimization
✅ Cache Management
✅ Platform-wide Reports
❌ Cannot edit UMKM directly
❌ Cannot edit Products directly
❌ Cannot see individual shop dashboards
```

### ADMIN Features:
```
✅ UMKM Management (CRUD)
✅ UMKM Verification
✅ Product Moderation
✅ Desa/Kecamatan Management
✅ Company Settings
✅ View all shop data
✅ View all product data
✅ Export Reports
❌ Cannot manage other admins
❌ Cannot access system settings
❌ Cannot access superadmin panel
```

### UMKM Features:
```
✅ View own shop dashboard
✅ See own products only
✅ See own statistics
✅ See own pending products
✅ Alerts for stock warnings
✅ View shop verification status
❌ Cannot see other shops
❌ Cannot manage other products
❌ Cannot access admin panel
❌ Cannot verify their own UMKM
```

---

## 🗄️ DATA ISOLATION

### Admin View:
```
- UMKM.all()
- Product.all()
- Umkm.where('status', 'pending')
- See statistics for all UMKM
```

### UMKM View:
```
- UMKM.where('user_id', auth()->id())
- Product.whereHas('umkm', fn($q) => $q->where('user_id', auth()->id()))
- See statistics ONLY for their own UMKM
```

---

## 📈 DASHBOARD STATISTICS

### Admin Dashboard Stats:
```
Total Omzet (Juta Rupiah)
├─ Sum all UMKM.omzet_bulanan / 1,000,000
└─ Example: Rp 450.5M

UMKM Terdaftar
├─ Count of all UMKM
└─ Example: 45 UMKM

UMKM Baru (7 hari)
├─ Count where created_at >= now().subDays(7)
└─ Example: +7 UMKM

Total Produk
├─ Count Product where status = 'published'
└─ Example: 234 produk

Menunggu Verifikasi
├─ Count Product where status = 'pending'
└─ Example: 12 produk

Peningkatan per Desa
├─ Group by desa, count UMKM, sum omzet
└─ Shows top 10 villages by count
```

### UMKM Dashboard Stats:
```
Total Penjualan
├─ Sum of (produk.harga * produk.stok) for own products
└─ Example: Rp 5.2M

Produk Aktif
├─ Count own products where status = 'published'
└─ Example: 18 produk

Produk Pending
├─ Count own products where status = 'pending'
└─ Example: 2 produk pending

Total Pesanan (Mock)
├─ Placeholder: rand(50, 300)
└─ Will use Order model when implemented

Rating Toko (Mock)
├─ Placeholder: rand(4.0, 5.0)
└─ Will use Review model when implemented
```

---

## 🔗 ROUTES STRUCTURE

### Public Routes:
```
GET  /                           → Home
GET  /katalog                    → Product Catalog
GET  /login                      → Login Form
POST /login                      → Process Login
POST /logout                     → Logout
```

### Admin Routes (Protected with `admin.superadmin`):
```
GET  /dashboard/admin            → Admin Dashboard
GET  /admin/umkm                 → UMKM List
GET  /admin/umkm/create          → Create UMKM Form
POST /admin/umkm                 → Store UMKM
GET  /admin/umkm/{id}            → Show UMKM
GET  /admin/umkm/{id}/edit       → Edit UMKM Form
PUT  /admin/umkm/{id}            → Update UMKM
DELETE /admin/umkm/{id}          → Delete UMKM
GET  /admin/umkm-verifikasi      → Verification List
POST /admin/umkm/{id}/approve    → Approve UMKM
POST /admin/umkm/{id}/reject     → Reject UMKM
GET  /admin/products             → Products List
GET  /admin/products-moderasi    → Product Moderation
GET  /admin/desa                 → Desa List
GET  /admin/settings/company     → Company Settings
POST /admin/settings/company     → Update Settings
GET  /admin/laporan              → Reports
```

### Super Admin Routes (Protected with `role:superadmin`):
```
GET  /superadmin/dashboard       → Super Admin Dashboard
GET  /superadmin/admins          → Admins List
GET  /superadmin/admins/create   → Create Admin Form
POST /superadmin/admins          → Store Admin
GET  /superadmin/admins/{id}/edit → Edit Admin Form
PUT  /superadmin/admins/{id}     → Update Admin
DELETE /superadmin/admins/{id}   → Delete Admin
GET  /superadmin/settings        → System Settings
POST /superadmin/settings        → Update Settings
```

### UMKM Routes (Protected with `role:umkm`):
```
GET  /dashboard/umkm             → UMKM Dashboard
```

---

## 💡 IMPLEMENTATION DETAILS

### Database Migration:
File: `database/migrations/2026_04_13_000001_add_user_id_to_umkm_table.php`

```php
Schema::table('umkms', function (Blueprint $table) {
    $table->foreignId('user_id')
          ->nullable()
          ->after('id')
          ->constrained('users')
          ->onDelete('cascade');
});
```

### Controller Logic:
**DashboardController@umkm:**
```php
public function umkm()
{
    $user = auth()->user();
    $umkms = $user->umkms()->get();
    
    if ($umkms->isEmpty()) {
        return view('dashboard_umkm', [
            'hasUmkm' => false
        ]);
    }
    
    $umkm = $umkms->first();
    
    // Calculate stats for this UMKM only
    $totalProducts = $umkm->products()->count();
    $activeProducts = $umkm->products()
        ->where('status', 'published')->count();
    
    // ... more statistics
    
    return view('dashboard_umkm', [
        'data' => $data,
        'hasUmkm' => true
    ]);
}
```

---

## 🧪 TESTING CREDENTIALS

### For Testing:
```
Super Admin:
- Email: superadmin@test.com
- Password: password
- Redirect: /superadmin/dashboard

Admin:
- Email: admin@test.com
- Password: password
- Redirect: /dashboard/admin

UMKM User:
- Email: umkm@test.com
- Password: password
- Redirect: /dashboard/umkm
```

---

## ⚠️ PENTING: Data Isolation

**SECURITY NOTES:**

1. UMKM users dapat HANYA melihat data mereka sendiri
2. Admin dapat melihat ALL data tapi tidak bisa akses super admin features
3. Super Admin dapat melihat dan manage SEMUA, termasuk system settings
4. Middleware melakukan checking di setiap protected route
5. Query otomatis di-filter berdasarkan user role

---

## 📝 CHECKLIST IMPLEMENTASI

✅ User Model - Added `umkms()` relation
✅ Umkm Model - Added `user_id` column + relations
✅ DashboardController - Implemented `umkm()` method with real data
✅ Migration - Added `user_id` to umkms table
✅ dashboard_umkm.blade.php - Updated with real data binding
✅ Route Protection - Configured middleware
✅ Data Isolation - Implemented in controller queries
✅ UI/UX - Show "Belum Ada UMKM" if user has no UMKM

---

## 🚀 NEXT STEPS

1. **Run Migration:**
   ```bash
   php artisan migrate
   ```

2. **Seed Test Data:**
   ```bash
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=UmkmSeeder
   php artisan db:seed --class=ProductSeeder
   ```

3. **Test Each Role:**
   - Login as UMKM → `/dashboard/umkm`
   - Login as Admin → `/dashboard/admin`
   - Login as Super Admin → `/superadmin/dashboard`

4. **Verify Data Isolation:**
   - UMKM should only see own products
   - Admin should see all UMKM and products
   - Super Admin should see all + admin management

---

## 📞 SUPPORT

Untuk pertanyaan atau issue terkait:
- Dashboard: Lihat `resources/views/pages/dashboard_*.blade.php`
- Controller: Lihat `app/Http/Controllers/DashboardController.php`
- Routes: Lihat `routes/web.php`
- Models: Lihat `app/Models/`
