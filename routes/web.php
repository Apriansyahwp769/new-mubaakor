<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController; 
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use App\Models\Post; // Tambahkan import Model untuk Closure
use App\Models\Category; // Tambahkan import Model untuk Closure


// =====================================================================
// 1. ROUTE PUBLIK (FRONTEND)
// =====================================================================

// Search (Paling Spesifik)
Route::get('/search', [FrontendController::class, 'search'])->name('search'); 

// Detail Berita
Route::get('berita/{post:slug}', [FrontendController::class, 'show'])->name('posts.show');

// Like/Unlike (AJAX)
Route::post('berita/{post}/like', [FrontendController::class, 'toggleLike'])->name('posts.like');

// Home Page (Paling Umum)
Route::get('/{category:slug?}', [FrontendController::class, 'index'])->name('home');


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


Route::middleware(['auth'])->group(function () {
    
    // b. ROUTE ADMIN GROUP (CRUD dan Dashboard)
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // DASHBOARD ADMIN UTAMA
        Route::get('dashboard', function () {
            $totalPosts = Post::count();
            $totalCategories = Category::count();
            $publishedPosts = Post::where('status', 'published')->count();
            $latestPosts = Post::with('category')->latest()->take(5)->get();
            return view('admin.dashboard', compact('totalPosts', 'totalCategories', 'publishedPosts', 'latestPosts'));
        })->name('dashboard');

        // CRUD Kategori
        Route::resource('categories', CategoryController::class)->except(['show']);

        // CRUD Berita
        Route::resource('posts', PostController::class)->except(['show']); 

        // 🔥 FIX: ROUTE LOGOUT DAN PROFIL dipindahkan ke sini 🔥
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::patch('password', [PasswordController::class, 'update'])->name('password.update');
    });
    

});