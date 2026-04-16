# 📋 UMKM System - Visual Flow & Architecture

## 🔄 User Flow Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                    UMKM SYSTEM - USER FLOW                              │
└─────────────────────────────────────────────────────────────────────────┘

┌──────────────┐           ┌────────────────┐          ┌─────────────────┐
│              │           │                │          │                 │
│   UMKM       │──────────▶│    REGISTER    │────────▶ │   Status:       │
│  (Publik)    │           │  /daftar-umkm  │          │   PENDING ⏳    │
│              │           │                │          │                 │
└──────────────┘           └────────────────┘          └─────────────────┘
                                                               ▲
                                                               │
                                                               │ (Admin Action)
                                                               ▼
                           ┌────────────────────────────────────────┐
                           │         ADMIN VERIFICATION             │
                           │  /dashboard/admin/umkm/verifikasi      │
                           │                                        │
                           │  Klik "✅ Setujui"                    │
                           │  ─────────────────────────────────    │
                           │  Generate: umkm + 4 digit             │
                           │  Password: umkm1234 (contoh)          │
                           │                                        │
                           │  Update Status: DISETUJUI ✅          │
                           │  Display Password ke Admin             │
                           └────────────────────────────────────────┘
                                       ▼
┌──────────────────────────────────────────────────────────────────────────┐
│                                                                          │
│  ADMIN INFORMASIKAN KE UMKM:                                           │
│  ─────────────────────────────────────────────────────────────────────  │
│  📧 Email: umkm@email.com                                              │
│  🔐 Password: umkm1234                                                 │
│  🌐 Login di: /umkm/login                                              │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
                                       ▼
┌──────────────────────────────────────────────────────────────────────────┐
│                         UMKM LOGIN PROCESS                              │
│                                                                          │
│         GET /umkm/login ──── Form Login                                │
│                                                                          │
│         POST /umkm/login ──── Validasi Email + Password               │
│                                │                                        │
│                   ┌────────────┴────────────┐                          │
│                   ▼                         ▼                           │
│            ❌ GAGAL                    ✅ BERHASIL                     │
│         (Error Message)          auth:umkm session                    │
│                                        │                               │
│                                        ▼                               │
│                              Redirect /umkm/dashboard                 │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
                                       ▼
┌──────────────────────────────────────────────────────────────────────────┐
│                   UMKM DASHBOARD & PRODUCT MANAGEMENT                   │
│                                                                          │
│  📊 GET /umkm/dashboard ──── Overview + Stats                          │
│                              - Total Produk                             │
│                              - Produk Aktif                             │
│                              - Pending Review                           │
│                              - List Produk                              │
│                                    │                                    │
│      ┌─────────────────────────────┼─────────────────────────────┐     │
│      ▼                             ▼                             ▼     │
│  Tambah Produk              Edit Produk                  Hapus Produk │
│  POST /umkm/products        PUT /umkm/products/{id}    DELETE /{id}   │
│  ─────────────────          ─────────────────────      ──────────────  │
│  - Nama                     - Update fields           - Confirm       │
│  - Deskripsi                - Upload foto baru        - Delete file    │
│  - Harga                    - Save changes            - Remove record  │
│  - Stok                                                                │
│  - Kategori                                                            │
│  - Upload Foto                                                         │
│  - Submit                                                              │
│      │                                                                 │
│      └─────────────────────────────┬─────────────────────────────────┘│
│                                    ▼                                    │
│              Produk masuk status: PENDING ⏳                           │
│              (Menunggu admin approve untuk jadi AKTIF)                │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ File Architecture

