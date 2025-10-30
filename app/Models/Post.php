<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    /**
     * Kolom yang aman untuk Mass Assignment.
     */
    protected $fillable = [
        'user_id', 
        'category_id', 
        'title', 
        'slug', 
        'content', 
        'image_path', 
        'status', 
        'published_at'
    ];
    
    /**
     * 🔥 SOLUSI: Cast kolom published_at sebagai tanggal (datetime)
     * Ini menyelesaikan error "Call to a member function format() on string"
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Relasi: Satu Berita dimiliki oleh satu User (Admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu Berita dimiliki oleh satu Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Relasi: Satu Berita memiliki banyak Likes
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}