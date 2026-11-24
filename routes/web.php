<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerJobController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/directory', [SiteController::class, 'directory'])->name('directory');
Route::get('/company/{company}', [SiteController::class, 'company'])->name('company');

// کاریابی و فرصت‌های شغلی عمومی
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job:slug}', [JobController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job:slug}/apply', [JobController::class, 'apply'])->name('jobs.apply');

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

    // مدیریت آگهی‌های استخدام و رزومه‌ها در پنل کارفرما
    Route::get('/dashboard/jobs/create', [EmployerJobController::class, 'create'])->name('employer.jobs.create');
    Route::post('/dashboard/jobs', [EmployerJobController::class, 'store'])->name('employer.jobs.store');
    Route::get('/dashboard/jobs/{job}/edit', [EmployerJobController::class, 'edit'])->name('employer.jobs.edit');
    Route::put('/dashboard/jobs/{job}', [EmployerJobController::class, 'update'])->name('employer.jobs.update');
    Route::post('/dashboard/jobs/{job}/toggle', [EmployerJobController::class, 'toggle'])->name('employer.jobs.toggle');
    Route::delete('/dashboard/jobs/{job}', [EmployerJobController::class, 'destroy'])->name('employer.jobs.destroy');
    Route::get('/dashboard/jobs/{job}/applications', [EmployerJobController::class, 'applications'])->name('employer.jobs.applications');
    Route::post('/dashboard/applications/{application}/status', [EmployerJobController::class, 'updateApplicationStatus'])->name('employer.applications.status');
});

// پنل مدیریت ارشد سیستم
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/companies', [\App\Http\Controllers\Admin\AdminController::class, 'companies'])->name('companies');
    Route::post('/companies/{company}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveCompany'])->name('companies.approve');
    Route::post('/companies/{company}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectCompany'])->name('companies.reject');
    Route::post('/companies/{company}/tier', [\App\Http\Controllers\Admin\AdminController::class, 'updateTier'])->name('companies.tier');

    Route::get('/jobs', [\App\Http\Controllers\Admin\AdminController::class, 'jobs'])->name('jobs');
    Route::post('/jobs/{job}/toggle', [\App\Http\Controllers\Admin\AdminController::class, 'toggleJob'])->name('jobs.toggle');
    Route::post('/jobs/{job}/immediate', [\App\Http\Controllers\Admin\AdminController::class, 'toggleImmediate'])->name('jobs.immediate');
    Route::delete('/jobs/{job}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyJob'])->name('jobs.destroy');

    Route::get('/applications', [\App\Http\Controllers\Admin\AdminController::class, 'applications'])->name('applications');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\AdminController::class, 'toggleUserAdmin'])->name('users.toggle-admin');
});