```
kartarumkm/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── 🆕 UmkmAuthController.php
│   │   │   │       ├── showLoginForm()
│   │   │   │       ├── authenticate()
│   │   │   │       └── logout()
│   │   │   ├── 📝 UmkmController.php (UPDATE)
│   │   │   │   └── approve() - Generate password
│   │   │   └── 🆕 ProductController.php
│   │   │       ├── index()
│   │   │       ├── create()
│   │   │       ├── store()
│   │   │       ├── edit()
│   │   │       ├── update()
│   │   │       └── destroy()
│   │   │
│   │   └── Middleware/
│   │       └── 🆕 UmkmAuthenticated.php
│   │
│   ├── Models/
│   │   ├── 📝 Umkm.php (UPDATE)
│   │   │   ├── extends Authenticatable
│   │   │   ├── hasMany products()
│   │   │   └── Auth methods
│   │   └── 🆕 Product.php
│   │       └── belongsTo umkm()
│   │
│   └── Policies/
│       └── 🆕 ProductPolicy.php
│           ├── view()
│           ├── update()
│           └── delete()
│
├── config/
│   └── 📝 auth.php (UPDATE)
│       ├── guards['umkm']
│       └── providers['umkms']
│
├── database/
│   ├── migrations/
│   │   └── 🆕 2024_01_01_000000_add_password_to_umkms.php
│   │
│   └── seeders/
│       └── 🆕 UmkmSeeder.php
│
├── resources/views/
│   └── umkm/
│       ├── 🆕 login.blade.php
│       ├── 🆕 dashboard.blade.php
│       └── products/
│           ├── 🆕 index.blade.php
│           ├── 🆕 create.blade.php
│           └── 🆕 edit.blade.php
│
├── routes/
│   └── 📝 web.php (UPDATE)
│       ├── GET /umkm/login (public)
│       ├── POST /umkm/login (public)
│       ├── POST /umkm/logout (protected)
│       ├── GET /umkm/dashboard (protected)
│       └── REST /umkm/products/* (protected)
│
└── Documentation/
    ├── 🆕 UMKM_SETUP.md
    ├── 🆕 IMPLEMENTATION_CHECKLIST.md
    ├── 🆕 SUMMARY.md
    ├── 🆕 ARCHITECTURE.md (this file)
    ├── 🆕 setup-umkm.sh (Linux/Mac)
    └── 🆕 setup-umkm.bat (Windows)

🆕 = File Baru
📝 = File yang diupdate
```

---

## 🔐 Authentication Flow (Detailed)

```
┌──────────────────────────────────────────────────────────────────┐
│                    AUTH GUARD: 'umkm'                            │
│                   (Separate dari 'web' guard)                    │
└──────────────────────────────────────────────────────────────────┘

                     UmkmAuthController
                            │
          ┌─────────────────┼─────────────────┐
          ▼                 ▼                 ▼
      showLoginForm()  authenticate()     logout()
      ──────────────   ───────────────    ───────
         GET Request   POST Request      POST Request
               │            │                 │
               ▼            ▼                 ▼
        Tampil Form    Validasi            Destroy
        /umkm/login   Email & Pass         Session
               │            │                 │
               │            ▼                 │
               │      Hash::check()           │
               │            │                 │
               │    ┌───────┴───────┐         │
               │    ▼               ▼         │
               │  ✅ Valid      ❌ Invalid    │
               │    │               │         │
               │    ▼               ▼         │
               │  Auth:login    Return Err  │
               │    │               │         │
               │    ▼               ▼         │
               │  Session       Back with    │
               │  Created        Errors     │
               │    │                        │
               │    ▼                        │
               │  Redirect              Redirect
               │  /umkm/               /umkm/login
               │  dashboard
               │
               └──────────────┬──────────────┘
                              │
                    Protected Routes
                    (middleware: auth:umkm)
```

---

## 🗃️ Database Relationships

```
┌─────────────────────────────────┐
│         UMKMS TABLE             │
├─────────────────────────────────┤
│ id (PK)                         │
│ nama_toko                       │
│ pemilik                         │
│ email                           │
│ phone                           │
│ desa                            │
│ alamat                          │
│ kategori                        │
│ deskripsi                       │
│ password (encrypted)            │
│ status (pending/disetujui/...) │
│ omzet_bulanan                   │
│ foto_toko                       │
│ no_ktp                          │
│ lama_usaha                      │
│ created_at                      │
│ updated_at                      │
└─────────────────────────────────┘
          │
          │ hasMany (1:N)
          │
          ▼
┌─────────────────────────────────┐
│      PRODUCTS TABLE             │
├─────────────────────────────────┤
│ id (PK)                         │
│ umkm_id (FK) ◄────┐            │
│ nama_produk       │            │
│ deskripsi         │ Foreign Key│
│ harga             │            │
│ stok              │            │
│ satuan            │            │
│ kategori          │            │
│ foto              │            │
│ status            │            │
│ created_at        │            │
│ updated_at        │            │
└─────────────────────────────────┘
        │
        └──► belongsTo Umkm
```

---

## 📊 Request/Response Flow

