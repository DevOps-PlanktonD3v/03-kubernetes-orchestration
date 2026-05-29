<?php

// ============================================================
// app/Providers/AppServiceProvider.php - Service Provider Utama
//
// Service Provider adalah tempat untuk mendaftarkan binding,
// singleton, dan melakukan bootstrap komponen aplikasi.
//
// Untuk lab ini, kita tidak perlu menambahkan apa-apa di sini.
// File ini wajib ada karena didaftarkan di bootstrap/providers.php.
// ============================================================

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan service ke dalam service container.
     * Dipanggil sebelum boot().
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap service yang sudah didaftarkan.
     * Dipanggil setelah semua provider di-register.
     */
    public function boot(): void
    {
        //
    }
}
