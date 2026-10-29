<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Isi galeri awal dari foto contoh di config/gallery.php, supaya foto yang tampil di
     * website langsung terdaftar di Admin > Galeri. Aman dijalankan berulang: foto yang
     * sudah ada (dan mungkin sudah diedit admin) tidak ditimpa dan tidak dobel.
     */
    public function run(): void
    {
        foreach (config('gallery.defaults') as $i => [$url, $title, $category]) {
            GalleryPhoto::firstOrCreate(
                ['image' => $url],
                [
                    'title' => $title,
                    'category' => $category,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}