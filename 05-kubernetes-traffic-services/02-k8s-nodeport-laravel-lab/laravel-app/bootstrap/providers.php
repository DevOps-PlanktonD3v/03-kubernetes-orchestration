<?php

// ============================================================
// bootstrap/providers.php - Daftar Service Provider
//
// Service Provider adalah kelas yang "mendaftarkan" fitur
// ke dalam container aplikasi Laravel.
//
// Di Laravel 12, daftar provider dipindahkan dari config/app.php
// ke file ini agar lebih terorganisir.
// ============================================================

return [
    App\Providers\AppServiceProvider::class,
];
