<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Kode ini harus mengembalikan nama route (admin.login) saat user belum login
        // dan mencoba mengakses area yang terproteksi.
        return $request->expectsJson() ? null : route('admin.login'); 
    }
}