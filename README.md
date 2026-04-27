# 🎯 UMKM System - Master Documentation

## 📖 Overview

Ini adalah dokumentasi lengkap untuk sistem **UMKM Authentication & Product Management** yang terintegrasi dengan Karang Taruna Teluknaga Platform.

**Status:** ✅ **SIAP DEPLOY**

---

## 🚀 Mulai Cepat (5 Menit)

### Opsi 1: Otomatis (Recommended)

**Linux/Mac:**
```bash
chmod +x setup-umkm.sh
./setup-umkm.sh
```

**Windows:**
```cmd
setup-umkm.bat
```

### Opsi 2: Manual

```bash
# 1. Migration
php artisan migrate

# 2. Test Data (Optional)
php artisan db:seed --class=UmkmSeeder

# 3. Storage Link
php artisan storage:link

# 4. Clear Caches
php artisan route:clear
php artisan view:clear
php artisan config:cache
```

---

## 📚 Dokumentasi Lengkap

| Dokumen | Deskripsi |
|---------|-----------|
| **UMKM_SETUP.md** | Setup guide & alur lengkap |
| **IMPLEMENTATION_CHECKLIST.md** | Checklist & quick reference |
| **ARCHITECTURE.md** | System design & visual flows |
| **SUMMARY.md** | Overview & file structure |
| **QUICK_REFERENCE.sh** | Quick command reference |
| **Readme.md** | File ini (Master index) |

---

## 🎯 Sistem Alur

```
UMKM Register (Publik)
    ↓
Status: PENDING ⏳
    ↓
Admin Approve + Generate Password
    ↓
Status: DISETUJUI ✅
    ↓
UMKM Login (/umkm/login)
    ↓
Dashboard + Manage Products
    ↓
Create/Edit/Delete Products
```

---

## 🔑 Test Credentials

```
Email: umkm1@test.com    | Password: umkm1234
Email: umkm2@test.com    | Password: umkm5678
Email: umkm3@test.com    | Password: umkm9999
```

**Untuk login test:** Buka http://localhost:8000/umkm/login

---

## 🌐 Penting URLs

### UMKM Section
- `/daftar-umkm` - Registrasi UMKM
- `/umkm/login` - Login UMKM
- `/umkm/dashboard` - Dashboard (after login)
- `/umkm/products` - Kelola produk
- `/umkm/products/create` - Tambah produk
- `/umkm/products/{id}/edit` - Edit produk

### Admin Section
- `/dashboard/admin/umkm` - Manage UMKM
- `/dashboard/admin/umkm/verifikasi` - **Verify & Generate Password**
- `/dashboard/admin/products` - Manage Products

---

## 📁 File Structure

```
kartarumkm/
├── app/Http/Controllers/
│   ├── Auth/UmkmAuthController.php      ← Login/Logout Logic
│   ├── UmkmController.php               ← UMKM Management
│   └── ProductController.php            ← Product CRUD
├── app/Models/
│   ├── Umkm.php                         ← UMKM Model
│   └── Product.php                      ← Product Model
├── app/Policies/
│   └── ProductPolicy.php                ← Authorization
├── app/Http/Middleware/
│   └── UmkmAuthenticated.php            ← Auth Middleware
├── resources/views/umkm/
│   ├── login.blade.php                  ← Login Form
│   ├── dashboard.blade.php              ← Dashboard
│   └── products/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── config/auth.php                      ← Guard Config
├── routes/web.php                       ← UMKM Routes
├── database/
│   ├── migrations/...                   ← Password Column
│   └── seeders/UmkmSeeder.php           ← Test Data
└── Documentation/
    ├── README.md                        ← Master Index
    ├── UMKM_SETUP.md
    ├── IMPLEMENTATION_CHECKLIST.md
    ├── ARCHITECTURE.md
    ├── SUMMARY.md
    ├── QUICK_REFERENCE.sh
    ├── setup-umkm.sh                    ← Linux/Mac Setup
    └── setup-umkm.bat                   ← Windows Setup
```

---

## ✨ Key Features

✅ **Authentication**
- Session-based login dengan guard 'umkm'
- Password hashing dengan bcrypt
- Status validation (pending/disetujui/ditolak)

✅ **Product Management**
- CRUD products untuk UMKM
- Upload photo support
- Status tracking (pending/aktif)

✅ **Authorization**
- ProductPolicy untuk ownership check
- UMKM hanya bisa manage produk sendiri

✅ **Admin Features**
- Generate password otomatis (umkm + 4 digit)
- Approve/Reject UMKM
- Manage all products

✅ **UI/UX**
- Responsive design (Bootstrap 5)
- Form validation
- Success/Error alerts
- Mobile-friendly

---

## 🔐 Security

- Password hashing (bcrypt)
- CSRF protection (@csrf)
- SQL injection prevention (ORM)
- Authorization policies
- Input validation
- File upload validation
- Session management

---

