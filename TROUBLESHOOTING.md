# 🔧 UMKM System - Troubleshooting Guide

## 🚨 Common Issues & Solutions

---

## 🔴 Authentication Issues

### ❌ Issue: Login page shows "email tidak terdaftar"

**Cause:** Email doesn't exist in database or not approved yet

**Solution:**
```bash
# 1. Check if UMKM exists
php artisan tinker
> App\Models\Umkm::where('email', 'umkm1@test.com')->first()

# 2. Check UMKM status
> $umkm = App\Models\Umkm::where('email', 'umkm1@test.com')->first()
> echo $umkm->status;  // Should be: disetujui

# 3. If status is 'pending', admin needs to approve first
# Go to: /dashboard/admin/umkm/verifikasi
```

---

### ❌ Issue: Login shows "Password salah"

**Cause:** Incorrect password or password not hashed

**Solution:**
```bash
# 1. Check if password is hashed
php artisan tinker
> $umkm = App\Models\Umkm::where('email', 'umkm1@test.com')->first()
> echo $umkm->password;  // Should start with $2y$

# 2. If not hashed, re-hash it manually
> use Illuminate\Support\Facades\Hash;
> $umkm->password = Hash::make('umkm1234');
> $umkm->save();

# 3. Now try logging in again with: umkm1234
```

---

### ❌ Issue: "Pendaftaran Anda masih menunggu persetujuan"

**Cause:** UMKM status is still 'pending'

**Solution:**
```
1. Admin go to: /dashboard/admin/umkm/verifikasi
2. Find the UMKM
3. Click "✅ Setujui"
4. Note the generated password
5. Inform UMKM to use new password
6. Status changes to 'disetujui' automatically
```

---

### ❌ Issue: Session lost / Keep logging out

**Cause:** Session configuration issue

**Solution:**
```bash
# 1. Check SESSION_DRIVER in .env
cat .env | grep SESSION

# 2. Should be:
SESSION_DRIVER=session

# 3. Check storage permissions
chmod -R 775 storage/framework/sessions

# 4. Regenerate config cache
php artisan config:cache
```

---

## 📁 File Upload Issues

### ❌ Issue: Photos don't display

**Cause:** Storage symlink not created or missing

**Solution:**
```bash
# 1. Create storage symlink
php artisan storage:link

# 2. Verify symlink exists
ls -la public/storage

# 3. Check .env
cat .env | grep FILESYSTEM

# 4. Should have:
FILESYSTEM_DISK=public
```

---

### ❌ Issue: Photo upload fails silently

**Cause:** File permissions or storage folder missing

**Solution:**
```bash
# 1. Create products folder
mkdir -p storage/app/public/products

# 2. Fix permissions
chmod -R 755 storage/app/public
chmod -R 755 storage/framework
chmod -R 755 bootstrap/cache

# 3. Try uploading again
```

---

### ❌ Issue: "MIME type not allowed" error

**Cause:** File format validation

**Solution:**
```php
// In ProductController.php, check allowed formats:
'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'

// Only these formats allowed:
- .jpg / .jpeg
- .png
- .gif

// Max size: 2MB
```

---

## 🔐 Authorization Issues

### ❌ Issue: Edit/Delete shows "Unauthorized"

**Cause:** UMKM trying to edit another UMKM's product

**Solution:**
```bash
# 1. This is working as intended (security feature)
# 2. UMKM can only edit/delete own products

# 3. Check ProductPolicy.php:
app/Policies/ProductPolicy.php

# 4. Verify umkm_id matches:
php artisan tinker
> $product = Product::find($productId)
> echo $product->umkm_id;
> echo Auth::guard('umkm')->user()->id;
# These should match!
```

---

### ❌ Issue: "Policy not registered" error

**Cause:** ProductPolicy not registered in AuthServiceProvider

**Solution:**
```php
// app/Providers/AuthServiceProvider.php

<?php
namespace App\Providers;

use App\Models\Product;
use App\Policies\ProductPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
```

---

## 🛣️ Route Issues

### ❌ Issue: "404 - Route not found /umkm/login"

**Cause:** Routes not cached or not registered

**Solution:**
```bash
# 1. Clear route cache
php artisan route:clear

# 2. Verify routes registered
php artisan route:list | grep umkm

# 3. Should show all /umkm/* routes

# 4. If still not found, check routes/web.php
# Make sure UMKM routes are present
```

---

### ❌ Issue: Routes work but wrong guard applied

**Cause:** Wrong middleware or guard configuration

**Solution:**
```php
// routes/web.php - Check structure:

// For public (guest)
Route::middleware('guest:umkm')->group(function () {
    // Login routes here
});

// For protected (authenticated)
Route::middleware('auth:umkm')->group(function () {
    // Protected routes here
});
```

---

## 💾 Database Issues

### ❌ Issue: "Column 'password' doesn't exist"

**Cause:** Migration not run

**Solution:**
```bash
# 1. Run migration
php artisan migrate

# 2. Verify password column exists
php artisan tinker
> Schema::hasColumn('umkms', 'password')  # Should return: true

# 3. If false, run migration again
php artisan migrate --fresh  # ⚠️ This deletes all data!
```

---

### ❌ Issue: Can't seed test data

**Cause:** Seeder not found or syntax error

