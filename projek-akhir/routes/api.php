<?php

use App\Http\Controllers\Api\ProvinsiController;
use App\Http\Controllers\Api\UmpController;
use App\Http\Controllers\Api\UmpStatistikController;
use Illuminate\Support\Facades\Route;

// Endpoint Baca & Statistik (Publik / Guest dapat melihat data & grafik)
Route::get('/provinsi', [ProvinsiController::class, 'index']);
Route::get('/ump/statistik', [UmpStatistikController::class, 'statistik']);
Route::get('/ump/tren', [UmpStatistikController::class, 'tren']);
Route::get('/ump/perbandingan', [UmpStatistikController::class, 'perbandingan']);
Route::get('/ump', [UmpController::class, 'index']);
Route::get('/ump/{ump}', [UmpController::class, 'show']);

// Endpoint Tulis (Hanya Admin yang sudah login via Sanctum)
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/ump', [UmpController::class, 'store']);
    Route::put('/ump/{ump}', [UmpController::class, 'update']);
    Route::patch('/ump/{ump}', [UmpController::class, 'update']);
    Route::delete('/ump/{ump}', [UmpController::class, 'destroy']);
});