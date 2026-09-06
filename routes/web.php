<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CashierRefundController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnerReportExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerController::class, 'menu'])->name('menu');
Route::get('/health', HealthController::class)->middleware('throttle:30,1')->name('health');
Route::post('/checkout', [CustomerController::class, 'checkout'])->middleware('throttle:10,1')->name('checkout');
Route::get('/order/{token}', [CustomerController::class, 'track'])->name('orders.track');
Route::get('/order/{token}/status', [CustomerController::class, 'status'])->middleware('throttle:60,1')->name('orders.status');
Route::post('/order/{token}/pay', [CustomerController::class, 'pay'])->middleware('throttle:10,1')->name('orders.pay');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::middleware('role:KITCHEN,ADMIN')->prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('index');
        Route::post('/claim-next', [KitchenController::class, 'claim'])->name('claim');
        Route::post('/queue/{queue}/ready', [KitchenController::class, 'ready'])->name('ready');
    });
    Route::middleware('role:CASHIER,ADMIN')->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::post('/orders', [CashierController::class, 'store'])->name('orders.store');
        Route::post('/payments/{payment}/verify', [CashierController::class, 'verify'])->name('payments.verify');
        Route::post('/payments/{payment}/refund', CashierRefundController::class)->name('payments.refund');
        Route::post('/queue/{queue}/complete', [CashierController::class, 'complete'])->name('complete');
        Route::post('/orders/{order}/priority', [CashierController::class, 'priority'])->name('priority');
        Route::post('/orders/{order}/cancel', [CashierController::class, 'cancel'])->name('cancel');
    });
    Route::middleware('role:ADMIN')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/audit-logs', AuditLogController::class)->name('audit-logs.index');
        Route::get('/operations', OperationsController::class)->name('operations.index');
        Route::resource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
        Route::resource('menus', MenuController::class)->only(['store', 'update', 'destroy']);
        Route::resource('users', UserController::class)->only(['store', 'update', 'destroy']);
        Route::patch('/menus/{menu}/availability', [AdminController::class, 'availability'])->name('menus.availability');
        Route::post('/settings', [AdminController::class, 'settings'])->name('settings');
    });
    Route::middleware('role:OWNER,ADMIN')->get('/owner', OwnerController::class)->name('owner.index');
    Route::middleware('role:OWNER,ADMIN')->get('/owner/export', OwnerReportExportController::class)->name('owner.export');
});
