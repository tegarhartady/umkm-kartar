<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    public function run()
    {
        $banks = [
            ['nama_bank' => 'BCA', 'kode_bank' => '014'],
            ['nama_bank' => 'Mandiri', 'kode_bank' => '008'],
            ['nama_bank' => 'BNI', 'kode_bank' => '009'],
            ['nama_bank' => 'BRI', 'kode_bank' => '002'],
            ['nama_bank' => 'BSI (Bank Syariah Indonesia)', 'kode_bank' => '451'],
            ['nama_bank' => 'CIMB Niaga', 'kode_bank' => '022'],
            ['nama_bank' => 'Danamon', 'kode_bank' => '011'],
            ['nama_bank' => 'BTN', 'kode_bank' => '200'],
            ['nama_bank' => 'Dana', 'kode_bank' => null],
            ['nama_bank' => 'OVO', 'kode_bank' => null],
            ['nama_bank' => 'GoPay', 'kode_bank' => null],
            ['nama_bank' => 'LinkAja', 'kode_bank' => null],
            ['nama_bank' => 'ShopeePay', 'kode_bank' => null],
        ];

        foreach ($banks as $bank) {
            Bank::firstOrCreate(
                ['nama_bank' => $bank['nama_bank']],
                ['kode_bank' => $bank['kode_bank'], 'is_active' => true]
            );
        }
    }
}
