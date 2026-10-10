<?php

use Illuminate\Support\Facades\Route;

// Semua alamat selain /api/... ditampilkan oleh aplikasi React
Route::get('/{any?}', function () {
    return response()->file(public_path('index.html'));
})->where('any', '^(?!api/).*$');