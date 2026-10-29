<?php

namespace Tests\Feature;

use App\Models\GalleryPhoto;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_galeri_kosong_menampilkan_foto_bawaan_unsplash(): void
    {
        $this->get('/galeri')
            ->assertOk()
            ->assertSee('images.unsplash.com/photo-1696433933590-1086d1d53133', false)
            ->assertDontSee('Foto galeri akan segera hadir');
    }

    public function test_teks_banner_bawaan_dipakai_selama_admin_belum_mengisi(): void
    {
        $this->get('/galeri')
            ->assertOk()
            ->assertSee('Lihat lebih dekat.')
            ->assertSee('Toko dan kegiatan kami.');
    }

    public function test_teks_banner_dari_admin_tampil_di_halaman_galeri(): void
    {
        SiteSetting::saveGallery([
            'eyebrow' => 'Dokumentasi',
            'title' => 'Judul Buatan Admin',
            'subtitle' => 'Baris kedua baru.',
            'lead' => 'Keterangan baru dari admin.',
        ]);

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('Dokumentasi')
            ->assertSee('Judul Buatan Admin')
            ->assertSee('Baris kedua baru.')
            ->assertSee('Keterangan baru dari admin.')
            ->assertDontSee('Lihat lebih dekat.');
    }

    public function test_kolom_teks_yang_dikosongkan_kembali_ke_teks_bawaan(): void
    {
        SiteSetting::saveGallery(['title' => 'Judul Buatan Admin']);

        $this->get('/galeri')
            ->assertSee('Judul Buatan Admin')
            ->assertSee('Toko dan kegiatan kami.'); // subtitle kosong -> bawaan
    }

    public function test_foto_nonaktif_tidak_tampil_di_galeri(): void
    {
        GalleryPhoto::create(['title' => 'Foto Aktif', 'category' => 'Toko', 'image' => 'uploads/gallery/a.jpg', 'sort_order' => 1, 'is_active' => true]);
        GalleryPhoto::create(['title' => 'Foto Tersembunyi', 'category' => 'Toko', 'image' => 'uploads/gallery/b.jpg', 'sort_order' => 2, 'is_active' => false]);

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('Foto Aktif')
            ->assertDontSee('Foto Tersembunyi');
    }
}