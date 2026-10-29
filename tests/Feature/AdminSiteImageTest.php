<?php

namespace Tests\Feature;

use App\Models\SiteImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSiteImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('media');
        $this->actingAs(User::factory()->create());
    }

    public function test_halaman_menampilkan_semua_slot(): void
    {
        $this->get('/admin/gambar-situs')
            ->assertOk()
            ->assertSee('Foto utama Beranda')
            ->assertSee('Logo perusahaan');
    }

    public function test_ganti_gambar_menyimpan_file_dan_memperkecilnya(): void
    {
        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->image('foto.jpg', 3200, 1800)])
            ->assertRedirect()
            ->assertSessionHas('status');

        $path = SiteImage::where('key', 'hero')->value('path');
        Storage::disk('media')->assertExists($path);

        // slot hero maksimal 1600 px lebar
        [$width] = getimagesizefromstring(Storage::disk('media')->get($path));
        $this->assertSame(1600, $width);
    }

    public function test_gambar_kecil_tidak_diperbesar(): void
    {
        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->image('kecil.png', 800, 450)]);

        $path = SiteImage::where('key', 'hero')->value('path');
        [$width] = getimagesizefromstring(Storage::disk('media')->get($path));
        $this->assertSame(800, $width);
    }

    public function test_mengganti_lagi_menghapus_file_lama(): void
    {
        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->image('a.jpg', 900, 500)]);
        $old = SiteImage::where('key', 'hero')->value('path');

        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->image('b.jpg', 900, 500)]);
        $new = SiteImage::where('key', 'hero')->value('path');

        $this->assertNotSame($old, $new);
        Storage::disk('media')->assertMissing($old);
        Storage::disk('media')->assertExists($new);
        $this->assertSame(1, SiteImage::count());
    }

    public function test_kembalikan_ke_gambar_bawaan(): void
    {
        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->image('a.jpg', 900, 500)]);
        $path = SiteImage::where('key', 'hero')->value('path');

        $this->delete('/admin/gambar-situs/hero')->assertRedirect();

        $this->assertSame(0, SiteImage::count());
        Storage::disk('media')->assertMissing($path);
    }

    public function test_menolak_file_bukan_gambar(): void
    {
        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->create('virus.php', 10, 'application/x-php')])
            ->assertSessionHasErrors('image', null, 'slot_hero');

        $this->assertSame(0, SiteImage::count());
    }

    public function test_menolak_svg_dan_file_terlalu_besar(): void
    {
        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->create('x.svg', 10, 'image/svg+xml')])
            ->assertSessionHasErrors('image', null, 'slot_hero');

        $this->put('/admin/gambar-situs/hero', ['image' => UploadedFile::fake()->image('besar.jpg')->size(5000)])
            ->assertSessionHasErrors('image', null, 'slot_hero');
    }

    public function test_slot_tidak_dikenal_menghasilkan_404(): void
    {
        $this->put('/admin/gambar-situs/ngawur', ['image' => UploadedFile::fake()->image('a.jpg')])->assertNotFound();
        $this->delete('/admin/gambar-situs/ngawur')->assertNotFound();
    }
}
