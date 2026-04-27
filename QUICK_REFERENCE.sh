#!/usr/bin/env bash

# 📋 UMKM System - Quick Reference & Cheatsheet

cat << 'EOF'

╔══════════════════════════════════════════════════════════════════════════════╗
║                    🚀 UMKM SYSTEM - QUICK REFERENCE                         ║
╚══════════════════════════════════════════════════════════════════════════════╝

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📌 SETUP & INSTALLATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  # Linux/Mac
  chmod +x setup-umkm.sh
  ./setup-umkm.sh

  # Windows
  setup-umkm.bat

  # Manual
  php artisan migrate
  php artisan db:seed --class=UmkmSeeder
  php artisan storage:link
  php artisan route:clear
  php artisan view:clear
  php artisan config:cache

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔑 TEST CREDENTIALS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Email: umkm1@test.com       | Password: umkm1234
  Email: umkm2@test.com       | Password: umkm5678
  Email: umkm3@test.com       | Password: umkm9999

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🌐 IMPORTANT URLS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  UMKM SECTION:
  ├─ /daftar-umkm                          → UMKM Registration Form
  ├─ /umkm/login                           → UMKM Login
  ├─ /umkm/dashboard                       → UMKM Dashboard
  ├─ /umkm/products                        → Product List
  ├─ /umkm/products/create                 → Add Product Form
  └─ /umkm/products/{id}/edit              → Edit Product Form

  ADMIN SECTION:
  ├─ /dashboard/admin/umkm                 → Manage UMKM
  ├─ /dashboard/admin/umkm/verifikasi      → Verify UMKM (Generate Password)
  └─ /dashboard/admin/products             → Manage Products

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔄 ALUR SISTEM
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  1. UMKM Daftar
     └─ /daftar-umkm → Status: pending

  2. Admin Approve
     └─ /dashboard/admin/umkm/verifikasi
     └─ Klik "Setujui"
     └─ Generate password (umkm + 4 digit)
     └─ Status: disetujui

  3. UMKM Login
     └─ /umkm/login → Email + Password
     └─ Guard: umkm (Session)

  4. UMKM Dashboard
     └─ /umkm/dashboard → View stats & products

  5. Manage Products
     └─ /umkm/products → CRUD operations
     └─ Product auto status: pending (admin approve to aktif)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📁 KEY FILES
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  CONTROLLERS:
  ├─ app/Http/Controllers/Auth/UmkmAuthController.php
  ├─ app/Http/Controllers/UmkmController.php
  └─ app/Http/Controllers/ProductController.php

  MODELS:
  ├─ app/Models/Umkm.php
  └─ app/Models/Product.php

  VIEWS:
  ├─ resources/views/umkm/login.blade.php
  ├─ resources/views/umkm/dashboard.blade.php
  └─ resources/views/umkm/products/{index,create,edit}.blade.php

  CONFIG:
  ├─ config/auth.php (guards & providers)
  └─ routes/web.php (UMKM routes)

  SECURITY:
  ├─ app/Policies/ProductPolicy.php
  └─ app/Http/Middleware/UmkmAuthenticated.php

  DOCS:
  ├─ UMKM_SETUP.md
  ├─ IMPLEMENTATION_CHECKLIST.md
  ├─ ARCHITECTURE.md
  └─ SUMMARY.md

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🔐 AUTH GUARD
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  GUARD NAME: umkm
  MODEL: App\Models\Umkm
  SESSION BASED

  Usage in Code:
  ├─ Auth::guard('umkm')->login($umkm)
  ├─ Auth::guard('umkm')->logout()
  ├─ Auth::guard('umkm')->check()
  ├─ Auth::guard('umkm')->user()
  └─ middleware('auth:umkm')

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💾 DATABASE STATUS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  UMKM Status:
  ├─ pending    → Menunggu approval dari admin
  ├─ disetujui  → Approved, bisa login
  └─ ditolak    → Rejected

  Product Status:
  ├─ pending    → Menunggu approval dari admin (default)
  └─ aktif      → Published, bisa dilihat publik

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🛠️ COMMON TASKS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  # Check if UMKM logged in
  php artisan tinker
  > Auth::guard('umkm')->check()

  # Get current UMKM user
  > Auth::guard('umkm')->user()

  # Check user in view
  {{ Auth::guard('umkm')->check() ? 'Logged in' : 'Not logged in' }}

  # Hash password manually
  > Hash::make('password123')

  # Find UMKM by email
  > App\Models\Umkm::where('email', 'test@test.com')->first()

  # Get UMKM products
  > $umkm = App\Models\Umkm::first()
  > $umkm->products()->get()

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🐛 TROUBLESHOOTING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Issue: Login loop / tidak masuk dashboard
  └─ Check UMKM status = 'disetujui' di database
  └─ Check password ter-hash di database
  └─ Check guard 'umkm' di config/auth.php

  Issue: Product authorization error
  └─ Register ProductPolicy di AuthServiceProvider
  └─ Check umkm_id di products table benar

  Issue: Foto tidak upload
  └─ Run: php artisan storage:link
  └─ Check: public/storage symlink ada
  └─ Check folder permissions

  Issue: Route not found /umkm/login
  └─ Run: php artisan route:clear
  └─ Verify routes ada di web.php

  Issue: Session not working
  └─ Check: SESSION_DRIVER di .env = session
  └─ Check: storage/framework/sessions writable

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🎓 LEARNING RESOURCES
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  Documentation:
  ├─ https://laravel.com/docs/11/authentication
  ├─ https://laravel.com/docs/11/authorization
  ├─ https://laravel.com/docs/11/session
  └─ https://laravel.com/docs/11/blade

  Local Files:
  ├─ UMKM_SETUP.md             → Full setup guide
  ├─ IMPLEMENTATION_CHECKLIST.md → Step by step
  ├─ ARCHITECTURE.md            → System design
  └─ SUMMARY.md                 → Overview

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
✅ STATUS CHECKLIST
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  ✅ Controllers created
  ✅ Models configured
  ✅ Views designed
  ✅ Routes registered
  ✅ Guard setup
  ✅ Policy authorization
  ✅ Database migrations ready
  ✅ Test data seeder
  ✅ Documentation complete
  ✅ Setup scripts ready

  🎉 READY TO DEPLOY!

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🚀 NEXT STEPS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

  1. Run setup script (./setup-umkm.sh atau setup-umkm.bat)
  2. Start Laravel server (php artisan serve)
  3. Test login at http://localhost:8000/umkm/login
  4. Use test credentials provided above
  5. Create new products
  6. Test admin approval flow

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

For more help, see:
  📖 UMKM_SETUP.md
  📋 IMPLEMENTATION_CHECKLIST.md
  🏗️ ARCHITECTURE.md

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

EOF