**Solution:**
```bash
# 1. Verify seeder exists
ls -la database/seeders/UmkmSeeder.php

# 2. Run seeder
php artisan db:seed --class=UmkmSeeder

# 3. Check for errors
php artisan tinker
> App\Models\Umkm::count()  # Should be > 0

# 4. If error, check seeder syntax
cat database/seeders/UmkmSeeder.php
```

---

### ❌ Issue: Foreign key constraint error

**Cause:** umkm_id doesn't exist in products table

**Solution:**
```bash
# 1. Check products table structure
php artisan tinker
> Schema::getColumnListing('products')

# 2. Should include 'umkm_id'

# 3. Verify data integrity
> $product = Product::find(1)
> $product->umkm_id  # Should have a value
```

---

## 🎨 UI/UX Issues

### ❌ Issue: Bootstrap styles not loading

**Cause:** CSS not linked correctly

**Solution:**
```blade
<!-- Check in layouts/app.blade.php -->

<!-- Should have Bootstrap CDN or local file -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Also check Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.css">
```

---

### ❌ Issue: Icons not showing (bi-eye, etc.)

**Cause:** Bootstrap Icons not loaded

**Solution:**
```blade
<!-- Add to head section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.css">

<!-- Verify Bootstrap Icons CDN is correct -->
<!-- Latest: https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/ -->
```

---

### ❌ Issue: Form validation messages not showing

**Cause:** Error blade not included or wrong syntax

**Solution:**
```blade
<!-- Correct syntax -->
@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<!-- Per field -->
@error('email')
    <span class="text-danger">{{ $message }}</span>
@enderror
```

---

## 🔍 Debugging Tips

### ⚙️ Enable Debug Mode

```bash
# .env file
APP_DEBUG=true
LOG_LEVEL=debug

# Check logs
tail -f storage/logs/laravel.log
```

---

### ⚙️ Test Authentication State

```bash
php artisan tinker

# Check if UMKM logged in
> Auth::guard('umkm')->check()

# Get current user
> Auth::guard('umkm')->user()

# Check specific UMKM
> $umkm = App\Models\Umkm::first()
> Auth::guard('umkm')->login($umkm)
> Auth::guard('umkm')->user()
```

---

### ⚙️ Check Database State

```bash
php artisan tinker

# Count UMKM
> App\Models\Umkm::count()

# Find by email
> App\Models\Umkm::where('email', 'test@test.com')->first()

# Check status
> App\Models\Umkm::where('status', 'disetujui')->count()

# List products for UMKM
> $umkm = App\Models\Umkm::first()
> $umkm->products()->get()
```

---

### ⚙️ Clear All Caches

```bash
# Clear all
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
```

---

## 📊 Performance Checks

### Check if everything is working:

```bash
# 1. Routes
php artisan route:list | grep umkm

# 2. Models
php artisan tinker
> new App\Models\Umkm()
> new App\Models\Product()

# 3. Controllers
ls -la app/Http/Controllers/Auth/
ls -la app/Http/Controllers/ProductController.php

# 4. Views
ls -la resources/views/umkm/

# 5. Config
php artisan tinker
> config('auth.guards.umkm')
```

---

## 🆘 When All Else Fails

### Full Reset

```bash
# ⚠️ WARNING: This deletes all data!

# 1. Drop tables
php artisan migrate:reset

# 2. Run migrations again
php artisan migrate

# 3. Seed test data
php artisan db:seed --class=UmkmSeeder

# 4. Clear everything
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 5. Start fresh
php artisan serve
```

---

## 📞 Getting Help

If issue persists:

1. **Check logs:** `storage/logs/laravel.log`
2. **Debug mode:** Set `APP_DEBUG=true`
3. **Use tinker:** `php artisan tinker`
4. **Check code:** Read the error message carefully
5. **Google it:** Copy exact error message to search
6. **Read docs:** https://laravel.com/docs

---

## ✅ Verification Checklist

```bash
# Run these commands to verify everything works:

# 1. Migration
php artisan migrate:status  # Should show "Ran"

# 2. Routes
php artisan route:list | grep umkm  # Should show all UMKM routes

# 3. Models
php artisan tinker
> App\Models\Umkm::count()  # Should be > 0
> App\Models\Product::count()  # Should be > 0

# 4. Auth Guard
> config('auth.guards.umkm')  # Should return array with driver & provider

# 5. Storage Link
ls -la public/storage  # Should exist

# 6. Server
php artisan serve  # Should start on 127.0.0.1:8000

# 7. Login
# Visit http://localhost:8000/umkm/login
# Login with: umkm1@test.com / umkm1234
# Should redirect to /umkm/dashboard
```

---

## 🎯 Quick Fix Summary

| Problem | Solution |
|---------|----------|
| Login fails | Check UMKM status = 'disetujui' |
| Photos don't show | Run `php artisan storage:link` |
| Routes 404 | Run `php artisan route:clear` |
| Password error | Check if hashed in database |
| Auth error | Check guard 'umkm' in config |
| Styles broken | Check Bootstrap CDN link |
| Icons missing | Check Bootstrap Icons CDN |
| Database error | Run `php artisan migrate` |
| Everything broken | Run `php artisan migrate:refresh --seed` |

---

**Still stuck? Read the code comments and documentation files!** 📚

*For Laravel help: https://laravel.com/docs*
