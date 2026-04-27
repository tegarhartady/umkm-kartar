# 🧪 TESTING GUIDE - DASHBOARD UMKM & ROLE SYSTEM

## ✅ PRE-TESTING REQUIREMENTS

### 1. Run Migration
```bash
cd /Users/tegarhartady/Documents/projet/umkm_kartar_teluknaga/kartarumkm
php artisan migrate
```

### 2. (Optional) Seed Test Data
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=UmkmSeeder
php artisan db:seed --class=ProductSeeder
```

### 3. Start Laravel Server
```bash
php artisan serve
# Server runs at http://127.0.0.1:8000
```

---

## 🎯 TEST SCENARIOS

### ✅ TEST 1: Super Admin Access

**Credentials:**
```
Email: superadmin@test.com
Password: password
```

**Steps:**
1. Go to `http://127.0.0.1:8000/login`
2. Enter super admin credentials
3. Should redirect to `/superadmin/dashboard`

**Expected Results:**
```
✓ Dashboard loads successfully
✓ Can see "Kelola Admin" menu
✓ Can see "Pengaturan Sistem" menu
✓ Cannot see UMKM management (not needed)
✓ Can click on "Kelola Admin" without error
```

**Verification:**
```
- Check sidebar menu for SUPER ADMIN section
- Verify you can access /superadmin/admins
- Verify you can access /superadmin/settings
- Try accessing /dashboard/admin → should work (has permission)
- Try accessing /dashboard/umkm → should work (has permission)
```

---

### ✅ TEST 2: Admin Access

**Credentials:**
```
Email: admin@test.com
Password: password
```

**Steps:**
1. Go to `http://127.0.0.1:8000/login`
2. Enter admin credentials
3. Should redirect to `/dashboard/admin`

**Expected Results:**
```
✓ Admin dashboard loads
✓ Statistics displayed (Total Omzet, UMKM Terdaftar, etc.)
✓ Can see "Kelola UMKM" menu
✓ Can see "Produk" menu with Moderasi option
✓ Can see "Laporan" menu
✓ Can see "Pengaturan" menu
```

**Verification:**
```
- Check if all stats are populated (not 0 or empty)
- Click "Kelola UMKM" → should see list of all UMKM
- Click "Moderasi Produk" → should see all products
- Try accessing /superadmin/dashboard → should get 403
- Try accessing /dashboard/umkm → should work
  (but show different data)
```

**Statistics to Check:**
```
□ Total Omzet: Should show a number
□ UMKM Terdaftar: Should show count > 0
□ UMKM Baru: Should show recent count
□ Total Produk: Should show published products
□ Menunggu Verifikasi: Should show pending count
□ Peningkatan per Desa: Should show breakdown
```

---

### ✅ TEST 3: UMKM User Access

**Credentials (If Seeded):**
```
Email: umkm1@test.com
Password: password

OR

Email: umkm2@test.com
Password: password
```

**Steps:**
1. Go to `http://127.0.0.1:8000/login`
2. Enter UMKM credentials
3. Should redirect to `/dashboard/umkm`

**Expected Results:**
```
✓ UMKM dashboard loads (personal dashboard)
✓ Shows only THEIR shop name & status
✓ Shows ONLY their products (not all products)
✓ Shows ONLY their statistics
✓ Cannot see admin menu items
✓ Sidebar has minimal menu
```

**Personal Data Verification:**
```
□ Dashboard Header: Shows "Selamat datang, [USER_NAME]!"
□ Shop Name: Shows their UMKM name
□ Status: Shows their UMKM status (Active/Pending/Rejected)
□ Total Penjualan: Calculated from their products only
□ Produk Aktif: Count of their published products
□ Total Produk: Total of their products (all statuses)
□ Produk Terbaru: Shows only their products (max 5)
□ Stok Menipis: Shows only their low-stock products
```

---

### ✅ TEST 4: Data Isolation - UMKM Cannot See Other UMKM

**Test:**
1. Login as UMKM1 → note their products
2. Login as UMKM2 → check products
3. Verify they're DIFFERENT

**Expected:**
```
✓ UMKM1 sees only UMKM1's products
✓ UMKM2 sees only UMKM2's products
✓ They NEVER see products from each other
✓ Dashboard stats are DIFFERENT for each UMKM
```

