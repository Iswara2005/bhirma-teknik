<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        try {
            $photos = GalleryPhoto::active()->ordered()->get()->map->toCard();
        } catch (QueryException $e) {
            report($e); // tabel belum dibuat / database bermasalah: catat, jangan matikan halaman
            $photos = collect();
        }

        // Catatan: bila tidak ada foto aktif, $photos dibiarkan kosong. View gallery.blade.php
        // lalu menampilkan pesan "Foto galeri akan segera hadir." (bukan foto contoh Unsplash).

        return view('gallery', [
            // Teks banner: isian admin (Admin > Teks Halaman > Galeri) atau teks bawaan bila kosong
            'text' => SiteSetting::gallery(),
            'photos' => $photos,
            // Kategori unik, sesuai urutan kemunculan foto
            'categories' => $photos->pluck('category')->filter()->unique()->values(),
        ]);
    }
}