<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UmpSpreadsheetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatasheetController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScientificReportController;
use App\Http\Controllers\ServiceApiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/home-data', [HomeController::class, 'apiData'])->name('home.api');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/sign-up', [AuthController::class, 'showRegister'])->name('register');
Route::get('/sign up', fn () => redirect()->route('register'));
Route::get('/register', fn () => redirect()->route('register'));
Route::post('/sign-up', [AuthController::class, 'register'])->name('register.post');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/update-wage', [UserController::class, 'updateWage'])->name('user.update-wage');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
    Route::delete('/wage-logs/{log}', [AdminDashboardController::class, 'destroyLog'])->name('wage-logs.destroy');

    Route::prefix('ump/spreadsheet')->name('ump.spreadsheet')->group(function () {
        Route::get('/', [UmpSpreadsheetController::class, 'index']);
        Route::post('/import', [UmpSpreadsheetController::class, 'import'])->name('.import');
        Route::get('/export', [UmpSpreadsheetController::class, 'export'])->name('.export');
    });
});

Route::get('/scientific-report', [ScientificReportController::class, 'index'])->name('scientific-report.index');
Route::get('/scientific-report/download', [ScientificReportController::class, 'downloadPdf'])->name('scientific-report.pdf');

Route::get('/datasheet', [DatasheetController::class, 'index'])->name('datasheet.index');
Route::get('/datasheet/export', [DatasheetController::class, 'export'])->name('datasheet.export');

Route::get('/service-api', [ServiceApiController::class, 'index'])->name('service-api.index');
Route::get('/api/service', [ServiceApiController::class, 'getFlexibleData'])->name('service-api.data');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
