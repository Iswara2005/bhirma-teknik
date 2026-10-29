<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        foreach (['/admin', '/admin/gambar-situs', '/admin/bidang-usaha', '/admin/bidang-usaha/create'] as $url) {
            $this->get($url)->assertRedirect(route('admin.login'));
        }
    }

    public function test_tamu_tidak_bisa_mengubah_data(): void
    {
        $this->post('/admin/bidang-usaha', [])->assertRedirect(route('admin.login'));
        $this->delete('/admin/bidang-usaha/1')->assertRedirect(route('admin.login'));
        $this->put('/admin/gambar-situs/hero', [])->assertRedirect(route('admin.login'));
    }

    public function test_login_berhasil(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'rahasia123'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        $user = User::factory()->create(['password' => 'rahasia123']);

        $this->from('/admin/login')
            ->post('/admin/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_dibatasi_setelah_5_percobaan(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'a@b.c', 'password' => 'x']);
        }

        $this->post('/admin/login', ['email' => 'a@b.c', 'password' => 'x'])->assertStatus(429);
    }

    public function test_logout(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/logout')
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_admin_yang_sudah_login_tidak_melihat_form_login(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin/login')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_perintah_admin_create_membuat_akun(): void
    {
        $this->artisan('admin:create', ['--name' => 'Budi', '--email' => 'budi@contoh.id'])
            ->expectsQuestion('Password (minimal 8 karakter)', 'rahasia123')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', ['email' => 'budi@contoh.id']);
    }
}
