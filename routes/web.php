<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token', function () {
    return csrf_token();
});

Route::post('/presensi-masuk', [LogsController::class, 'masuk']);

Route::post('/presensi-istirahat', [LogsController::class, 'istirahat']);

Route::post('/presensi-kembali', [LogsController::class, 'kembali']);

Route::post('/presensi-pulang', [LogsController::class, 'pulang']);

Route::post('/log-activity', [LogsController::class, 'logActivity']);

Route::post('/kebaikan', [LogsController::class, 'kebaikan']);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');