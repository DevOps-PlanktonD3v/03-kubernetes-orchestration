<?php

// ============================================================
// public/index.php - Entry Point Aplikasi
//
// Semua request HTTP yang masuk ke aplikasi Laravel
// diarahkan ke file ini oleh Apache melalui .htaccess.
//
// File ini bertugas:
// 1. Mengecek mode maintenance
// 2. Memuat Composer autoloader
// 3. Bootstrap aplikasi Laravel dan menangani request
// ============================================================

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Cek apakah aplikasi dalam mode maintenance
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Muat Composer autoloader (hasil dari `composer install`)
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel dan tangani HTTP request
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
