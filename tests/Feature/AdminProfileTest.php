<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['name' => 'Admin Lama', 'email' => 'lama@test.local', 'password' => 'rahasia123']);
    }

    public function test_tamu_tidak_bisa_membuka_profil(): void
    {
        $this->get('/admin/profil')->assertRedirect(route('admin.login'));
        $this->put('/admin/profil', [])->assertRedirect(route('admin.login'));
        $this->put('/admin/profil/password', [])->assertRedirect(route('admin.login'));
    }

    public function test_halaman_profil_menampilkan_data_admin(): void
    {
        $this->actingAs($this->admin())->get('/admin/profil')
            ->assertOk()->assertSee('Profil')->assertSee('Admin Lama')->assertSee('lama@test.local');
    }

    public function test_ubah_nama_dan_email(): void
    {
        $user = $this->admin();

        $this->actingAs($user)->put('/admin/profil', ['name' => 'Admin Baru', 'email' => 'baru@test.local'])
            ->assertRedirect(route('admin.profile.edit'))->assertSessionHas('status');

        $this->assertSame('Admin Baru', $user->fresh()->name);
        $this->assertSame('baru@test.local', $user->fresh()->email);
    }

    public function test_email_yang_dipakai_akun_lain_ditolak(): void
    {
        User::factory()->create(['email' => 'dipakai@test.local']);

        $this->actingAs($this->admin())->put('/admin/profil', ['name' => 'X', 'email' => 'dipakai@test.local'])
            ->assertSessionHasErrors('email');
    }

    public function test_menyimpan_tanpa_mengubah_email_tidak_dianggap_kembar(): void
    {
        $user = $this->admin();

        $this->actingAs($user)->put('/admin/profil', ['name' => 'Nama Baru', 'email' => 'lama@test.local'])
            ->assertSessionHasNoErrors();
    }

    public function test_ganti_password_berhasil(): void
    {
        $user = $this->admin();

        $this->actingAs($user)->put('/admin/profil/password', [
            'current_password' => 'rahasia123',
            'password' => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
        ])->assertRedirect(route('admin.profile.edit'))->assertSessionHas('status');

        $this->assertTrue(Hash::check('passwordbaru1', $user->fresh()->password));
    }

    public function test_ganti_password_ditolak_bila_tidak_valid(): void
    {
        $user = $this->admin();
        $this->actingAs($user);

        // password lama salah
        $this->put('/admin/profil/password', ['current_password' => 'salah', 'password' => 'passwordbaru1', 'password_confirmation' => 'passwordbaru1'])
            ->assertSessionHasErrors('current_password');

        // konfirmasi tidak sama
        $this->put('/admin/profil/password', ['current_password' => 'rahasia123', 'password' => 'passwordbaru1', 'password_confirmation' => 'beda'])
            ->assertSessionHasErrors('password');

        // terlalu pendek
        $this->put('/admin/profil/password', ['current_password' => 'rahasia123', 'password' => 'pendek', 'password_confirmation' => 'pendek'])
            ->assertSessionHasErrors('password');

        // sama dengan password lama
        $this->put('/admin/profil/password', ['current_password' => 'rahasia123', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123'])
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check('rahasia123', $user->fresh()->password)); // tidak berubah
    }

    public function test_upload_foto_profil_dipotong_persegi_dan_foto_lama_dihapus(): void
    {
        Storage::fake('media');
        $user = $this->admin();
        $this->actingAs($user);

        $this->put('/admin/profil', ['name' => 'Admin Lama', 'email' => 'lama@test.local', 'image' => UploadedFile::fake()->image('a.jpg', 800, 600)])
            ->assertSessionHasNoErrors();

        $first = $user->fresh()->photo;
        $this->assertNotNull($first);
        Storage::disk('media')->assertExists($first);
        [$w, $h] = getimagesizefromstring(Storage::disk('media')->get($first));
        $this->assertSame(400, $w);
        $this->assertSame(400, $h); // dipotong persegi

        // ganti foto: file lama ikut terhapus
        $this->put('/admin/profil', ['name' => 'Admin Lama', 'email' => 'lama@test.local', 'image' => UploadedFile::fake()->image('b.jpg', 500, 900)]);
        $second = $user->fresh()->photo;
        $this->assertNotSame($first, $second);
        Storage::disk('media')->assertMissing($first);
        Storage::disk('media')->assertExists($second);

        // halaman profil & sidebar menampilkan foto
        $this->get('/admin/profil')->assertOk()->assertSee($second, false);
    }

    public function test_hapus_foto_profil(): void
    {
        Storage::fake('media');
        $user = $this->admin();
        $this->actingAs($user)->put('/admin/profil', ['name' => 'A', 'email' => 'lama@test.local', 'image' => UploadedFile::fake()->image('a.jpg', 300, 300)]);
        $path = $user->fresh()->photo;

        $this->put('/admin/profil', ['name' => 'A', 'email' => 'lama@test.local', 'remove_photo' => '1']);

        $this->assertNull($user->fresh()->photo);
        Storage::disk('media')->assertMissing($path);
    }

    public function test_file_bukan_gambar_ditolak_sebagai_foto_profil(): void
    {
        Storage::fake('media');

        $this->actingAs($this->admin())
            ->put('/admin/profil', ['name' => 'A', 'email' => 'lama@test.local', 'image' => UploadedFile::fake()->create('a.pdf', 10, 'application/pdf')])
            ->assertSessionHasErrors('image');
    }
}