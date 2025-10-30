<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    // Relasi ke Posts (Satu Kategori punya banyak Berita)
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
