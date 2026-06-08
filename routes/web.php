<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/payment', [ProfileController::class, 'updatePayment'])->name('profile.update-payment');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/apply-driver', [ProfileController::class, 'applyDriver'])->name('profile.apply-driver');
});

// User Routes — Pickup Request (with AI Scanner), Redeem Center
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/pickup', [UserController::class, 'pickupRequest'])->name('pickup');
    Route::post('/pickup', [UserController::class, 'storePickupRequest'])->name('pickup.store');
    Route::get('/redeem', [UserController::class, 'redeem'])->name('redeem');
    Route::post('/redeem', [UserController::class, 'storeRedeem'])->name('redeem.store');
});

// Driver Routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::post('/status', [DriverController::class, 'updateStatus'])->name('status.update');
    Route::get('/orders', [DriverController::class, 'orderPool'])->name('orders');
    Route::get('/preview/{pickup}', [DriverController::class, 'preview'])->name('preview');
    Route::post('/orders/{pickup}/accept', [DriverController::class, 'acceptOrder'])->name('orders.accept');
    Route::get('/navigation/{pickup}', [DriverController::class, 'navigation'])->name('navigation');
    Route::get('/verify/{pickup}', [DriverController::class, 'verifyWeight'])->name('verify');
    Route::post('/verify/{pickup}', [DriverController::class, 'storeWeight'])->name('verify.store');
    Route::get('/redeem', [DriverController::class, 'redeem'])->name('redeem');
    Route::post('/redeem', [DriverController::class, 'storeRedeem'])->name('redeem.store');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    Route::post('/drivers', [AdminController::class, 'storeDriver'])->name('drivers.store');
    Route::post('/drivers/{application}/approve', [AdminController::class, 'approveDriverApplication'])->name('drivers.approve');
    Route::post('/drivers/{application}/reject', [AdminController::class, 'rejectDriverApplication'])->name('drivers.reject');
    Route::get('/pricing', [AdminController::class, 'pricing'])->name('pricing');
    Route::post('/pricing/{category}', [AdminController::class, 'updatePricing'])->name('pricing.update');
    Route::get('/payouts', [AdminController::class, 'payouts'])->name('payouts');
    Route::post('/payouts/{redemption}/approve', [AdminController::class, 'approvePayout'])->name('payouts.approve');
    Route::post('/payouts/{redemption}/reject', [AdminController::class, 'rejectPayout'])->name('payouts.reject');
});

require __DIR__ . '/auth.php';

// Language Switcher Route
Route::get('/lang/{locale}', [\App\Http\Controllers\LocaleController::class, 'switch'])->name('lang.switch');
