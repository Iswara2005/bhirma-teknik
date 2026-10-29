<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\SiteImage;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ServiceSeeder::class);
    }

    public function test_semua_halaman_publik_bisa_dibuka(): void
    {
        foreach (['/', '/tentang-kami', '/bidang-usaha', '/kontak'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_seeder_memuat_9_bidang_usaha_dan_45_produk_dan_aman_dijalankan_ulang(): void
    {
        $this->assertSame(9, Service::count());
        $this->assertSame(45, ServiceItem::count());

        $this->seed(ServiceSeeder::class);

        $this->assertSame(9, Service::count());
        $this->assertSame(45, ServiceItem::count());
    }

    public function test_halaman_detail_menampilkan_produk(): void
    {
        $this->get('/bidang-usaha/komputer')
            ->assertOk()
            ->assertSee('Detail produk')
            ->assertSee('Laptop');
    }

    public function test_slug_tidak_dikenal_menghasilkan_404(): void
    {
        $this->get('/bidang-usaha/ngawur')->assertNotFound();
    }

    public function test_bidang_usaha_yang_disembunyikan_hilang_dari_website(): void
    {
        Service::where('slug', 'atk')->update(['is_active' => false]);

        $this->get('/bidang-usaha/atk')->assertNotFound();
        $this->get('/bidang-usaha')->assertDontSee('/bidang-usaha/atk');
        $this->get('/')->assertSee('8 Bidang Usaha');
    }

    public function test_gambar_bawaan_dipakai_sebelum_admin_menggantinya(): void
    {
        $this->get('/')->assertSee(e(config('site_images.hero.default')), false);    }

    public function test_gambar_yang_diganti_admin_tampil_di_website(): void
    {
        SiteImage::create(['key' => 'hero', 'path' => 'uploads/site/baru.jpg']);

        $this->get('/')
            ->assertSee('/uploads/site/baru.jpg', false)
            ->assertDontSee(e(config('site_images.hero.default')), false);
    }
}
