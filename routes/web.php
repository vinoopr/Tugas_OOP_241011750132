<?php

use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProjectController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page.home');
});

Route::get('/about', function () {
    return view('page.about');
});

Route::get('/profile', [MahasiswaController::class, 'index']);

Route::get('/project', [ProjectController::class, 'index'])
    ->name('project.index');

Route::get('/project/{id}', [ProjectController::class, 'show'])
    ->name('project.show');

// Login menggunakan Laravel UI; registrasi publik tidak disediakan.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Semua route CRUD wajib melewati autentikasi dan pemeriksaan admin.
Route::middleware(['auth', EnsureAdmin::class])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.home');

    Route::get('projects/pdf', [AdminProjectController::class, 'cetakLaporan'])
        ->name('admin.projects.pdf');

    Route::resource('projects', AdminProjectController::class)
        ->except(['show'])->names('admin.projects');
});
