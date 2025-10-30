<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Like;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Halaman Beranda: Menampilkan daftar semua berita yang 'published'
     * Mendukung filter berdasarkan slug kategori (opsional).
     */
    public function index(Category $category = null)
    {
        // 1. Ambil semua kategori untuk ditampilkan di navigasi filter
        $categories = Category::all();

        // 2. Query dasar: Ambil semua postingan yang sudah diterbitkan
        $posts = Post::with('category', 'user')
                    ->where('status', 'published')
                    ->latest();

        // 3. LOGIKA FILTER KATEGORI
        // 🔥 KOREKSI: Gunakan pengecekan yang benar untuk parameter opsional dari Route Model Binding
        if ($category && $category->exists) { 
            $posts->where('category_id', $category->id);
        }

        // 4. Eksekusi query dengan pagination (INI HARUS TERAKHIR!)
        $posts = $posts->paginate(12); // Pastikan ini adalah paginate, bukan get() atau all()

        // Kirim data posts, semua categories, dan category yang sedang aktif (jika ada) ke view
        return view('frontend.index', compact('posts', 'categories', 'category'));
    }

    /**
     * Halaman Detail Berita: Menampilkan satu berita tertentu.
     */
    public function show(Post $post)
    {
        // Pastikan hanya berita yang sudah diterbitkan yang bisa diakses
        if ($post->status !== 'published') {
            abort(404);
        }

        // Cek apakah user/IP address ini sudah pernah like
        $hasLiked = Like::where('post_id', $post->id)
                        ->where('ip_address', request()->ip())
                        ->exists();
        
        // Hitung total likes
        $totalLikes = $post->likes()->count();
        $categories = Category::all();
        // Mengambil penulis (user) di Controller agar relasi Post-User-Category aman.
        $post->load('user', 'category'); 

        return view('frontend.show', compact('post', 'hasLiked', 'totalLikes','categories'));
    }

    /**
     * Fitur Like/Unlike Berita (AJAX).
     */
    public function toggleLike(Post $post)
    {
        $ip = request()->ip();

        // Cek apakah sudah ada like dari IP ini
        $like = Like::where('post_id', $post->id)
                      ->where('ip_address', $ip)
                      ->first();

        if ($like) {
            // Jika sudah ada, hapus (Unlike)
            $like->delete();
            $action = 'unliked';
        } else {
            // Jika belum ada, buat (Like)
            Like::create([
                'post_id' => $post->id,
                'ip_address' => $ip,
            ]);
            $action = 'liked';
        }
        // Search
        
        // Kirim response balik ke JavaScript
        $totalLikes = $post->likes()->count();
        return response()->json(['success' => true, 'action' => $action, 'total_likes' => $totalLikes]);
    }

    // Search
    public function search(Request $request)
    {
        $query = $request->input('q'); // Ambil query dari input 'q'
        $categories = Category::all(); // Ambil categories untuk navbar/layout

        if (!$query) {
            // Jika query kosong, redirect ke halaman utama
            return redirect()->route('home');
        }

        // Cari berita di kolom 'title' atau 'content' yang statusnya 'published'
        $posts = Post::with('category', 'user')
                    ->where('status', 'published')
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', '%' . $query . '%')
                          ->orWhere('content', 'like', '%' . $query . '%');
                    })
                    ->latest()
                    ->paginate(12);

        // Kirim hasil ke view yang sama (index.blade.php) atau view khusus
        // Kita gunakan view index untuk kecepatan
        return view('frontend.index', compact('posts', 'categories'))
                   ->with('search_query', $query); // Kirim query untuk ditampilkan di judul
    }
}