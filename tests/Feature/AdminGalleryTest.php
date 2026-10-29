<?php

namespace Tests\Feature;

use App\Models\GalleryPhoto;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('media');
        $this->actingAs(User::factory()->create());
    }

    public function test_halaman_admin_galeri_dan_form_bisa_dibuka(): void
    {
        $this->get('/admin/galeri')->assertOk()->assertSee('Teks banner');
        $this->get('/admin/galeri/create')->assertOk()->assertSee('Tambah Foto Galeri');
    }

    public function test_tambah_foto_menyimpan_file_dan_data(): void
    {
        $this->post('/admin/galeri', [
            'title' => 'Tampak depan',
            'category' => 'Toko',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('toko.jpg', 2000, 1200),
        ])->assertRedirect('/admin/galeri')->assertSessionHas('status');

        $photo = GalleryPhoto::firstOrFail();
        $this->assertSame('Tampak depan', $photo->title);
        Storage::disk('media')->assertExists($photo->image);
    }

    public function test_foto_wajib_dan_kategori_harus_dari_daftar(): void
    {
        $this->post('/admin/galeri', ['category' => 'Toko'])->assertSessionHasErrors('image');
        $this->post('/admin/galeri', [
            'category' => 'Ngawur',
            'image' => UploadedFile::fake()->image('a.jpg'),
        ])->assertSessionHasErrors('category');

        $this->assertSame(0, GalleryPhoto::count());
    }

    public function test_edit_tanpa_ganti_foto_mempertahankan_foto_lama(): void
    {
        $photo = GalleryPhoto::create(['title' => 'Lama', 'category' => 'Toko', 'image' => 'uploads/gallery/lama.jpg', 'sort_order' => 1, 'is_active' => true]);

        $this->put("/admin/galeri/{$photo->id}", ['title' => 'Baru', 'category' => 'Kegiatan', 'is_active' => '1'])
            ->assertRedirect('/admin/galeri');

        $photo->refresh();
        $this->assertSame('Baru', $photo->title);
        $this->assertSame('uploads/gallery/lama.jpg', $photo->image);
    }

    public function test_hapus_foto_menghapus_data_dan_filenya(): void
    {
        $this->post('/admin/galeri', [
            'category' => 'Toko',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('toko.jpg', 1200, 800),
        ]);
        $photo = GalleryPhoto::firstOrFail();
        Storage::disk('media')->assertExists($photo->image);

        $this->delete("/admin/galeri/{$photo->id}")->assertRedirect('/admin/galeri');

        $this->assertSame(0, GalleryPhoto::count());
        Storage::disk('media')->assertMissing($photo->image);
    }

    public function test_simpan_teks_banner_dan_route_teks_tidak_dianggap_foto(): void
    {
        $this->put('/admin/galeri/teks', ['title' => 'Judul Admin'])
            ->assertRedirect()
            ->assertSessionHas('status');

        $this->assertSame('Judul Admin', SiteSetting::gallery()['title']);
    }

    public function test_teks_banner_terlalu_panjang_ditolak(): void
    {
        $this->put('/admin/galeri/teks', ['title' => str_repeat('a', 81)])
            ->assertSessionHasErrors('title', null, 'text');
    }

    public function test_dashboard_menampilkan_pintasan_galeri(): void
    {
        $this->get('/admin')->assertOk()->assertSee('/admin/galeri', false);
    }
}