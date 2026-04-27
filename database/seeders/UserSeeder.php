<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'role' => 'superadmin',
                'password' => Hash::make('password'),
            ]
        );

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('✅ User seeder berhasil!');
        $this->command->info('📧 Admin Login:');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password');
    }
}
