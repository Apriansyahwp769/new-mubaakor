<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 
use App\Events\FileUploaded; // 🔥 IMPORT EVENT KUSTOM 🔥

class PostController extends Controller
{
    /**
     * Tampilkan daftar semua berita (INDEX)
     */
    public function index()
    {
        $posts = Post::with('category', 'user')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Tampilkan formulir untuk membuat berita baru (CREATE)
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Simpan berita baru ke database (STORE)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        // 2. LOGIKA UPLOAD GAMBAR BARU DENGAN CUSTOM FILENAME
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $fileName = Str::slug($request->title) . '-' . time() . '.' . $extension;

            // Simpan file ke storage/app/public/images/posts
            $imagePath = $file->storeAs('images/posts', $fileName, 'public');

        }

        // 3. Simpan Data ke Database
        Post::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image_path' => $imagePath, 
            'status' => $request->status,
            'published_at' => ($request->status == 'published') ? now() : null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Berita baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit untuk berita (EDIT)
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Perbarui berita di database (UPDATE)
     */
    public function update(Request $request, Post $post)
    {
        // 1. Validasi Input
        $request->validate([
            'title' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $imagePath = $post->image_path; 

        // 2. LOGIKA UPDATE GAMBAR BARU DENGAN CUSTOM FILENAME 
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }

            // Buat nama file baru dan simpan
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($request->title) . '-' . time() . '.' . $extension;
            $imagePath = $file->storeAs('images/posts', $fileName, 'public');
            
        }

        // 3. Perbarui Data
        $post->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image_path' => $imagePath,
            'status' => $request->status,
            'published_at' => ($request->status == 'published' && !$post->published_at) ? now() : $post->published_at,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Hapus berita dari database (DESTROY)
     */
    public function destroy(Post $post)
    {
        // Hapus file gambar dari server
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dihapus.');
    }
}