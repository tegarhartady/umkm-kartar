#!/bin/bash

# 🚀 UMKM System - One-Click Setup
# Run this script to setup everything automatically

echo "================================"
echo "🚀 UMKM System Setup"
echo "================================"
echo ""

# Step 1: Run Migration
echo "Step 1️⃣: Running migrations..."
php artisan migrate
if [ $? -eq 0 ]; then
    echo "✅ Migration completed"
else
    echo "❌ Migration failed"
    exit 1
fi
echo ""

# Step 2: Load Test Data (Optional)
echo "Step 2️⃣: Loading test data..."
php artisan db:seed --class=UmkmSeeder
if [ $? -eq 0 ]; then
    echo "✅ Test data loaded"
else
    echo "⚠️  Test data load skipped"
fi
echo ""

# Step 3: Create Storage Link
echo "Step 3️⃣: Creating storage symlink..."
php artisan storage:link
if [ $? -eq 0 ]; then
    echo "✅ Storage link created"
else
    echo "⚠️  Storage link already exists"
fi
echo ""

# Step 4: Clear Caches
echo "Step 4️⃣: Clearing caches..."
php artisan route:clear
php artisan view:clear
php artisan config:cache
echo "✅ Caches cleared"
echo ""

# Summary
echo "================================"
echo "✅ Setup Completed!"
echo "================================"
echo ""
echo "📧 Test Credentials:"
echo "─────────────────────"
echo "Email: umkm1@test.com"
echo "Password: umkm1234"
echo ""
echo "Email: umkm2@test.com"
echo "Password: umkm5678"
echo ""
echo "Email: umkm3@test.com"
echo "Password: umkm9999"
echo ""
echo "🌐 URLs to Visit:"
echo "─────────────────────"
echo "👤 Login: http://localhost:8000/umkm/login"
echo "📊 Dashboard: http://localhost:8000/umkm/dashboard"
echo "✏️  Admin Verify: http://localhost:8000/dashboard/admin/umkm/verifikasi"
echo ""
echo "📚 Documentation:"
echo "─────────────────────"
echo "Setup Guide: UMKM_SETUP.md"
echo "Checklist: IMPLEMENTATION_CHECKLIST.md"
echo "Summary: SUMMARY.md"
echo ""
echo "🚀 Next: Run 'php artisan serve' to start the app"
echo ""
