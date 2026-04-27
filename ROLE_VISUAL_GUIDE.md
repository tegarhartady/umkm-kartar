# 🎯 SISTEM ROLE & DASHBOARD - VISUAL GUIDE

## 1️⃣ FLOW DIAGRAM LOGIN & REDIRECT

```
┌─────────────────┐
│  User Login     │
│  (email/pwd)    │
└────────┬────────┘
         │
         ▼
┌────────────────────┐
│ Auth Verification  │
│ (Check db)         │
└────────┬───────────┘
         │
    ┌────┴─────┐
    │ Failed?   │
    └────┬──────┘
         │
    ┌────┴─────────────┐
    │  No              │ Yes
    │                  │
    ▼                  ▼
Set role           Back with error
    │               (invalid creds)
    │
    ▼
┌──────────────────────────────────────┐
│       Check User Role                │
└────────────┬────────────┬────────────┘
             │            │            
        Super Admin    Admin         UMKM
             │            │            │
             ▼            ▼            ▼
    /superadmin/    /dashboard/    /dashboard/
    dashboard       admin          umkm
```

---

## 2️⃣ DATABASE ARCHITECTURE

```
USERS TABLE
┌──────────────────────────┐
│ id (PK)                  │
│ name                     │
│ email (UNIQUE)           │
│ password                 │
│ role (superadmin/admin/  │
│       umkm)              │
│ created_at, updated_at   │
└────────────┬─────────────┘
             │ 1
             │
             │ N
             ▼
UMKMS TABLE
┌──────────────────────────┐
│ id (PK)                  │
│ user_id (FK) ← RELATION  │ UMKM dimiliki oleh 1 User
│ nama_toko                │
│ pemilik                  │
│ email                    │
│ phone                    │
│ desa                     │
│ alamat                   │
│ kategori                 │
│ deskripsi                │
│ status (pending/active/  │
│         rejected)        │
│ omzet_bulanan            │
│ foto_toko                │
│ created_at, updated_at   │
└────────────┬─────────────┘
             │ 1
             │
             │ N
             ▼
PRODUCTS TABLE
┌──────────────────────────┐
│ id (PK)                  │
│ umkm_id (FK)             │ Produk dimiliki oleh 1 UMKM
│ nama_produk              │
│ kategori                 │
│ harga                    │
│ stok                     │
│ satuan                   │
│ deskripsi                │
│ image                    │
│ status (published/       │
│         pending)         │
│ created_at, updated_at   │
└──────────────────────────┘
```

### Relasi Summary:
```
User (1) ←→ (N) UMKM ←→ (N) Product

Contoh:
┌─────────────────────────────────────────┐
│ User: Budi                              │
│ Role: umkm                              │
├─────────────────────────────────────────┤
│ UMKM: Toko Mak Tini (user_id=1)         │
│   ├─ Product: Kerupuk (status=active)   │
│   ├─ Product: Terasi (status=pending)   │
│   └─ Product: Ikan Asin (status=active) │
└─────────────────────────────────────────┘
```

---

## 3️⃣ ROLE HIERARCHY & PERMISSIONS

```
                    ┌──────────────────┐
                    │  SUPER ADMIN     │ (Highest)
                    │  Role: superadmin│
                    └────────┬─────────┘
                             │
                    ┌────────▼──────────┐
                    │ Can Manage:       │
                    │ • Admin Users     │
                    │ • System Settings │
                    │ • View All Data   │
                    └───────────────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │    ADMIN         │ (Medium)
                    │  Role: admin     │
                    └────────┬─────────┘
                             │
                    ┌────────▼──────────┐
                    │ Can Manage:       │
                    │ • UMKM Data       │
                    │ • Products        │
                    │ • Desa/Kec.       │
                    │ • View All Data   │
                    └───────────────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │    UMKM USER     │ (Lowest)
                    │  Role: umkm      │
                    └────────┬─────────┘
                             │
                    ┌────────▼──────────┐
                    │ Can View:         │
                    │ • Own Dashboard   │
                    │ • Own Products    │
                    │ • Own Statistics  │
                    └───────────────────┘
```

---

## 4️⃣ DASHBOARD ROUTING MAP

