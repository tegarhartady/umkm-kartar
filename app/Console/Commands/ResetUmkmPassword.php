<?php

namespace App\Console\Commands;

use App\Models\Umkm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUmkmPassword extends Command
{
    protected $signature = 'umkm:reset-password {email}';
    protected $description = 'Reset password UMKM yang sudah disetujui';

    public function handle()
    {
        $email = $this->argument('email');
        $umkm = Umkm::where('email', $email)->first();

        if (!$umkm) {
            $this->error('UMKM tidak ditemukan');
            return 1;
        }

        // Generate password baru
        $newPassword = 'umkm' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Update password
        $umkm->update(['password' => Hash::make($newPassword)]);

        $this->info("✅ Password berhasil di-reset!");
        $this->info("Email: {$umkm->email}");
        $this->info("Password Baru: {$newPassword}");

        return 0;
    }
}