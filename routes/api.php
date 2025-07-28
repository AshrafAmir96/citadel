<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ApiDocumentationController;

// API Documentation
Route::get('/', [ApiDocumentationController::class, 'index']);

// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

// User routes
Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    
    // User management
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::post('users/{id}/roles', [UserController::class, 'assignRole']);
    Route::get('users/{id}/permissions', [UserController::class, 'permissions']);
    
    // Media management
    Route::get('media', [MediaController::class, 'index']);
    Route::post('media', [MediaController::class, 'store']);
    Route::delete('media/{id}', [MediaController::class, 'destroy']);
    
    // Search
    Route::get('search', [SearchController::class, 'search']);
});
