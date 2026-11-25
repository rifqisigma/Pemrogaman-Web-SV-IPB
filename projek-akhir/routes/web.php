<?php

use App\Http\Controllers\Admin\UmpSpreadsheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('admin/ump/spreadsheet')->name('admin.ump.spreadsheet')->group(function () {
    Route::get('/', [UmpSpreadsheetController::class, 'index']);
    Route::post('/import', [UmpSpreadsheetController::class, 'import'])->name('.import');
    Route::get('/export', [UmpSpreadsheetController::class, 'export'])->name('.export');
});