<?php

// ============================================================
// config/session.php - Konfigurasi Session
//
// Session menyimpan data pengguna antar request (misal: login state).
// Untuk lab ini, driver 'file' sudah cukup.
//
// Di production Kubernetes dengan multiple replica:
// gunakan SESSION_DRIVER=redis agar session di-share antar Pod,
// karena setiap request bisa diarahkan ke Pod yang berbeda.
// ============================================================

use Illuminate\Support\Str;

return [
    // Driver penyimpanan session
    'driver' => env('SESSION_DRIVER', 'file'),

    // Durasi session dalam menit
    'lifetime' => env('SESSION_LIFETIME', 120),

    // Hapus session saat browser ditutup
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    // Enkripsi data session
    'encrypt' => env('SESSION_ENCRYPT', false),

    // Folder penyimpanan session (untuk driver 'file')
    'files' => storage_path('framework/sessions'),

    // Nama tabel session (untuk driver 'database')
    'table' => env('SESSION_TABLE', 'sessions'),

    // Probabilitas pembersihan session expired [peluang, total]
    'lottery' => [2, 100],

    // Nama cookie session
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),

    'path'        => env('SESSION_PATH', '/'),
    'domain'      => env('SESSION_DOMAIN'),
    'secure'      => env('SESSION_SECURE_COOKIE'),
    'http_only'   => env('SESSION_HTTP_ONLY', true),
    'same_site'   => env('SESSION_SAME_SITE', 'lax'),
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),
];
