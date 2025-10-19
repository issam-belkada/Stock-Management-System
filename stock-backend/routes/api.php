<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);

    // ✅ Protect with permissions
    Route::post('users/create', [UserController::class, 'store'])
        ->middleware('permission:create_user');

    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:delete_user');



    // 📦 Product Routes
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('permission:view_products');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('permission:manage_products');

    Route::get('/products/{id}', [ProductController::class, 'show'])
        ->middleware('permission:view_products');

    Route::put('/products/{id}', [ProductController::class, 'update'])
        ->middleware('permission:manage_products');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])
        ->middleware('permission:manage_products');



    // ✅ Categories & Suppliers
    Route::apiResource('categories', CategoryController::class)
        ->middleware('permission:view_categories');

    Route::apiResource('suppliers', SupplierController::class)
        ->middleware('permission:view_suppliers');

    // ✅ Stock Movement
    Route::post('stock-in', [StockMovementController::class, 'stockIn'])
        ->middleware('permission:manage_stock');

    Route::post('stock-out', [StockMovementController::class, 'stockOut'])
        ->middleware('permission:manage_stock');


    // ✅ Notifications
    Route::get('notifications', [NotificationController::class, 'index'])
        ->middleware('permission:view_notifications');

    Route::put('notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->middleware('permission:view_notifications');

    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->middleware('permission:view_notifications');

    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])
        ->middleware('permission:manage_notifications');

    // ✅ Role Management
    Route::apiResource('roles', RoleController::class)
        ->middleware('permission:manage_roles');


    // 📊 Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('permission:view_dashboard');

});
