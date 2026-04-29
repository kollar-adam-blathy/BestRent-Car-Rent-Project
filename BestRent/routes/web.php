<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/cars', [HomeController::class, 'cars']);
Route::get('/cars/{car}', [HomeController::class, 'showCar']);

Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/client/profile', [HomeController::class, 'profile']);
    Route::patch('/client/profile', [HomeController::class, 'updateProfile']);
    Route::delete('/client/profile', [HomeController::class, 'destroyProfile']);
    Route::get('/client/dashboard', [HomeController::class, 'clientDashboard']);
    Route::get('/client/reserve', [HomeController::class, 'reserveForm']);
    Route::get('/client/reservations/{reservation}', [HomeController::class, 'reservationShow']);

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show']);
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update']);

    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{payment}', [PaymentController::class, 'show']);
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay']);
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'adminDashboard']);

    Route::get('/cars/create', [CarController::class, 'create']);
    Route::post('/cars', [CarController::class, 'store']);
    Route::get('/cars/{car}/edit', [CarController::class, 'edit']);
    Route::patch('/cars/{car}', [CarController::class, 'update']);
    Route::delete('/cars/{car}', [CarController::class, 'destroy']);

    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy']);

    Route::get('/payments', [PaymentController::class, 'index']);
    Route::patch('/payments/{payment}', [PaymentController::class, 'update']);
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);
});
