<?php

// ============================================================
// config/logging.php - Konfigurasi Logging
//
// Di Kubernetes, best practice logging adalah menulis ke stderr
// agar log bisa dibaca langsung dengan: kubectl logs <pod-name>
//
// Untuk lab ini, gunakan channel 'stderr' agar log terlihat
// di output container (kubectl logs).
// ============================================================

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [
    // Channel default yang digunakan
    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace'   => env('LOG_DEPRECATIONS_TRACE', false),
    ],

    'channels' => [
        // Stack menggabungkan beberapa channel
        'stack' => [
            'driver'            => 'stack',
            'channels'          => explode(',', env('LOG_STACK', 'single')),
            'ignore_exceptions' => false,
        ],

        // Tulis log ke file storage/logs/laravel.log
        'single' => [
            'driver'               => 'single',
            'path'                 => storage_path('logs/laravel.log'),
            'level'                => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        // Tulis log ke stderr — ideal untuk Kubernetes!
        // Gunakan LOG_CHANNEL=stderr di .env untuk Kubernetes
        'stderr' => [
            'driver'    => 'monolog',
            'level'     => env('LOG_LEVEL', 'debug'),
            'handler'   => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with'      => ['stream' => 'php://stderr'],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        // Buang semua log (berguna saat testing)
        'null' => [
            'driver'  => 'monolog',
            'handler' => NullHandler::class,
        ],
    ],
];