## 💻 Tech Stack

- **Framework:** Laravel 11
- **Auth Guard:** Custom guard 'umkm'
- **Database:** MySQL/PostgreSQL
- **Frontend:** Blade + Bootstrap 5
- **Icons:** Bootstrap Icons
- **File Storage:** Laravel Storage

---

## 📊 Database

### Umkms Table
Kolom: id, nama_toko, pemilik, email, phone, desa, alamat, kategori, deskripsi, **password**, status, omzet_bulanan, foto_toko, no_ktp, lama_usaha, timestamps

### Products Table
Kolom: id, umkm_id (FK), nama_produk, deskripsi, harga, stok, satuan, kategori, foto, status, timestamps

---

## 🔄 Routes Created

```php
// Public Routes (guest:umkm)
GET  /umkm/login              → UmkmAuthController@showLoginForm
POST /umkm/login              → UmkmAuthController@authenticate

// Protected Routes (auth:umkm)
POST   /umkm/logout           → UmkmAuthController@logout
GET    /umkm/dashboard        → Dashboard View
GET    /umkm/products         → ProductController@index
GET    /umkm/products/create  → ProductController@create
POST   /umkm/products         → ProductController@store
GET    /umkm/products/{id}/edit → ProductController@edit
PUT    /umkm/products/{id}    → ProductController@update
DELETE /umkm/products/{id}    → ProductController@destroy
```

---

## 🛠️ Admin Password Generation

Ketika admin klik **"Setujui"** di `/dashboard/admin/umkm/verifikasi`:

1. ✅ Status berubah dari `pending` → `disetujui`
2. ✅ Generate password: `umkm` + 4 digit random
   - Contoh: `umkm1234`, `umkm5678`, `umkm9999`
3. ✅ Hash password dengan bcrypt
4. ✅ Save ke database
5. ✅ Tampilkan password ke admin untuk dikomunikasikan ke UMKM

---

## 📋 Checklist Verifikasi

- [ ] Run `php artisan migrate`
- [ ] (Optional) Run `php artisan db:seed --class=UmkmSeeder`
- [ ] Run `php artisan storage:link`
- [ ] Clear caches: `php artisan route:clear && php artisan view:clear`
- [ ] Visit `/umkm/login` and test login
- [ ] Admin approve UMKM at `/dashboard/admin/umkm/verifikasi`
- [ ] Test create/edit/delete products
- [ ] Test logout

---

## 🐛 Troubleshooting

### Login tidak bekerja
```
✅ Pastikan UMKM status = 'disetujui' di database
✅ Pastikan password ter-hash di database
✅ Check guard 'umkm' di config/auth.php
```

### Foto produk tidak tampil
```
✅ Run: php artisan storage:link
✅ Check: public/storage symlink ada
```

### Authorization error saat edit produk
```
✅ Register ProductPolicy di AuthServiceProvider
✅ Check: umkm_id di products table benar
```

### Routes tidak ketemu
```
✅ Run: php artisan route:clear
✅ Verify routes ada di routes/web.php
```

---

## 🎓 Next Steps (Optional)

1. **Email Notifications**
   - Send approval email to UMKM with login details
   - Send password reset link

2. **SMS Integration**
   - Send password via SMS
   - Send order notifications

3. **Admin Product Approval**
   - Add product approval workflow
   - Email notifications for approval

4. **Analytics**
   - Sales dashboard
   - Product performance metrics
   - Revenue tracking

5. **Payment Integration**
   - Midtrans/PayPal integration
   - Order tracking

---

## 📞 Support

Pertanyaan? Lihat dokumentasi lengkap:
- 📖 **UMKM_SETUP.md** - Setup & alur
- 📋 **IMPLEMENTATION_CHECKLIST.md** - Step by step guide
- 🏗️ **ARCHITECTURE.md** - System design
- 📊 **SUMMARY.md** - File overview

---

## 📝 Changelog

### v1.0.0 (Initial Release)
- ✅ UMKM Authentication system
- ✅ Product management (CRUD)
- ✅ Admin approval workflow
- ✅ Auto password generation
- ✅ Full documentation

---

## 📄 License

Developed for Karang Taruna Teluknaga UMKM Platform

---

## ✅ Status

**System Status:** ✅ **PRODUCTION READY**

```
✅ 12 files created
✅ 3 files updated
✅ Full documentation
✅ Test data seeder
✅ Setup scripts
✅ Ready to deploy
```

---

## 🎉 Ready to Go!

Semua file sudah siap. Tinggal:

1. **Run setup:** `./setup-umkm.sh` atau `setup-umkm.bat`
2. **Start server:** `php artisan serve`
3. **Test login:** http://localhost:8000/umkm/login
4. **Use test credentials:** Email dan password sudah disediakan

---

**Happy Coding! 🚀**

Untuk bantuan lebih lanjut atau pertanyaan, lihat dokumentasi files di folder root project.
