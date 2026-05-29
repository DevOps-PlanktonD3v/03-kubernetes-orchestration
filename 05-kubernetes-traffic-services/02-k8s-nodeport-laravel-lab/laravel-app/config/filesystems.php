<?php

// ============================================================
// config/filesystems.php - Konfigurasi File Storage
//
// Konfigurasi sistem penyimpanan file Laravel.
// Lab ini tidak menyimpan file, tapi config ini diperlukan
// agar framework berjalan normal.
// ============================================================

return [
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [
        // Disk lokal (private) — tidak bisa diakses publik
        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app/private'),
            'serve'  => true,
            'throw'  => false,
        ],

        // Disk publik — bisa diakses via URL /storage/...
        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw'      => false,
        ],
    ],

    // Symlink: public/storage → storage/app/public
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],
];
