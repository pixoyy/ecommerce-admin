<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorizationController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.login'));

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest.admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,15');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', fn () => view('admin.dashboard'))->name('dashboard');

        $placeholderRoutes = [
            'products' => 'Produk',
            'product-variants' => 'Varian Produk',
            'warehouses' => 'Gudang',
            'stocks' => 'Stok',
            'promotions' => 'Promosi',
            'payment-accounts' => 'Akun Pembayaran',
            'payments' => 'Konfirmasi Pembayaran',
            'orders' => 'Pesanan',
            'shipments' => 'Pengiriman',
            'point-transactions' => 'Poin Reward',
            'reviews' => 'Ulasan',
            'file-storages' => 'File Storage',
        ];

        foreach ($placeholderRoutes as $uri => $title) {
            Route::view($uri, 'admin.placeholder', ['pageTitle' => $title])->name($uri);
        }

        Route::middleware('superadmin')->group(function () {
            Route::resource('roles', RoleController::class)->except('show');

            Route::prefix('roles/{role}/authorizations')->name('roles.authorizations.')->group(function () {
                Route::get('/', [AuthorizationController::class, 'edit'])->name('edit');
                Route::put('/', [AuthorizationController::class, 'update'])->name('update');
            });

            Route::resource('admins', AdminController::class)->except('show');

            Route::prefix('admins/{admin}/password')->name('admins.password.')->group(function () {
                Route::get('/', [AdminController::class, 'editPassword'])->name('edit');
                Route::put('/', [AdminController::class, 'updatePassword'])->name('update');
            });

            Route::resource('categories', CategoryController::class)->except('show');
            Route::resource('brands', BrandController::class)->except('show');
            Route::resource('users', UserController::class);
        });
    });
});