```
CLIENT REQUEST              ROUTING                  CONTROLLER
─────────────────────────────────────────────────────────────────

GET /umkm/login         ──────────────────────▶   showLoginForm()
                                                      │
                                                      ▼
                                                  return view()
                                                      │
                        ◀──────────────────────────────┘
                        HTML RESPONSE
                        login.blade.php


POST /umkm/login        ──────────────────────▶   authenticate()
(email, password)                                   │
                                                    ▼
                                              Hash::check()
                                                    │
                                       ┌────────────┴────────────┐
                                       ▼                         ▼
                                  ✅ Valid                  ❌ Invalid
                                       │                         │
                                       ▼                         ▼
                                 Auth::login()              return back()
                                       │                    withErrors()
                                       ▼                         │
                                 Session Create             ◀────────
                                       │
                                       ▼
                        ◀────────────────────────────
                        REDIRECT /umkm/dashboard


GET /umkm/dashboard     ──────────────────────▶   (in routes)
(auth:umkm middleware                              view return
checks session)                                        │
                                                       ▼
                                               dashboard.blade.php
                                                   - Stats
                                                   - Info Toko
                                                   - List Produk
                                                       │
                        ◀──────────────────────────────┘
                        HTML RESPONSE
```

---

## 🔄 State Transitions

```
UMKM Status Flow:

┌──────────┐     Admin     ┌───────────┐     Admin      ┌──────────┐
│ PENDING  │──Setujui──▶  │ DISETUJUI │◀──Tolak──     │ DITOLAK  │
└──────────┘              └───────────┘               └──────────┘
     ▲                          │
     │                          │ UMKM Login
     │                          ▼
     │                    ┌────────────┐
     │                    │  AKTIF USE │
     │                    │   - View   │
     │                    │  - Manage  │
     │                    │  - Produk  │
     │                    └────────────┘
     │
  Register
  from
  Public


Product Status Flow:

┌──────────┐    Auto     ┌──────────┐   Admin    ┌────────┐
│ PENDING  │──Default──▶ │  PENDING │──Approve─▶│ AKTIF  │
└──────────┘            └──────────┘           └────────┘
                              ▲
                              │
                        UMKM Create/Edit
```

---

## 🎯 Access Control Matrix

```
┌──────────────────┬────────────┬──────────────┬──────────────┐
│ Resource         │ Publik     │ UMKM Auth    │ Admin Auth   │
├──────────────────┼────────────┼──────────────┼──────────────┤
│ /umkm/login      │ ✅ View    │ ❌ Redirect  │ ❌ Redirect  │
│ /daftar-umkm     │ ✅ View    │ ❌ Redirect  │ ❌ Redirect  │
│ /umkm/dashboard  │ ❌ Deny    │ ✅ View      │ ❌ Deny      │
│ /umkm/products   │ ❌ Deny    │ ✅ CRUD*     │ ❌ Deny      │
│ /dashboard/admin │ ❌ Deny    │ ❌ Deny      │ ✅ View      │
└──────────────────┴────────────┴──────────────┴──────────────┘

* UMKM hanya bisa CRUD produk milik sendiri (via ProductPolicy)
```

---

## ✨ Key Implementation Points

### 1. Guard Separation
- `'web'` guard untuk User/Admin
- `'umkm'` guard untuk UMKM
- Tidak campur antara kedua

### 2. Password Generation
- Format: `umkm` + 4 digit random
- Hash dengan bcrypt
- Ditampilkan ke admin setelah approve

### 3. Authorization
- ProductPolicy untuk ownership check
- UMKM hanya bisa manage produk sendiri

### 4. Validation
- Email unique check
- Status verification
- File upload validation

### 5. Session Management
- Session created after login
- Session destroyed after logout
- Auto redirect if not authenticated

---

## 📚 Files Reference Quick Access

| Purpose | File |
|---------|------|
| Login Logic | `UmkmAuthController.php` |
| Approve Logic | `UmkmController.php@approve()` |
| CRUD Produk | `ProductController.php` |
| Guard Config | `config/auth.php` |
| Routes | `routes/web.php` |
| Auth Model | `Models/Umkm.php` |
| Product Model | `Models/Product.php` |
| Auth Policy | `Policies/ProductPolicy.php` |
| Login View | `views/umkm/login.blade.php` |
| Dashboard View | `views/umkm/dashboard.blade.php` |
| Product CRUD | `views/umkm/products/*.blade.php` |

---

✅ **Semua komponen sudah terintegrasi dengan baik!**