**How to Test:**
```
1. Login as umkm1@test.com
   - Note: product names, count, stok values
   - Note: Penjualan, Produk Aktif numbers

2. Logout (click dropdown → Logout)

3. Login as umkm2@test.com
   - Check: Different products shown?
   - Check: Different product count?
   - Check: Different stats?

4. If same products/stats → ERROR: Data isolation failed!
```

---

### ✅ TEST 5: UMKM Cannot Access Admin Dashboard

**Test:**
1. Login as UMKM user
2. Try to access `/dashboard/admin` directly

**Expected:**
```
✓ Get 403 Forbidden error
✓ Cannot view admin stats
✓ Cannot manage UMKM
✓ Cannot moderate products
```

**How to Test:**
```
1. Login as umkm@test.com
2. In URL bar: type http://127.0.0.1:8000/dashboard/admin
3. Press Enter
4. Should see: 403 Forbidden OR redirect back
```

---

### ✅ TEST 6: Admin Cannot Access Super Admin

**Test:**
1. Login as Admin
2. Try to access `/superadmin/dashboard` directly

**Expected:**
```
✓ Get 403 Forbidden error
✓ Cannot see admin management panel
✓ Cannot access system settings
```

**How to Test:**
```
1. Login as admin@test.com
2. In URL bar: type http://127.0.0.1:8000/superadmin/dashboard
3. Press Enter
4. Should see: 403 Forbidden OR redirect back
```

---

### ✅ TEST 7: No UMKM - Empty State Handling

**Test:**
1. Create a new UMKM user (or use one with no UMKM assigned)
2. Login and navigate to `/dashboard/umkm`

**Expected:**
```
✓ Shows "Belum Ada UMKM" message
✓ Shows helpful explanation
✓ Shows "Kembali ke Home" button
✓ Does NOT crash or show errors
```

**How to Test:**
```
1. Go to /login
2. Create new user with role='umkm' (or use seeded)
3. If they have no UMKM associated:
   - Should see friendly message
   - Should NOT see stats cards
   - Should NOT see product list
```

---

### ✅ TEST 8: Product Filtering - Show Only UMKM's Products

**Test:**
1. Login as Admin
2. Check product list (all products visible)
3. Note: which products belong to which UMKM
4. Login as specific UMKM
5. Verify only their products shown

**Expected:**
```
✓ Admin sees all products (from all UMKM)
✓ UMKM1 sees only UMKM1's products
✓ UMKM2 sees only UMKM2's products
✓ Count matches exactly
```

---

### ✅ TEST 9: Product Status Handling

**Test:**
1. Login as UMKM
2. Check products with different statuses

**Expected Results:**
```
✓ Active Products (status='published'): Counted in "Produk Aktif"
✓ Pending Products (status='pending'): 
  - Counted in "Total Produk Pending"
  - Alert box shown if count > 0
✓ Draft products: Shown in product list with "Draft" badge
```

---

### ✅ TEST 10: Stock Warning Alert

**Test:**
1. Login as UMKM with products
2. Check "Stok Menipis" section

**Expected:**
```
✓ Shows products with stok < 5
✓ Displays product name, stok amount, price
✓ Has warning icon
✓ If all stok >= 5: Shows "Semua produk stok aman"
```

---

## 🔍 CHECKING DATABASE

### Verify User-UMKM Relations:

```sql
-- In your database client or artisan tinker:

-- Check user_id added to UMKM
SELECT id, user_id, nama_toko, status FROM umkms LIMIT 5;

-- Verify foreign key
SELECT * FROM information_schema.KEY_COLUMN_USAGE 
WHERE TABLE_NAME='umkms' AND COLUMN_NAME='user_id';

-- Check relations
SELECT u.id, u.name, u.role, COUNT(um.id) as umkm_count 
FROM users u 
LEFT JOIN umkms um ON u.id = um.user_id 
GROUP BY u.id;
```

### Using Tinker:

```bash
php artisan tinker

# Get a UMKM user
>>> $user = User::where('role', 'umkm')->first();
>>> $user->umkms;  // Should show their UMKMs

# Get a UMKM
>>> $umkm = Umkm::first();
>>> $umkm->user;   // Should show the owning user
>>> $umkm->products;  // Should show their products
```

---

## ⚠️ ERROR SCENARIOS & SOLUTIONS

### Error 1: "Call to undefined method umkms()"
**Cause:** User model relation not updated
**Fix:** 
```php
// Add to User model
public function umkms()
{
    return $this->hasMany(Umkm::class);
}
```