```
                        ┌─ Login ──────────┐
                        │   /login          │
                        └────────┬──────────┘
                                 │
                    ┌────────────┴──────────────┐
                    │                           │
                    │ Check Role                │
                    │                           │
        ┌───────────┼────────────┬──────────────┴────────┐
        │           │            │                       │
        ▼           ▼            ▼                       ▼
   superadmin     admin        umkm              (Fail→Logout)
        │           │            │
        ▼           ▼            ▼
    /superadmin /dashboard   /dashboard
    /dashboard  /admin       /umkm
        │           │            │
    ┌───┴───┐   ┌───┴───┐    ┌──┴──┐
    │       │   │       │    │     │
    ▼       ▼   ▼       ▼    ▼     ▼
   Manage Admin UMKM  Products Stats Alerts
   Admins Mgmt  Mgmt   Moderasi

Toolbar:
Dashboard
  │
  ├─ Kelola Admin (SuperAdmin only)
  ├─ Kelola UMKM (Admin only)  
  ├─ Laporan (Admin only)
  ├─ Desa (Admin only)
  └─ Logout (All)
```

---

## 5️⃣ DATA VISIBILITY COMPARISON

```
┌────────────────┬─────────────────┬──────────────┬────────────────┐
│ Data Type      │  Super Admin    │    Admin     │     UMKM       │
├────────────────┼─────────────────┼──────────────┼────────────────┤
│ Users          │  View all       │  View all    │  Only self     │
│ UMKM           │  View all       │  View all    │  Own UMKM only │
│ Products       │  View all       │  View all    │  Own products  │
│ Orders         │  View all       │  View all    │  Own orders    │
├────────────────┼─────────────────┼──────────────┼────────────────┤
│ Can Edit:      │                 │              │                │
│ • UMKM         │  Yes            │  Yes         │  No            │
│ • Product      │  No (via Admin) │  Yes         │  No            │
│ • Admin User   │  Yes            │  No          │  No            │
│ • Settings     │  Yes (system)   │  Yes (co.)   │  No            │
├────────────────┼─────────────────┼──────────────┼────────────────┤
│ Access Level   │  ████████████   │  ████████    │  ████          │
│ (Visual)       │  (100%)         │  (70%)       │  (30%)         │
└────────────────┴─────────────────┴──────────────┴────────────────┘
```

---

## 6️⃣ MIDDLEWARE PROTECTION LAYERS

```
┌──────────────────────────────────────────────┐
│         Incoming Request                      │
└────────────┬─────────────────────────────────┘
             │
             ▼
┌──────────────────────────────────────────────┐
│  Layer 1: Authentication Middleware          │
│  - Check: Is user logged in?                 │
│  - If No: Redirect to /login                 │
└────────────┬─────────────────────────────────┘
             │ (auth()->check() = true)
             │
             ▼
┌──────────────────────────────────────────────┐
│  Layer 2: Role Middleware                    │
│  - Check: Does user have required role?      │
│  - admin.superadmin: role='admin'|'super...' │
│  - role:umkm: role='umkm'                    │
│  - If No: Return 403 Forbidden               │
└────────────┬─────────────────────────────────┘
             │ (auth()->user()->role valid)
             │
             ▼
┌──────────────────────────────────────────────┐
│  Layer 3: Authorization in Controller        │
│  - Check: Can user access this data?         │
│  - UMKM: Only own UMKM/Products              │
│  - If No: Return empty or error              │
└────────────┬─────────────────────────────────┘
             │
             ▼
┌──────────────────────────────────────────────┐
│  Return: View with authorized data           │
└──────────────────────────────────────────────┘
```

---

## 7️⃣ CONTROLLER QUERY FLOW

```
┌─ DashboardController::admin()
│
├─ $umkmCount = Umkm::count()
├─ $umkmBaru = Umkm::where('created_at', '>=', now()->subDays(7))->count()
├─ $produkCount = Product::where('status', 'published')->count()
├─ $totalOmzet = Umkm::sum('omzet_bulanan') / 1000000
├─ $peningkatanDesa = Umkm::groupBy('desa')->...
├─ $verifikasiPending = Umkm::where('status', 'pending')->...
└─ $produkTerbaru = Product::orderByDesc('created_at')->...

   → Returns: ALL data (Admin role)
   
┌─ DashboardController::umkm()
│
├─ $user = auth()->user() // Get logged in user
├─ $umkms = $user->umkms() // Get ONLY their UMKMs
├─ $umkm = $umkms->first() // Get first UMKM
├─ $totalProducts = $umkm->products()->count()
├─ $activeProducts = $umkm->products()->where('status', 'published')->count()
├─ $recentProducts = $umkm->products()->orderByDesc('created_at')->limit(5)
└─ $lowStockProducts = $umkm->products()->where('stok', '<', 5)

   → Returns: ONLY their data (UMKM role)
```

