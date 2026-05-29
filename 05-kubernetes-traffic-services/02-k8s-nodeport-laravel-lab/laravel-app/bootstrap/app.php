<?php

// ============================================================
// bootstrap/app.php - Titik masuk utama Laravel 12
//
// File ini menginisialisasi aplikasi Laravel dan mendaftarkan:
// - File routing (routes/web.php)
// - Middleware
// - Exception handler
//
// Berbeda dengan Laravel 10 ke bawah, di Laravel 12 tidak ada
// lagi file app/Http/Kernel.php. Semua konfigurasi ada di sini.
// ============================================================

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // File route untuk request HTTP
        web: __DIR__.'/../routes/web.php',
        // File route untuk perintah Artisan CLI
        commands: __DIR__.'/../routes/console.php',
        // Endpoint health check bawaan Laravel (GET /up)
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Tambahkan middleware custom di sini jika dibutuhkan
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi exception handler di sini
    })->create();
