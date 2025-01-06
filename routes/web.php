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

Route::get('/karyawan-baru/create', function () {
    return view('form_karyawan_baru');
})->name('karyawan-baru.create');;

Route::post('/karyawan-baru/create', [KaryawanBaruController::class, 'store'])->name('karyawan-baru.store');

Route::get('/karyawan', [KaryawanBaruController::class, 'index']);
Route::get('/api/karyawan', [KaryawanBaruController::class, 'getUsers'])->name('api.users');
Route::get('/api/karyawan/{id}', [KaryawanBaruController::class, 'show']);
Route::post('/api/karyawan/update/{id}', [KaryawanBaruController::class, 'update'])->name('api.users.update');
Route::delete('/api/karyawan/delete/{id}', [KaryawanBaruController::class, 'destroy'])->name('api.users.delete');

Route::post('/api/karyawan/foto', [KaryawanBaruController::class, 'storeFoto'])->name('api.karyawan.foto.store');
