<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\ResetPasswordController;

Route::get('/', function () {
    return view('welcome');
})->middleware('allowIP');

Route::post('/log-baru', [LogsController::class, 'logBaru'])->middleware('allowIP');

Route::post('/get-log', [LogsController::class, 'getLog'])->middleware('allowIP');

Route::post('/get-logs', [LogsController::class, 'getLogs'])->middleware('allowIP');

Route::post('/presensi-masuk', [LogsController::class, 'masuk'])->middleware('allowIP');

Route::post('/presensi-istirahat', [LogsController::class, 'istirahat'])->middleware('allowIP');

Route::post('/presensi-kembali', [LogsController::class, 'kembali'])->middleware('allowIP');

Route::post('/presensi-pulang', [LogsController::class, 'pulang'])->middleware('allowIP');

Route::post('/log-activity', [LogsController::class, 'logActivity'])->middleware('allowIP');

Route::post('/kebaikan', [LogsController::class, 'kebaikan'])->middleware('allowIP');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/reset-password', [ResetPasswordController::class, 'generateToken']);

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'useToken']);

Route::post('/new-password', [ResetPasswordController::class, 'newPassword']);

Route::post('/login', [AuthController::class, 'login']); //->middleware('allowIP');

Route::get('/logout', [AuthController::class, 'logout']); //->middleware('allowIP');

Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::post('/admin/register', [AuthController::class, 'adminRegister']);

Route::get('/admin/dashboard', [AdminPageController::class, 'dashboard']);

Route::get('/admin/profile/{admin_id}', [AdminPageController::class, 'profile']);

Route::post('/admin/profile', [AdminPageController::class, 'postProfile']);

Route::post('/admin/presensi/harian', [AdminPageController::class, 'getPresensi']);

Route::post('/admin/detail-presensi', [AdminPageController::class, 'getDetailPresensi']);

Route::post('/admin/detail-presensi/catatan', [AdminPageController::class, 'postCatatan']);

Route::post('/admin/laporan', [AdminPageController::class, 'getLaporan']);

Route::post('/admin/divisi/aktif', [AdminPageController::class, 'getDivisions']);

Route::post('/admin/divisi', [AdminPageController::class, 'getDivisions']);

Route::get('/admin/divisi/belum-aktif', [AdminPageController::class, 'getBelumAktif']);

Route::post('/admin/divisi/anggota', [AdminPageController::class, 'getAnggotaDivisi']);

Route::post('/admin/divisi/status', [AdminPageController::class, 'postStatus']);

Route::get('/admin/all-team', [AdminPageController::class, 'getAllTeam']);

Route::get('/admin/detail-pemagang/{username}', [AdminPageController::class, 'getPemagang']);

Route::post('/admin/pemagang/nilai', [AdminPageController::class, 'postPenilaian']);

Route::post('/admin/detail-pemagang', [AdminPageController::class, 'postPemagang']);

Route::get('/admin/dashboard', [AdminPageController::class, 'dashboard']);

Route::get('admin/sekolah', [AdminPageController::class, 'groupBySekolah']);

Route::post('admin/sekolah', [AdminPageController::class, 'searchBySekolah']);

Route::get('admin/sekolah/pemagang/{kampus}', [AdminPageController::class, 'getPemagangByKampus']);

Route::get('admin/sekolah/pemagang/{kampus}/{nama}', [AdminPageController::class, 'getPemagangByKampusAndPemagang']);

Route::get('admin/shift', [AdminPageController::class, 'getShifts']);

Route::get('/shift/{shift_id}', [AdminPageController::class, 'getShift']);

Route::post('/log/edit', [LogsController::class, 'editLog']);

Route::post('/log/kehadiran', [LogsController::class, 'editKehadiran']);

Route::post('admin/shift', [AdminPageController::class, 'editShift']);

Route::post('admin/shift/add', [AdminPageController::class, 'addShift']);

Route::get('admin/shift/delete/{id}', [AdminPageController::class, 'deleteShift']);

Route::get('admin/manage-divisi', [AdminPageController::class, 'getDivisi']);

Route::post('admin/manage-divisi', [AdminPageController::class, 'editDivisi']);

Route::post('admin/manage-divisi/add', [AdminPageController::class, 'addDivisi']);

Route::get('admin/manage-divisi/delete/{id}', [AdminPageController::class, 'deleteDivisi']);

Route::get('admin/manage-divisi/get/{division_id}', [AdminPageController::class, 'getSpecificDivisi']);

Route::get('/admin/manage-penilaian-subkategori/{id}',
    [AdminPageController::class, 'deleteSubkategori']
)->middleware('allowIP');

Route::get('/admin/manage-penilaian/{division_id}', [AdminPageController::class, 'getPenilaian']);

Route::post('/admin/manage-penilaian-subkategori', [AdminPageController::class, 'addSubkategori']);

Route::get('/admin/manage-penilaian-kategori/{id}', [AdminPageController::class, 'getKategori']);

Route::post('/admin/manage-penilaian-kategori', [AdminPageController::class, 'addKategori']);

Route::get('/admin/manage-penilaian-kategori/{division_id}/{category_id}', [AdminPageController::class, 'deleteKategori']);

Route::get('/admin/penilaian/{username}', [AdminPageController::class, 'getPenilaianUser']);