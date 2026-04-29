<?php

use App\Http\Controllers\Api\CarApiController;
use App\Http\Controllers\Api\ReservationApiController;
use Illuminate\Support\Facades\Route;

Route::get('/cars', [CarApiController::class, 'index']);
Route::post('/cars', [CarApiController::class, 'store']);
Route::put('/cars/{car}', [CarApiController::class, 'update']);
Route::delete('/cars/{car}', [CarApiController::class, 'destroy']);

Route::get('/reservations', [ReservationApiController::class, 'index']);
Route::get('/reservations/locations', [ReservationApiController::class, 'locations']);
Route::post('/reservations', [ReservationApiController::class, 'store']);
Route::get('/reservations/{reservation}', [ReservationApiController::class, 'show']);
Route::patch('/reservations/{reservation}', [ReservationApiController::class, 'update']);
Route::delete('/reservations/{reservation}', [ReservationApiController::class, 'destroy']);
