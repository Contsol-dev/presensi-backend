<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [UserController::class, 'getUsers']);

Route::post('/log-baru', [LogsController::class, 'logBaru']);

Route::post('/get-log', [LogsController::class, 'getLog']);

Route::post('/presensi-masuk', [LogsController::class, 'masuk']);

Route::post('/presensi-istirahat', [LogsController::class, 'istirahat']);

Route::post('/presensi-kembali', [LogsController::class, 'kembali']);

Route::post('/presensi-pulang', [LogsController::class, 'pulang']);

Route::post('/log-activity', [LogsController::class, 'logActivity']);

Route::post('/kebaikan', [LogsController::class, 'kebaikan']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/reset-password', [ResetPasswordController::class, 'generateToken']);

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'useToken']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/admin/dashboard', [AdminPageController::class, 'dashboard']);