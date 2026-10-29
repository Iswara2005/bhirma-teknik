<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Akun admin TIDAK dibuat di sini (supaya tidak ada password bawaan di kode).
     * Buat dengan: php artisan admin:create
     */
    public function run(): void
    {
        $this->call([
            ServiceSeeder::class,
            GallerySeeder::class,
        ]);   
     }
}
