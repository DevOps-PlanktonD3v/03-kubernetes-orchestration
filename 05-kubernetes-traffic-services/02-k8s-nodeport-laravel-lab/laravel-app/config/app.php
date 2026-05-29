<?php

// ============================================================
// config/app.php - Konfigurasi Utama Aplikasi
//
// File ini berisi pengaturan dasar aplikasi Laravel.
// Nilai-nilai di sini dibaca dari environment variable (.env)
// dengan fallback ke nilai default jika env var tidak ada.
//
// Di Kubernetes, env var di-inject melalui:
// - ConfigMap  : untuk data non-sensitif (APP_NAME, APP_ENV)
// - Secret     : untuk data sensitif (APP_KEY, DB_PASSWORD)
// ============================================================

return [
    // Nama aplikasi, tampil di notifikasi dan log
    'name' => env('APP_NAME', 'Laravel'),

    // Environment: local, staging, production
    'env' => env('APP_ENV', 'production'),

    // Mode debug — HARUS false di production!
    'debug' => (bool) env('APP_DEBUG', false),

    // URL publik aplikasi
    'url' => env('APP_URL', 'http://localhost'),

    // Timezone default untuk Carbon (tanggal/waktu)
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    // Locale untuk internationalization (i18n)
    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    // Algoritma enkripsi untuk data sensitif (cookie, session)
    'cipher' => 'AES-256-CBC',

    // Kunci enkripsi — generate dengan: php artisan key:generate
    'key' => env('APP_KEY'),

    // Kunci lama (untuk rotasi kunci enkripsi)
    'previous_keys' => array_filter(
        explode(',', env('APP_PREVIOUS_KEYS', ''))
    ),

    // Konfigurasi maintenance mode
    'maintenance' => [
        'driver' => 'file',
    ],
];
