<?php

// ============================================================
// config/cache.php - Konfigurasi Cache
//
// Untuk lab Kubernetes ini, kita menggunakan driver 'file'
// yang menyimpan cache di storage/framework/cache/data/.
//
// Di production Kubernetes dengan multiple replica, sebaiknya
// gunakan driver 'redis' agar cache di-share antar Pod.
// ============================================================

use Illuminate\Support\Str;

return [
    // Driver cache default
    'default' => env('CACHE_STORE', 'file'),

    'stores' => [
        // Cache berbasis file (cocok untuk 1 replica / lab)
        'file' => [
            'driver'    => 'file',
            'path'      => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        // Buang cache (tidak disimpan)
        'null' => [
            'driver' => 'null',
        ],
    ],

    // Prefix untuk menghindari tabrakan nama key
    'prefix' => env(
        'CACHE_PREFIX',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'
    ),
];
