<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AuthorizationController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FileStorageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentAccountController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\PromotionItemController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('admin.login'));

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest.admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,15');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        $placeholderRoutes = [];

        foreach ($placeholderRoutes as $uri => $title) {
            Route::view($uri, 'admin.placeholder', ['pageTitle' => $title])->name($uri);
        }

        Route::controller(ProductController::class)->prefix('products')->name('products.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{product}', 'show')->name('show');
            Route::get('{product}/edit', 'edit')->name('edit');
            Route::put('{product}', 'update')->name('update');
            Route::delete('{product}', 'destroy')->name('destroy');
        });

        Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::delete('products/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');

        Route::controller(ProductVariantController::class)->prefix('products/{product}/variants')->name('products.variants.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{variant}', 'show')->name('show');
            Route::get('{variant}/edit', 'edit')->name('edit');
            Route::put('{variant}', 'update')->name('update');
            Route::delete('{variant}', 'destroy')->name('destroy');
        });

        Route::controller(WarehouseController::class)->prefix('warehouses')->name('warehouses.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{warehouse}/edit', 'edit')->name('edit');
            Route::put('{warehouse}', 'update')->name('update');
            Route::delete('{warehouse}', 'destroy')->name('destroy');
        });

        Route::controller(StockController::class)->prefix('stocks')->name('stocks.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        Route::middleware('superadmin')->group(function () {
            Route::controller(PromotionController::class)->prefix('promotions')->name('promotions.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{promotion}', 'show')->name('show');
                Route::get('{promotion}/edit', 'edit')->name('edit');
                Route::put('{promotion}', 'update')->name('update');
                Route::delete('{promotion}', 'destroy')->name('destroy');
            });

            Route::post('promotions/{promotion}/items', [PromotionItemController::class, 'store'])->name('promotions.items.store');
            Route::delete('promotions/items/{item}', [PromotionItemController::class, 'destroy'])->name('promotions.items.destroy');

            Route::controller(PaymentAccountController::class)->prefix('payment-accounts')->name('payment-accounts.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{paymentAccount}/edit', 'edit')->name('edit');
                Route::put('{paymentAccount}', 'update')->name('update');
                Route::delete('{paymentAccount}', 'destroy')->name('destroy');
            });

            Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{order}', 'show')->name('show');
            });

            Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

            Route::controller(PaymentController::class)->prefix('payments')->name('payments.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{payment}', 'show')->name('show');
            });

            Route::post('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
            Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

            Route::get('shipments', [ShipmentController::class, 'index'])->name('shipments.index');
            Route::get('orders/{order}/shipments/create', [ShipmentController::class, 'create'])->name('orders.shipments.create');
            Route::post('orders/{order}/shipments', [ShipmentController::class, 'store'])->name('orders.shipments.store');
            Route::get('shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
            Route::put('shipments/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');

            Route::controller(PointController::class)->prefix('point-transactions')->name('point-transactions.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{user}', 'show')->name('show');
            });

            Route::get('file-storages', [FileStorageController::class, 'index'])->name('file-storages.index');

            Route::controller(ReviewController::class)->prefix('reviews')->name('reviews.')->group(function () {
                Route::get('/', 'index')->name('index');
            });

            Route::post('reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');

            Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{role}/edit', 'edit')->name('edit');
                Route::put('{role}', 'update')->name('update');
                Route::delete('{role}', 'destroy')->name('destroy');
            });

            Route::prefix('roles/{role}/authorizations')->name('roles.authorizations.')->group(function () {
                Route::get('/', [AuthorizationController::class, 'edit'])->name('edit');
                Route::put('/', [AuthorizationController::class, 'update'])->name('update');
            });

            Route::controller(AdminController::class)->prefix('admins')->name('admins.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{admin}/edit', 'edit')->name('edit');
                Route::put('{admin}', 'update')->name('update');
                Route::delete('{admin}', 'destroy')->name('destroy');
            });

            Route::prefix('admins/{admin}/password')->name('admins.password.')->group(function () {
                Route::get('/', [AdminController::class, 'editPassword'])->name('edit');
                Route::put('/', [AdminController::class, 'updatePassword'])->name('update');
            });

            Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{category}/edit', 'edit')->name('edit');
                Route::put('{category}', 'update')->name('update');
                Route::delete('{category}', 'destroy')->name('destroy');
            });

            Route::controller(BrandController::class)->prefix('brands')->name('brands.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{brand}/edit', 'edit')->name('edit');
                Route::put('{brand}', 'update')->name('update');
                Route::delete('{brand}', 'destroy')->name('destroy');
            });

            Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{user}', 'show')->name('show');
                Route::get('{user}/edit', 'edit')->name('edit');
                Route::put('{user}', 'update')->name('update');
                Route::delete('{user}', 'destroy')->name('destroy');
            });
        });
    });
});
