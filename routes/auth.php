<?php

use App\Http\Controllers\Api\v1\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*///////////////////////////////////////////
*
*           PUBLIC AUTH ROUTES (All Roles)
*           Endpoint: /api/auth/*
*
*/ //////////////////////////////////////////

Route::post('/register', [AdminAuthController::class, 'register']);
Route::post('/login', [AdminAuthController::class, 'login']);


/*///////////////////////////////////////////
*
*           PROTECTED AUTH ROUTES
*
*/ //////////////////////////////////////////
Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::post('/refresh-token', [AdminAuthController::class, 'refreshToken']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::get('/profile', [AdminAuthController::class, 'profile']);
});
