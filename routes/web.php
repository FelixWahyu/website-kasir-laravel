<?php

use App\Http\Controllers\Admin\AiChatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginSessionController;
use App\Http\Controllers\Kasir\DashboardKasirController;
use App\Http\Controllers\Kasir\TransactionStoreController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard' : 'kasir.dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginSessionController::class, 'create'])->name('login');
    Route::post('/login', [LoginSessionController::class, 'store']);

    Route::get('/password/reset', [PasswordController::class, 'forgotPassword'])->name('password.request');
    Route::post('/password/email', [PasswordController::class, 'sendResetEmail'])->name('send.email');
    Route::get('/password/reset/{token}', [PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [LoginSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'roles:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('/admin/categories', CategoryController::class)->except(['show']);

    Route::resource('/admin/products', ProductController::class);

    Route::resource('discounts', DiscountController::class);
    Route::put('discounts/{discount}/toggle', [DiscountController::class, 'toggleStatus'])->name('discounts.toggle');

    Route::get('/admin/reports', [ReportController::class, 'reportIndex'])->name('admin.reports');
    Route::get('/admin/reports/pdf', [ReportController::class, 'reportPdf'])->name('admin.reports.pdf');

    Route::get('/admin/settings', [SettingController::class, 'edit'])->name('admin.settings');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/admin/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/admin/users/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'delete'])->name('users.delete');

    Route::post('/admin/ai', [AiChatController::class, 'handleQuery']);
});

Route::middleware(['auth', 'roles:kasir'])->group(function () {
    Route::get('/kasir/dashboard', [DashboardKasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/kasir/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/kasir/transactions/store', [TransactionStoreController::class, 'store'])->name('transaction.store');
    Route::get('/receipt/{transaction}', [PosController::class, 'receipt'])->name('receipt.print');
});

Route::middleware('auth')->group(function () {
    Route::resource('/customers', CustomerController::class)->except(['show']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
