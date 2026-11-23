<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/directory', [SiteController::class, 'directory'])->name('directory');
Route::get('/company/{company}', [SiteController::class, 'company'])->name('company');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap']);
Route::get('/robots.txt', [SiteController::class, 'robots']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'send'])->middleware('throttle:20,1')->name('login.send');
    Route::get('/login/code', [AuthController::class, 'codeForm'])->name('login.code');
    Route::post('/login/code', [AuthController::class, 'verify'])->middleware('throttle:20,1')->name('login.verify');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/companies/create', [DashboardController::class, 'create'])->name('companies.create');
    Route::post('/dashboard/companies', [DashboardController::class, 'store'])->name('companies.store');
    Route::get('/dashboard/companies/{company}/edit', [DashboardController::class, 'edit'])->name('companies.edit');
    Route::put('/dashboard/companies/{company}', [DashboardController::class, 'update'])->name('companies.update');
});
