<?php

use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TourController as AdminTourController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\TourController as CompanyTourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tours/{tour:slug}', [HomeController::class, 'show'])->name('tours.show');
Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Company area
|--------------------------------------------------------------------------
*/
Route::prefix('company')->name('company.')->group(function () {
    // Guest
    Route::middleware('guest')->group(function () {
        Route::get('register', [CompanyAuthController::class, 'showRegister'])->name('register');
        Route::post('register', [CompanyAuthController::class, 'register']);
        Route::get('login', [CompanyAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [CompanyAuthController::class, 'login']);
    });

    // Authenticated company (role middleware also redirects guests to the company login)
    Route::middleware('role:company')->group(function () {
        Route::post('logout', [CompanyAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

        // Listing own tours is always allowed; managing them requires approval.
        Route::get('tours', [CompanyTourController::class, 'index'])->name('tours.index');

        Route::middleware('company.approved')->group(function () {
            Route::get('tours/create', [CompanyTourController::class, 'create'])->name('tours.create');
            Route::post('tours', [CompanyTourController::class, 'store'])->name('tours.store');
            Route::get('tours/{tour}/edit', [CompanyTourController::class, 'edit'])->name('tours.edit');
            Route::put('tours/{tour}', [CompanyTourController::class, 'update'])->name('tours.update');
            Route::delete('tours/{tour}', [CompanyTourController::class, 'destroy'])->name('tours.destroy');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Admin area
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('tours', [AdminTourController::class, 'index'])->name('tours.index');
        Route::get('tours/{tour}', [AdminTourController::class, 'show'])->name('tours.show');
        Route::post('tours/{tour}/approve', [AdminTourController::class, 'approve'])->name('tours.approve');
        Route::post('tours/{tour}/reject', [AdminTourController::class, 'reject'])->name('tours.reject');

        Route::get('companies', [AdminCompanyController::class, 'index'])->name('companies.index');
        Route::post('companies/{company}/approve', [AdminCompanyController::class, 'approve'])->name('companies.approve');
        Route::post('companies/{company}/reject', [AdminCompanyController::class, 'reject'])->name('companies.reject');
    });
});
