<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\StatusController;

Route::post('/login', [LoginController::class, 'login']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/locations', [LocationController::class, 'index']);
Route::get('/types', [TypeController::class, 'index']);
Route::get('/status', [StatusController::class, 'index']);
Route::get('/properties/{property}', [PropertyController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('check-token', [LoginController::class, 'checkToken']);
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::put('/properties/{property}', [PropertyController::class, 'update']);
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy']);
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);
});

