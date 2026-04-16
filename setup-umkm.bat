@echo off
REM 🚀 UMKM System - One-Click Setup (Windows Batch)
REM Run this script to setup everything automatically

setlocal enabledelayedexpansion

echo ================================
echo 🚀 UMKM System Setup
echo ================================
echo.

REM Step 1: Run Migration
echo Step 1: Running migrations...
call php artisan migrate
if !errorlevel! equ 0 (
    echo ✅ Migration completed
) else (
    echo ❌ Migration failed
    pause
    exit /b 1
)
echo.

REM Step 2: Load Test Data (Optional)
echo Step 2: Loading test data...
call php artisan db:seed --class=UmkmSeeder
if !errorlevel! equ 0 (
    echo ✅ Test data loaded
) else (
    echo ⚠ Test data load skipped
)
echo.

REM Step 3: Create Storage Link
echo Step 3: Creating storage symlink...
call php artisan storage:link
if !errorlevel! equ 0 (
    echo ✅ Storage link created
) else (
    echo ⚠ Storage link already exists
)
echo.

REM Step 4: Clear Caches
echo Step 4: Clearing caches...
call php artisan route:clear
call php artisan view:clear
call php artisan config:cache
echo ✅ Caches cleared
echo.

REM Summary
echo ================================
echo ✅ Setup Completed!
echo ================================
echo.
echo Test Credentials:
echo ─────────────────────
echo Email: umkm1@test.com
echo Password: umkm1234
echo.
echo Email: umkm2@test.com
echo Password: umkm5678
echo.
echo Email: umkm3@test.com
echo Password: umkm9999
echo.
echo URLs to Visit:
echo ─────────────────────
echo Login: http://localhost:8000/umkm/login
echo Dashboard: http://localhost:8000/umkm/dashboard
echo Admin Verify: http://localhost:8000/dashboard/admin/umkm/verifikasi
echo.
echo Documentation:
echo ─────────────────────
echo Setup Guide: UMKM_SETUP.md
echo Checklist: IMPLEMENTATION_CHECKLIST.md
echo Summary: SUMMARY.md
echo.
echo Next: Run 'php artisan serve' to start the app
echo.
pause
