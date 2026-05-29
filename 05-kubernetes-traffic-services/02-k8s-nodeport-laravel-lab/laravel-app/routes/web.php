<?php

// ============================================================
// routes/web.php - Definisi Routing Aplikasi
//
// File ini mendefinisikan semua endpoint HTTP yang tersedia.
// Di lab ini kita menggunakan route closure (fungsi anonim)
// langsung di sini — tanpa Controller — agar mudah dipahami.
//
// Format: Route::METHOD('path', function() { return response; });
// ============================================================

use Illuminate\Support\Facades\Route;

// ------------------------------------------------------------
// GET /
// Homepage — menampilkan identitas Pod untuk lab Kubernetes
//
// Di Kubernetes, setiap Pod punya hostname dan IP unik.
// Endpoint ini memudahkan kita melihat ke Pod mana request diarahkan
// saat menggunakan Service (load balancing).
// ------------------------------------------------------------
Route::get('/', function () {
    $hostname = gethostname();

    return response()->json([
        'app'         => 'laravel-web',
        'version'     => 'v1',
        'environment' => 'kubernetes',
        // gethostname() di dalam Pod akan mengembalikan nama Pod
        'hostname'    => $hostname,
        // Resolusi hostname ke IP container
        'ip'          => gethostbyname($hostname),
        // Waktu saat ini dalam format ISO 8601
        'timestamp'   => now()->toISOString(),
    ]);
});

// ------------------------------------------------------------
// GET /health
// Health Check — digunakan oleh Kubernetes Liveness/Readiness Probe
//
// Kubernetes secara berkala memanggil endpoint ini untuk mengecek
// apakah Pod masih berjalan dengan baik.
// Jika mengembalikan HTTP 200, Pod dianggap sehat.
// ------------------------------------------------------------
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

// ------------------------------------------------------------
// GET /info
// Info Aplikasi — menampilkan detail environment dan versi
//
// Berguna untuk debugging: memastikan environment variable
// dari ConfigMap/Secret Kubernetes sudah terbaca dengan benar.
// ------------------------------------------------------------
Route::get('/info', function () {
    return response()->json([
        // Dari environment variable APP_NAME
        'app_name'        => env('APP_NAME'),
        // Dari environment variable APP_ENV (production/local)
        'app_env'         => env('APP_ENV'),
        // Hostname container/Pod
        'hostname'        => gethostname(),
        // Versi PHP yang berjalan di container
        'php_version'     => PHP_VERSION,
        // Versi framework Laravel yang digunakan
        'laravel_version' => app()->version(),
    ]);
});
