<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController; 
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; 
// use App\Http\Controllers\CustomAuthRedirectController; // Hapus jika tidak digunakan

// =====================================================================
// 1. ROUTE PUBLIK (FRONTEND)
// =====================================================================

// Home Page (Beranda) dan Filter Kategori
Route::get('/{category:slug?}', [FrontendController::class, 'index'])->name('home');

// Detail Berita
Route::get('berita/{post:slug}', [FrontendController::class, 'show'])->name('posts.show');

// Like/Unlike (AJAX)
Route::post('berita/{post}/like', [FrontendController::class, 'toggleLike'])->name('posts.like');


// =====================================================================
// 2. ROUTE OTENTIKASI ADMIN (LOGIN/SUBMIT)
// =====================================================================

Route::prefix('admin')
    ->middleware('guest')
    ->group(function () {
        
        // GET /admin/login (Menampilkan Form Login)
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('admin.login'); 

        // POST /admin/login (Proses Submit Login)
        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->name('admin.login.post'); 
    });


// =====================================================================
// 3. ROUTE ADMIN TERPROTEKSI (HARUS LOGIN)
// =====================================================================

Route::middleware(['auth'])->group(function () {
    
    // a. ROUTE PROFIL DAN LOGOUT (Di luar prefix /admin)
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    // b. ROUTE ADMIN GROUP (Dashboard dan CRUD)
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // 🔥 DASHBOARD ADMIN UTAMA (Solusi untuk Error 404) 🔥
        // URL: /admin/dashboard, Name: admin.dashboard
        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('dashboard'); // Nama lengkap menjadi admin.dashboard

        // CRUD Kategori
        Route::resource('categories', CategoryController::class)->except(['show']);

        // CRUD Berita
        Route::resource('posts', PostController::class)->except(['show']); 
    });
});