<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('media');
        $this->seed(ServiceSeeder::class);
        $this->actingAs(User::factory()->create());
    }

    private function payload(array $override = []): array
    {
        return $override + [
            'name' => 'Kesehatan',
            'desc' => 'Perlengkapan kesehatan untuk kantor dan sekolah.',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('alkes.jpg', 1400, 1050),
        ];
    }

    public function test_daftar_menampilkan_semua_bidang_usaha(): void
    {
        $this->get('/admin/bidang-usaha')->assertOk()->assertSee('Software')->assertSee('Mesin Kantor');
    }

    public function test_form_tambah_dan_edit_bisa_dibuka(): void
    {
        $this->get('/admin/bidang-usaha/create')->assertOk()->assertSee('Tambah Bidang Usaha');
        $this->get('/admin/bidang-usaha/'.Service::first()->id.'/edit')->assertOk()->assertSee('Edit:');
    }

    public function test_tambah_bidang_usaha_baru_muncul_di_website(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload())->assertRedirect(route('admin.services.index'));

        $service = Service::where('slug', 'kesehatan')->firstOrFail();
        Storage::disk('media')->assertExists($service->image);
        $this->assertSame(10, $service->sort_order); // otomatis di paling akhir

        $this->get('/bidang-usaha')->assertSee('Perdagangan Eceran Kesehatan');
        $this->get('/')->assertSee('10 Bidang Usaha');
        $this->get('/bidang-usaha/kesehatan')->assertOk()->assertSee('Kesehatan');
    }

    public function test_foto_portrait_dipotong_otomatis_menjadi_4_banding_3(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload([
            'image' => UploadedFile::fake()->image('jersey.jpg', 900, 1600), // portrait
        ]))->assertRedirect(route('admin.services.index'));

        $service = Service::where('slug', 'kesehatan')->firstOrFail();
        [$width, $height] = getimagesizefromstring(Storage::disk('media')->get($service->image));

        $this->assertSame(900, $width);
        $this->assertSame(675, $height); // 900 x 3/4
    }

    public function test_versi_besar_foto_disimpan_untuk_zoom_dan_ikut_terhapus(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload([
            'image' => UploadedFile::fake()->image('jersey.jpg', 3000, 2000),
        ]))->assertRedirect(route('admin.services.index'));

        $service = Service::where('slug', 'kesehatan')->firstOrFail();
        $full = preg_replace('/(\.[^.\/]+)$/', '-full$1', $service->image);

        Storage::disk('media')->assertExists($full);
        [$width, $height] = getimagesizefromstring(Storage::disk('media')->get($full));
        $this->assertSame(2400, $width);   // diperkecil ke 2400 px, tidak dipotong
        $this->assertSame(1600, $height);

        $this->get('/bidang-usaha/kesehatan')->assertOk()->assertSee('data-lightbox', false)->assertSee('-full.jpg', false);

        $this->delete('/admin/bidang-usaha/'.$service->id);
        Storage::disk('media')->assertMissing($service->image);
        Storage::disk('media')->assertMissing($full);
    }

    public function test_produk_ditambah_lewat_form_dan_tampil_di_halaman_detail(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload([
            'items_present' => '1',
            'items' => [
                ['name' => 'Jersey home', 'desc' => 'Jersey kandang musim ini.', 'points' => "Ukuran S sampai XXL\nBahan dry-fit\n"],
                ['name' => '', 'desc' => '', 'points' => ''], // baris kosong diabaikan
            ],
        ]))->assertRedirect(route('admin.services.index'));

        $service = Service::where('slug', 'kesehatan')->firstOrFail();
        $this->assertSame(1, $service->items()->count());
        $this->assertSame(['Ukuran S sampai XXL', 'Bahan dry-fit'], $service->items()->first()->points);

        $this->get('/bidang-usaha/kesehatan')
            ->assertOk()
            ->assertSee('Detail produk')
            ->assertSee('Jersey home')
            ->assertSee('Bahan dry-fit');
    }

    public function test_edit_mengganti_daftar_produk_dan_tanpa_penanda_tidak_menghapus(): void
    {
        $service = Service::where('slug', 'atk')->first();
        $before = $service->items()->count();
        $this->assertGreaterThan(1, $before);

        $base = ['name' => $service->name, 'desc' => $service->desc, 'is_active' => '1'];

        // Tanpa "items_present": produk lama utuh
        $this->put("/admin/bidang-usaha/{$service->id}", $base);
        $this->assertSame($before, $service->items()->count());

        // Dengan penanda: daftar diganti sesuai isi form
        $this->put("/admin/bidang-usaha/{$service->id}", $base + [
            'items_present' => '1',
            'items' => [['name' => 'Pulpen gel', 'desc' => 'Pulpen tinta gel.', 'points' => "Hitam\nBiru"]],
        ]);
        $this->assertSame(['Pulpen gel'], $service->items()->pluck('name')->all());

        // Semua baris dihapus: tab Detail produk hilang dari website
        $this->put("/admin/bidang-usaha/{$service->id}", $base + ['items_present' => '1']);
        $this->assertSame(0, $service->items()->count());
        $this->get('/bidang-usaha/atk')->assertOk()->assertDontSee('Detail produk');
    }

    public function test_produk_tanpa_nama_ditolak(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload([
            'items_present' => '1',
            'items' => [['name' => '', 'desc' => 'Ada deskripsi', 'points' => '']],
        ]))->assertSessionHasErrors('items.0.name');

        $this->assertDatabaseMissing('services', ['slug' => 'kesehatan']);
    }

    public function test_slug_dibuat_unik_bila_nama_kembar(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload(['name' => 'Komputer']));

        $this->assertDatabaseHas('services', ['slug' => 'komputer-2']);
        $this->assertDatabaseHas('services', ['slug' => 'komputer']);
    }

    public function test_foto_wajib_saat_menambah(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload(['image' => null]))
            ->assertSessionHasErrors('image');
    }

    public function test_validasi_field_wajib(): void
    {
        $this->post('/admin/bidang-usaha', ['image' => UploadedFile::fake()->image('a.jpg')])
            ->assertSessionHasErrors(['name', 'desc']);
    }

    public function test_edit_tanpa_ganti_foto_mempertahankan_foto_lama(): void
    {
        $service = Service::where('slug', 'atk')->first();
        $oldImage = $service->image;

        $this->put("/admin/bidang-usaha/{$service->id}", [
            'name' => 'ATK Lengkap', 'desc' => 'Deskripsi baru.', 'is_active' => '1',
        ])->assertRedirect(route('admin.services.index'));

        $service->refresh();
        $this->assertSame('ATK Lengkap', $service->name);
        $this->assertSame($oldImage, $service->image);
        $this->assertSame('atk', $service->slug);
    }

    public function test_ganti_foto_menghapus_file_lama_tapi_tidak_menyentuh_url_imagekit(): void
    {
        $service = Service::where('slug', 'atk')->first();
        $this->assertStringStartsWith('https://', $service->image); // gambar lama dari ImageKit

        $base = ['name' => $service->name, 'desc' => $service->desc, 'is_active' => '1'];

        $this->put("/admin/bidang-usaha/{$service->id}", $base + ['image' => UploadedFile::fake()->image('1.jpg', 1200, 900)]);
        $first = $service->fresh()->image;
        Storage::disk('media')->assertExists($first);

        $this->put("/admin/bidang-usaha/{$service->id}", $base + ['image' => UploadedFile::fake()->image('2.jpg', 1200, 900)]);
        $second = $service->fresh()->image;

        Storage::disk('media')->assertMissing($first);
        Storage::disk('media')->assertExists($second);
    }

    public function test_menyembunyikan_dan_mengubah_urutan(): void
    {
        $service = Service::where('slug', 'atk')->first();

        $this->put("/admin/bidang-usaha/{$service->id}", [
            'name' => $service->name, 'desc' => $service->desc,
            'is_active' => '0', 'sort_order' => 99,
        ]);

        $service->refresh();
        $this->assertFalse($service->is_active);
        $this->assertSame(99, $service->sort_order);
        $this->get('/bidang-usaha/atk')->assertNotFound();
    }

    public function test_hapus_bidang_usaha_beserta_produk_dan_filenya(): void
    {
        $this->post('/admin/bidang-usaha', $this->payload());
        $service = Service::where('slug', 'kesehatan')->first();
        $service->items()->create(['name' => 'Termometer', 'desc' => 'x', 'points' => ['a']]);
        $image = $service->image;

        $this->delete("/admin/bidang-usaha/{$service->id}")->assertRedirect(route('admin.services.index'));

        $this->assertModelMissing($service);
        $this->assertDatabaseMissing('service_items', ['name' => 'Termometer']);
        Storage::disk('media')->assertMissing($image);
    }
}