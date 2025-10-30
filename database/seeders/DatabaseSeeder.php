<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use App\Models\User; // Tidak diperlukan jika tidak memanggil factory di sini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class, 
            ]);
    }
}