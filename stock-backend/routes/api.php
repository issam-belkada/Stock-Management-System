<?php

use App\Http\Controllers\ProfileController;
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
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SaleController;


Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('profile', [ProfileController::class, 'update']);


    // Users
    Route::get('users', [UserController::class, 'index'])
        ->middleware('permission:view_users');
    Route::get('users/{id}', [UserController::class, 'show'])
        ->middleware('permission:view_users');
    Route::post('users', [UserController::class, 'store'])
        ->middleware('permission:manage_users');
    Route::put('users/{id}', [UserController::class, 'update'])
        ->middleware('permission:manage_users');
    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:manage_users');




    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('permission:view_products');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('permission:create_product');

    Route::get('/products/{id}', [ProductController::class, 'show'])
        ->middleware('permission:view_products');

    Route::put('/products/{id}', [ProductController::class, 'update'])
        ->middleware('permission:edit_product');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])
        ->middleware('permission:delete_product');



    // Categories & Suppliers

    Route::get('categories', [CategoryController::class, 'index'])
        ->middleware('permission:view_categories');

    Route::get('categories/{id}', [CategoryController::class, 'show'])
        ->middleware('permission:view_categories');
    
    Route::post('categories', [CategoryController::class, 'store'])
        ->middleware('permission:manage_categories');
    
    Route::put('categories/{id}', [CategoryController::class, 'update'])
        ->middleware('permission:manage_categories');
    
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])
    ->middleware('permission:manage_categories');

    Route::apiResource('suppliers', SupplierController::class)
    ->middleware('permission:manage_suppliers');

    // Stock Movement
    Route::post('stock-in', [StockMovementController::class, 'stockIn'])
        ->middleware('permission:manage_stock');

    Route::post('stock-out', [StockMovementController::class, 'stockOut'])
        ->middleware('permission:manage_stock');


    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])
        ->middleware('permission:view_notifications');

    Route::put('notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->middleware('permission:view_notifications');

    Route::put('notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->middleware('permission:view_notifications');

    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])
        ->middleware('permission:manage_notifications');

    // Role Management
    Route::apiResource('roles', RoleController::class)
        ->middleware('permission:manage_roles');

    // Permission Management
    Route::apiResource('permissions', PermissionController::class)
    ->middleware('permission:manage_permissions');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware('permission:view_dashboard');


    // Reports
    Route::get('reports/stock', [ReportController::class, 'stockReport'])->middleware('permission:view_reports');
    Route::get('reports/top-products', [ReportController::class, 'topProducts'])->middleware('permission:view_reports');
    Route::get('reports/export', [ReportController::class, 'exportCSV'])->middleware('permission:export_reports');




    Route::post('sales', [SaleController::class, 'store'])->middleware('permission:make_sales');
    Route::get('sales', [SaleController::class, 'index'])->middleware('permission:view_sales');
    Route::get('sales/{id}', [SaleController::class, 'show'])->middleware('permission:view_sales');

});
