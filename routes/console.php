<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Review;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reviews:auto-complete', function () {
    $count = Review::autoGenerateForCompletedTransactions(true);
    $this->info("Berhasil membuat {$count} ulasan otomatis bintang 5.");
})->purpose('Otomatis membuat ulasan bintang 5 untuk transaksi selesai lebih dari 3 hari');

Schedule::command('reviews:auto-complete')->daily();
