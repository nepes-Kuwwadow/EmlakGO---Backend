<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\PropertyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - EmläkGO
|--------------------------------------------------------------------------
|
| Ähli API ýollary şu ýerde kesgitlenýär.
| URL: /api/...
|
*/

// Auth - ygtyýarnamasyz açyk uçlar
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Açyk (ygtyýarnama talap etmeýän) uçlar
Route::get('/categories',                  [CategoryController::class, 'index']);
Route::get('/properties',                  [PropertyController::class, 'index']);
Route::get('/properties/{property}',       [PropertyController::class, 'show']);
Route::post('/properties/{property}/inquiries', [InquiryController::class, 'store']);

// Kommentariýalar
Route::get('/properties/{property}/comments',  [CommentController::class, 'index']);
Route::post('/properties/{property}/comments', [CommentController::class, 'store']);

// Ygtyýarnamaly uçlar (Bearer token gerek)
Route::middleware('api.auth')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // Emläk dolandyrmak
    // 2nji commit
    Route::post('/properties',              [PropertyController::class, 'store']);
    Route::put('/properties/{property}',    [PropertyController::class, 'update']);
    Route::patch('/properties/{property}',  [PropertyController::class, 'update']);
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy']);

    // Halanlar
    Route::get('/favorites',                             [FavoriteController::class, 'index']);
    Route::post('/properties/{property}/favorite',       [FavoriteController::class, 'toggle']);

    // Kommentariýa pozmak
    Route::delete('/comments/{comment}',                 [CommentController::class, 'destroy']);
});
