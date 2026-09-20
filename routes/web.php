<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ──────────────── CUSTOMER ROUTES ────────────────
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

        // My Properties (allocations list)
        Route::get('/properties', [\App\Http\Controllers\Customer\PropertyController::class, 'index'])->name('properties.index');
        Route::get('/properties/{allocation}', [\App\Http\Controllers\Customer\PropertyController::class, 'show'])->name('properties.show');

        // Interactive Estate Map System (CORE FEATURE)
        Route::get('/estates', [\App\Http\Controllers\Customer\EstateMapController::class, 'index'])->name('estates.index');
        Route::get('/estates/{estate}', [\App\Http\Controllers\Customer\EstateMapController::class, 'show'])->name('estates.show');
        Route::get('/estates/{estate}/map-data', [\App\Http\Controllers\Customer\EstateMapController::class, 'mapData'])->name('estates.map-data');
        Route::get('/estates/{estate}/plots/{plot}', [\App\Http\Controllers\Customer\EstateMapController::class, 'plotDetail'])->name('estates.plot-detail');

        // Legacy map route (redirect)
        Route::get('/map', [\App\Http\Controllers\Customer\EstateMapController::class, 'index'])->name('map.index');

        Route::get('/documents', [\App\Http\Controllers\Customer\DocumentController::class, 'index'])->name('documents.index');
        Route::get('/payments/{allocation}', [\App\Http\Controllers\Customer\PaymentController::class, 'create'])->name('payments.create');
        Route::post('/payments/{allocation}', [\App\Http\Controllers\Customer\PaymentController::class, 'store'])->name('payments.store');

        Route::get('/support', [\App\Http\Controllers\Customer\SupportController::class, 'index'])->name('support.index');
        Route::post('/support', [\App\Http\Controllers\Customer\SupportController::class, 'store'])->name('support.store');

        Route::get('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/notifications', [\App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    });

    // ──────────────── ADMIN ROUTES ────────────────
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('estates', \App\Http\Controllers\Admin\EstateController::class);
        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
        Route::resource('allocations', \App\Http\Controllers\Admin\PlotAllocationController::class);
        Route::resource('plots', \App\Http\Controllers\Admin\PlotController::class);

        // Layout Management
        Route::get('layouts', [\App\Http\Controllers\Admin\LayoutController::class, 'index'])->name('layouts.index');
        Route::get('layouts/{estate}/edit', [\App\Http\Controllers\Admin\LayoutController::class, 'edit'])->name('layouts.edit');
        Route::put('layouts/{estate}', [\App\Http\Controllers\Admin\LayoutController::class, 'update'])->name('layouts.update');
        Route::get('layouts/{estate}/map-data', [\App\Http\Controllers\Admin\LayoutController::class, 'mapData'])->name('layouts.map-data');
        Route::post('layouts/{estate}/upload', [\App\Http\Controllers\Admin\LayoutController::class, 'uploadBlueprint'])->name('layouts.upload');
        Route::post('layouts/{estate}/plots', [\App\Http\Controllers\Admin\LayoutController::class, 'savePlot'])->name('layouts.plots.save');
        Route::put('layouts/{estate}/plots/{plot}', [\App\Http\Controllers\Admin\LayoutController::class, 'updatePlot'])->name('layouts.plots.update');
        Route::delete('layouts/{estate}/plots/{plot}', [\App\Http\Controllers\Admin\LayoutController::class, 'deletePlot'])->name('layouts.plots.delete');

        Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class);
        Route::resource('notifications', \App\Http\Controllers\Admin\NotificationController::class);
        Route::view('/settings', 'admin.settings.index')->name('settings.index');

        Route::get('/activity-logs', [\App\Http\Controllers\Admin\DashboardController::class, 'activityLogs'])->name('activity-logs.index');
    });
});
