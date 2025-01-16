<?php

use App\Livewire\Admin\Kategori;
use App\Livewire\Admin\Kriteria;
use App\Livewire\Admin\Pengguna;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\{Route, Auth};


// disable register, reset password
Auth::routes(['register' => false, 'reset' => false]);

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// livewire
// home
Route::middleware(['auth'])->group(function () {
    Route::get('/home', Dashboard::class)->name('home');
    // kategori
    Route::get('/kategori', Kategori::class)->name('kategori');
    // pengguna
    Route::get('/pengguna', Pengguna::class)->name('pengguna');
    // kriteria
    Route::get('/kriteria', Kriteria::class)->name('kriteria');
    // alternatif
    Route::get('/alternatif', App\Livewire\Admin\Alternatif::class)->name('alternatif');
    // perhitungan
    Route::get('/perhitungan', App\Livewire\Admin\Perhitungan::class)->name('perhitungan');
    // hasil
    Route::get('/hasil', App\Livewire\Admin\Hasil::class)->name('hasil');
    // profil
    Route::get('/profil', App\Livewire\Profil::class)->name('profil');
    // admin.manajemen-user
    Route::get('/admin/manajemen-user', App\Livewire\Admin\ManajemenUser::class)->name('admin.manajemen-user');
});
