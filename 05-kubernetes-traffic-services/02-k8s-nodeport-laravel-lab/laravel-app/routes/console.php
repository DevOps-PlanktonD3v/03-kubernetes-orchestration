<?php

// ============================================================
// routes/console.php - Definisi Perintah Artisan Custom
//
// File ini mendaftarkan perintah CLI custom untuk Artisan.
// Artisan adalah CLI (Command Line Interface) bawaan Laravel.
//
// Contoh penggunaan: php artisan inspire
// ============================================================

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
