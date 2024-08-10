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

Route::post('/log-baru', [LogsController::class, 'logBaru'])->middleware('allowIP');

Route::post('/get-log', [LogsController::class, 'getLog'])->middleware('allowIP');

Route::post('/get-logs', [LogsController::class, 'getLogs'])->middleware('allowIP');

Route::post('/presensi-masuk', [LogsController::class, 'masuk'])->middleware('allowIP');

Route::post('/presensi-istirahat', [LogsController::class, 'istirahat'])->middleware('allowIP');

Route::post('/presensi-kembali', [LogsController::class, 'kembali'])->middleware('allowIP');

Route::post('/presensi-pulang', [LogsController::class, 'pulang'])->middleware('allowIP');

Route::post('/log-activity', [LogsController::class, 'logActivity'])->middleware('allowIP');

Route::post('/kebaikan', [LogsController::class, 'kebaikan'])->middleware('allowIP');

Route::post('/register', [AuthController::class, 'register'])->middleware('allowIP');

Route::post('/reset-password', [ResetPasswordController::class, 'generateToken'])->middleware('allowIP');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'useToken'])->middleware('allowIP');

Route::post('/login', [AuthController::class, 'login'])->middleware('allowIP');

Route::get('/logout', [AuthController::class, 'logout'])->middleware('allowIP');

Route::post('/admin/login', [AuthController::class, 'adminLogin'])->middleware('allowIP');

Route::post('/admin/register', [AuthController::class, 'adminRegister'])->middleware('allowIP');

Route::get('/admin/dashboard', [AdminPageController::class, 'dashboard'])->middleware('allowIP');

Route::get('/admin/profile/{admin_id}', [AdminPageController::class, 'profile'])->middleware('allowIP');

Route::post('/admin/profile', [AdminPageController::class, 'postProfile'])->middleware('allowIP');

Route::post('/admin/presensi/harian', [AdminPageController::class, 'getPresensi'])->middleware('allowIP');

Route::post('/admin/detail-presensi', [AdminPageController::class, 'getDetailPresensi'])->middleware('allowIP');

Route::post('/admin/detail-presensi/catatan', [AdminPageController::class, 'postCatatan'])->middleware('allowIP');

Route::post('/admin/laporan', [AdminPageController::class, 'getLaporan'])->middleware('allowIP');

Route::post('/admin/divisi/aktif', [AdminPageController::class, 'getDivisions'])->middleware('allowIP');

Route::post('/admin/divisi', [AdminPageController::class, 'getDivisions'])->middleware('allowIP');

Route::get('/admin/divisi/belum-aktif', [AdminPageController::class, 'getBelumAktif'])->middleware('allowIP');

Route::post('/admin/divisi/anggota', [AdminPageController::class, 'getAnggotaDivisi'])->middleware('allowIP');

Route::post('/admin/divisi/status', [AdminPageController::class, 'postStatus'])->middleware('allowIP');

Route::get('/admin/all-team', [AdminPageController::class, 'getAllTeam'])->middleware('allowIP');

Route::get('/admin/detail-pemagang/{username}', [AdminPageController::class, 'getPemagang'])->middleware('allowIP');

Route::post('/admin/pemagang/nilai', [AdminPageController::class, 'postPenilaian'])->middleware('allowIP');

Route::post('/admin/detail-pemagang', [AdminPageController::class, 'postPemagang'])->middleware('allowIP');

Route::get('/admin/dashboard', [AdminPageController::class, 'dashboard'])->middleware('allowIP');

Route::get('admin/sekolah', [AdminPageController::class, 'groupBySekolah'])->middleware('allowIP');

Route::post('admin/sekolah', [AdminPageController::class, 'searchBySekolah'])->middleware('allowIP');

Route::get('admin/sekolah/pemagang/{kampus}', [AdminPageController::class, 'getPemagangByKampus'])->middleware('allowIP');

Route::get('admin/sekolah/pemagang/{kampus}/{nama}', [AdminPageController::class, 'getPemagangByKampusAndPemagang'])->middleware('allowIP');

Route::get('admin/shift', [AdminPageController::class, 'getShifts'])->middleware('allowIP');

Route::get('/shift/{shift_id}', [AdminPageController::class, 'getShift'])->middleware('allowIP');

Route::post('/log/edit', [LogsController::class, 'editLog'])->middleware('allowIP');

Route::post('/log/kehadiran', [LogsController::class, 'editKehadiran'])->middleware('allowIP');

Route::post('admin/shift', [AdminPageController::class, 'editShift'])->middleware('allowIP');

Route::post('admin/shift/add', [AdminPageController::class, 'addShift'])->middleware('allowIP');

Route::get('admin/shift/delete/{id}', [AdminPageController::class, 'deleteShift'])->middleware('allowIP');

Route::get('admin/manage-divisi', [AdminPageController::class, 'getDivisi'])->middleware('allowIP');

Route::post('admin/manage-divisi', [AdminPageController::class, 'editDivisi'])->middleware('allowIP');

Route::post('admin/manage-divisi/add', [AdminPageController::class, 'addDivisi'])->middleware('allowIP');

Route::get('admin/manage-divisi/delete/{id}', [AdminPageController::class, 'deleteDivisi'])->middleware('allowIP');

Route::get('admin/manage-divisi/get/{division_id}', [AdminPageController::class, 'getSpecificDivisi'])->middleware('allowIP');

Route::get('/admin/manage-penilaian-subkategori/{id}',
    [AdminPageController::class, 'deleteSubkategori']
)->middleware('allowIP');

Route::get('/admin/manage-penilaian/{division_id}', [AdminPageController::class, 'getPenilaian'])->middleware('allowIP');

Route::post('/admin/manage-penilaian-subkategori', [AdminPageController::class, 'addSubkategori'])->middleware('allowIP');

Route::get('/admin/manage-penilaian-kategori/{id}', [AdminPageController::class, 'getKategori'])->middleware('allowIP');

Route::post('/admin/manage-penilaian-kategori', [AdminPageController::class, 'addKategori'])->middleware('allowIP');

Route::get('/admin/manage-penilaian-kategori/{division_id}/{category_id}', [AdminPageController::class, 'deleteKategori'])->middleware('allowIP');

Route::get('/admin/penilaian/{username}', [AdminPageController::class, 'getPenilaianUser'])->middleware('allowIP');