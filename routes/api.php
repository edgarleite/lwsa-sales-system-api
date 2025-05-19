<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;

Route::prefix('v1')->group(function () {
    // Authentication (não cacheadas)
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    
    // Authenticated routes
    Route::middleware('jwt.verify')->group(function () {
        // Auth endpoints (não cacheados)
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        
        // Rotas GET com cache (5 horas de cache = 300 minutos)
        Route::middleware('cache.response:300')->group(function () {
            // Sellers
            Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
            Route::get('sellers/{seller}', [SellerController::class, 'show'])->name('sellers.show');
            Route::get('sellers/{seller}/sales', [SellerController::class, 'sales'])->name('sellers.sales');
            
            // Sales
            Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
            Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
            
            // Users
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        });

        // Rotas POST/PUT/DELETE para Sellers (sem cache)
        Route::post('sellers', [SellerController::class, 'store'])->name('sellers.store');
        Route::put('sellers/{seller}', [SellerController::class, 'update'])->name('sellers.update');
        Route::delete('sellers/{seller}', [SellerController::class, 'destroy'])->name('sellers.destroy');
        
        // Rotas POST/PUT/DELETE para Sales (sem cache)
        Route::post('sales', [SaleController::class, 'store'])->name('sales.store');
        Route::put('sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
        Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        
        // Rotas POST/PUT/DELETE para Users (sem cache)
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        // Reports (não cacheados)
        Route::post('reports/send-daily', [ReportController::class, 'sendDailyReports'])->name('reports.send-daily');
        Route::post('reports/resend/{seller}', [ReportController::class, 'resendReport'])->name('reports.resend');
    });
});