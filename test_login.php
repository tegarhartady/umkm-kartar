<?php

// Load Laravel app
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check users
$users = \App\Models\User::all();
echo "=== USERS ===\n";
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
}

echo "\n=== UMKMS ===\n";
$umkms = \App\Models\Umkm::all();
foreach ($umkms as $umkm) {
    echo "ID: {$umkm->id} | Store: {$umkm->nama_toko} | Owner: {$umkm->pemilik} | User ID: {$umkm->user_id}\n";
}

echo "\n=== PRODUCTS COUNT ===\n";
echo "Total Products: " . \App\Models\Product::count() . "\n";
