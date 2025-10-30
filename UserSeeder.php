<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan menggunakan model User yang benar
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masukkan Akun Admin Utama Anda
        User::create([
            'name' => 'Admin Mubaakor',
            'email' => 'admin@mubaakor.com', // GANTI dengan email yang Anda gunakan
            // PENTING: Gunakan Hash::make() untuk mengenkripsi password
            'password' => Hash::make('mubaakor95#'), // GANTI dengan password kuat Anda
            'email_verified_at' => Carbon::now(), // Opsional: anggap sudah terverifikasi
            // Jika Anda punya kolom 'is_admin', tambahkan: 'is_admin' => true,
        ]);
    }
}