<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerJobController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/directory', [SiteController::class, 'directory'])->name('directory');
Route::get('/company/{role}/{category}/{company}', [SiteController::class, 'company'])->name('company');
Route::get('/company/{company}', [SiteController::class, 'legacyCompany'])->name('company.legacy');

// صفحات سئو شده دسته‌بندی‌ها و استان‌ها
Route::get('/categories', [SiteController::class, 'categoriesIndex'])->name('categories.index');
Route::get('/classification-of-suppliers-based-on-field-of-activity', fn() => redirect()->route('categories.index', [], 301));
Route::get('/category/{role}', [SiteController::class, 'categoryRole'])->name('categories.role');
Route::get('/category/{role}/{category}', [SiteController::class, 'categoryShow'])->name('categories.show');
Route::get('/category/{role}/{category}/{province}', [SiteController::class, 'categoryProvinceShow'])->name('categories.province');

Route::get('/locations', [SiteController::class, 'locationsIndex'])->name('locations.index');
Route::get('/classification-of-suppliers-based-on-location', fn() => redirect()->route('locations.index', [], 301));
Route::get('/location/{province}', [SiteController::class, 'locationShow'])->name('locations.show');

// تعرفه‌ها و تبلیغات
Route::get('/subscription-fee', [SiteController::class, 'subscriptionFee'])->name('subscription.fee');
Route::get('/pricing', fn() => redirect()->route('subscription.fee'));
Route::get('/advertising', [SiteController::class, 'advertising'])->name('advertising');

// صفحات اطلاع‌رسانی، تماس، قوانین و اپلیکیشن
Route::get('/cellulose-industry-essential-phone-numbers', [SiteController::class, 'phoneNumbers'])->name('pages.phone-numbers');
Route::get('/phone-numbers', fn() => redirect()->route('pages.phone-numbers', [], 301));

Route::get('/app', [SiteController::class, 'appDownload'])->name('pages.app');

Route::get('/faqs', [SiteController::class, 'faqs'])->name('pages.faqs');
Route::get('/faq', fn() => redirect()->route('pages.faqs', [], 301));

Route::get('/terms-and-conditions', [SiteController::class, 'terms'])->name('pages.terms');
Route::get('/terms', fn() => redirect()->route('pages.terms', [], 301));

Route::get('/privacy-policy', [SiteController::class, 'privacy'])->name('pages.privacy');
Route::get('/privacy', fn() => redirect()->route('pages.privacy', [], 301));

Route::get('/about-us', [SiteController::class, 'aboutUs'])->name('pages.about');
Route::get('/about', fn() => redirect()->route('pages.about', [], 301));

Route::get('/contact-us', [SiteController::class, 'contactUs'])->name('pages.contact');
Route::get('/contact', fn() => redirect()->route('pages.contact', [], 301));
Route::post('/contact-us', [SiteController::class, 'submitContact'])->name('pages.contact.submit');

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
    Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('dashboard.profile');
    Route::get('/dashboard/companies/create', [DashboardController::class, 'create'])->name('companies.create');
    Route::post('/dashboard/companies', [DashboardController::class, 'store'])->name('companies.store');
    Route::post('/companies/{company}/bookmark', [DashboardController::class, 'toggleBookmark'])->name('companies.bookmark');
    Route::get('/dashboard/companies/{company}/edit', [DashboardController::class, 'edit'])->name('companies.edit');
    Route::put('/dashboard/companies/{company}', [DashboardController::class, 'update'])->name('companies.update');
    Route::delete('/dashboard/companies/{company}', [DashboardController::class, 'destroy'])->name('companies.destroy');

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

Route::post('/track/company-click/{company}', [SiteController::class, 'trackCompanyClick'])->name('track.company.click');

// پنل مدیریت ارشد سیستم
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [\App\Http\Controllers\Admin\AdminController::class, 'analytics'])->middleware('admin:analytics')->name('analytics');
    Route::get('/logs', [\App\Http\Controllers\Admin\AdminController::class, 'logs'])->middleware('admin:logs')->name('logs');

    Route::middleware('admin:companies')->group(function () {
        Route::get('/companies', [\App\Http\Controllers\Admin\AdminController::class, 'companies'])->name('companies');
        Route::post('/companies/{company}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveCompany'])->name('companies.approve');
        Route::post('/companies/{company}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectCompany'])->name('companies.reject');
        Route::post('/companies/{company}/note', [\App\Http\Controllers\Admin\AdminController::class, 'updateCompanyNote'])->name('companies.note');
    });
    Route::post('/companies/{company}/tier', [\App\Http\Controllers\Admin\AdminController::class, 'updateTier'])->middleware('admin:companies_tier')->name('companies.tier');
    Route::delete('/companies/{company}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyCompany'])->middleware('admin:companies_delete')->name('companies.destroy');

    Route::middleware('admin:jobs')->group(function () {
        Route::get('/jobs', [\App\Http\Controllers\Admin\AdminController::class, 'jobs'])->name('jobs');
        Route::post('/jobs/{job}/toggle', [\App\Http\Controllers\Admin\AdminController::class, 'toggleJob'])->name('jobs.toggle');
        Route::post('/jobs/{job}/immediate', [\App\Http\Controllers\Admin\AdminController::class, 'toggleImmediate'])->name('jobs.immediate');
        Route::delete('/jobs/{job}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyJob'])->name('jobs.destroy');
    });

    Route::get('/applications', [\App\Http\Controllers\Admin\AdminController::class, 'applications'])->middleware('admin:applications')->name('applications');

    Route::middleware('admin:users')->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\AdminController::class, 'toggleUserAdmin'])->name('users.toggle-admin');
    });

    Route::middleware('admin:categories')->group(function () {
        Route::get('/categories', [\App\Http\Controllers\Admin\AdminController::class, 'categories'])->name('categories');
        Route::get('/categories/{category}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\AdminController::class, 'updateCategory'])->name('categories.update');
    });
});
