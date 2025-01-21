<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KaryawanBaruController;
use App\Models\KaryawanBaru;

Route::get('/', function () {
    return view('home');
});

// Route to Login and Register page
Auth::routes();

// Route to profile page
Route::get('/admin/profile', function () {
    return view('profile');
});

// Route to list calon karyawan page
// Route::get('/list-new-member', function () {
//     return view('list_new_member');
// });

// Route to list ambil foto page
Route::get('/list-take-photo', function () {
    return view('list_take_photo');
});

// Route to Home page
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route to Karyawan Baru page
// Route::get('/karyawan-baru/create', function () {
//     return view('list_new_member');
// })->name('karyawan-baru.create');

// Route to list photo page
Route::get('/photo', function () {
    return view('list_photo');
});

// Route to Karyawan page (CRUD karyawan)
Route::get('/karyawan', [KaryawanBaruController::class, 'index']);
Route::get('/api/karyawan/{id}', [KaryawanBaruController::class, 'show']);
Route::get('/api/karyawan', [KaryawanBaruController::class, 'getUsers'])->name('api.users');
Route::post('/api/karyawan/store', [KaryawanBaruController::class, 'store'])->name('karyawan-baru.store');
Route::post('/api/karyawan/update/{id}', [KaryawanBaruController::class, 'update'])->name('api.users.update');
Route::delete('/api/karyawan/delete/{id}', [KaryawanBaruController::class, 'destroy'])->name('api.users.delete');

// Route to Add Photo
Route::post('/api/karyawan/foto', [KaryawanBaruController::class, 'storeFoto'])->name('api.karyawan.foto.store');

// Route to list photo page
Route::get('/api/photo', [KaryawanBaruController::class, 'getPhotoList'])->name('api.photo');

Route::get('/karyawan/byDate', [KaryawanBaruController::class, 'getKaryawanByDate'])->name('api.karyawan.byDate');