### Error 2: "SQLSTATE[HY000]: General error: 1030"
**Cause:** Migration not run
**Fix:** 
```bash
php artisan migrate
```

### Error 3: UMKM sees other UMKM's products
**Cause:** Data isolation not working in controller
**Fix:** Check DashboardController::umkm() method uses:
```php
$user = auth()->user();
$umkms = $user->umkms();  // Filter by user
```

### Error 4: Blank dashboard stats
**Cause:** No data in database
**Fix:** 
```bash
php artisan db:seed  # Seed all data
```

---

## 📋 TESTING CHECKLIST

```
DATABASE & MODELS
☐ Migration ran successfully
☐ user_id column added to umkms
☐ Foreign key constraint exists
☐ User::umkms() relation works
☐ Umkm::user() relation works

ROUTES & MIDDLEWARE
☐ /superadmin/dashboard accessible (Super Admin only)
☐ /dashboard/admin accessible (Admin & Super Admin)
☐ /dashboard/umkm accessible (UMKM only)
☐ Unauthorized users get 403

DATA DISPLAY
☐ Admin sees ALL UMKM data
☐ Admin sees ALL product data
☐ UMKM1 sees own UMKM only
☐ UMKM2 sees own UMKM only
☐ UMKM cannot see UMKM2's products

STATISTICS
☐ Admin stats are populated (not zero)
☐ UMKM stats are calculated correctly
☐ Product counts match actual products
☐ Stock warnings display correctly

EDGE CASES
☐ User with no UMKM shows proper message
☐ UMKM with no products shows empty state
☐ Pending products show alert
☐ Low stock products show in section

UI/UX
☐ Dashboard responsive on mobile
☐ All buttons/links work
☐ Images load or show placeholder
☐ Animations work smoothly
☐ Empty states look good
```

---

## 🚀 ACCEPTANCE CRITERIA

### ✅ For Production Release:

```
1. SECURITY
   ☑ UMKM cannot access admin routes
   ☑ Admin cannot access super admin routes
   ☑ Data strictly isolated by user
   ☑ No SQL injection possible

2. FUNCTIONALITY
   ☑ All dashboards load correctly
   ☑ Statistics calculated accurately
   ☑ Product filtering works
   ☑ Role-based access works

3. PERFORMANCE
   ☑ Dashboard loads < 2 seconds
   ☑ No N+1 query problems
   ☑ Images optimized
   ☑ Responsive on mobile

4. DATA
   ☑ UMKM sees only own data
   ☑ Admin sees all data
   ☑ Super Admin sees all + admin features
   ☑ No data leakage

5. ERROR HANDLING
   ☑ Graceful handling of no UMKM
   ☑ Proper error messages
   ☑ No 500 errors on edge cases
   ☑ Friendly 403 messages
```

---

## 📞 TROUBLESHOOTING

### Dashboard shows 0 for all stats?
```
1. Check if data exists in database
   → SELECT COUNT(*) FROM umkms;
2. Run seeders if needed
   → php artisan db:seed
3. Verify migrations ran
   → php artisan migrate:status
```

### Getting "Undefined variable" errors?
```
1. Check variable passed from controller
2. Check view for typos
3. Look in storage/logs/laravel.log
```

### UMKM can access admin dashboard?
```
1. Check middleware in routes/web.php
2. Verify middleware/AdminOrSuperAdminMiddleware.php
3. Check user role in database
```

### Data showing up for wrong UMKM?
```
1. Verify user_id correctly set in umkms table
2. Check relation in DashboardController::umkm()
3. Debug with: dd(auth()->user()->umkms);
```

---

## 📖 REFERENCE

**Files to Check:**
- Controller: `app/Http/Controllers/DashboardController.php`
- Models: `app/Models/User.php`, `Umkm.php`
- View: `resources/views/pages/dashboard_umkm.blade.php`
- Routes: `routes/web.php`
- Migration: `database/migrations/2026_04_13_000001_*`

**Test Commands:**
```bash
# Run artisan tinker
php artisan tinker

# Check user relations
>>> User::find(1)->umkms;

# Check UMKM owner
>>> Umkm::find(1)->user;

# Count UMKM per user
>>> User::withCount('umkms')->get();
```

---

## ✨ TEST COMPLETION

Once all tests pass:
1. ✅ Dashboard UMKM is production-ready
2. ✅ Role system is secure
3. ✅ Data isolation is guaranteed
4. ✅ Users can be safely given access

**Ready for deployment!** 🚀
