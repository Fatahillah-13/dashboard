<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KaryawanBaruController;

// Route::get('/', function () {
//     return view('home');
// });

// Route to Login and Register page
Auth::routes();

// Route to profile page
Route::get('/admin/profile', function () {
    return view('profile');
});

// Route to list calon karyawan page
Route::get('/list-new-member', function () {
    return view('list_new_member');
});

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