---

## 8️⃣ STATISTICS DISPLAYED

```
ADMIN DASHBOARD                    UMKM DASHBOARD
┌─────────────────────┐            ┌─────────────────────┐
│ Total Omzet: Rp450M │            │ Penjualan: Rp 5.2M  │
│ UMKM Terdaftar: 45  │            │ Produk Aktif: 18    │
│ UMKM Baru: +7       │            │ Produk Pending: 2   │
│ Total Produk: 234   │            │ Total Pesanan: 127  │
│ Menunggu: 12        │            │ Rating Toko: 4.8⭐  │
│ Per Desa: [list]    │            │ Recent Products: [] │
│ UMKM Pending: [list]│            │ Low Stock: [list]   │
│ Produk Terbaru:[list│            │ Status: Aktif ✓     │
└─────────────────────┘            └─────────────────────┘

Scope: GLOBAL                      Scope: PERSONAL
Data: ALL UMKM & Products          Data: OWN UMKM & Products only
```

---

## 9️⃣ SECURITY FLOW

```
SCENARIO 1: UMKM user tries to access /dashboard/admin
┌────────────────────────────────────────────────────┐
│ 1. Request: GET /dashboard/admin                   │
│ 2. Middleware: auth() check ✓                      │
│ 3. Middleware: role check [needs: admin/superadmin]│
│    User role: umkm ✗                               │
│ 4. Return: 403 Forbidden                           │
└────────────────────────────────────────────────────┘

SCENARIO 2: Admin tries to view UMKM#1 data
┌────────────────────────────────────────────────────┐
│ 1. Request: GET /admin/umkm/1                      │
│ 2. Middleware: auth() check ✓                      │
│ 3. Middleware: admin.superadmin check ✓            │
│ 4. Controller: Fetch Umkm::find(1)                 │
│ 5. Return: UMKM#1 data (Admin can see all)         │
└────────────────────────────────────────────────────┘

SCENARIO 3: UMKM#1 user tries to view UMKM#2 data
┌────────────────────────────────────────────────────┐
│ 1. Cannot access /dashboard/admin (blocked by MW)  │
│ 2. Can only access /dashboard/umkm                 │
│ 3. Controller: auth()->user()->umkms()             │
│ 4. Only returns UMKM#1 (their own UMKM)            │
│ 5. No way to access UMKM#2 data                    │
└────────────────────────────────────────────────────┘
```

---

## 🔟 IMPLEMENTATION CHECKLIST

```
PHASE 1: Database
☑ Migration created for user_id column
☑ Foreign key constraint added
☑ Relation defined in models

PHASE 2: Models
☑ User::umkms() relationship added
☑ Umkm::user() relationship added
☑ Fillable properties updated

PHASE 3: Controller
☑ admin() method returns ALL data
☑ umkm() method returns OWN data only
☑ Empty state handling for no UMKM

PHASE 4: Views
☑ dashboard_admin.blade.php - All data
☑ dashboard_umkm.blade.php - Personal data
☑ Error messages & alerts added

PHASE 5: Security
☑ Middleware protection on routes
☑ Data isolation in queries
☑ Authorization checks in controller

PHASE 6: Testing
☑ Test Super Admin access
☑ Test Admin access
☑ Test UMKM access
☑ Test data isolation
☑ Test error cases
```

---

## KEY RULES TO REMEMBER 🎯

```
1. Super Admin = System God
   ├─ Can manage EVERYTHING
   ├─ Includes managing admin users
   └─ Includes system settings

2. Admin = Business Manager
   ├─ Can manage UMKM & Products
   ├─ Can verify & moderate
   └─ Cannot access Super Admin features

3. UMKM = Shop Owner
   ├─ Can see ONLY own dashboard
   ├─ Read-only access currently
   └─ Cannot see other shops

4. Data Isolation = Security
   ├─ Each query filtered by ownership
   ├─ Middleware prevents unauthorized access
   └─ No way to "see" other data
```

---

This visual guide helps understand the role system at a glance!
