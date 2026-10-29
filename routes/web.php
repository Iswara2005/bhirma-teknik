<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ServiceDetailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
| Data bersama ($company, $services, dst.) disuplai App\View\Composers\SiteComposer.
*/
Route::view('/', 'home')->name('home');
Route::view('/tentang-kami', 'about')->name('about');
Route::view('/bidang-usaha', 'services')->name('services');
Route::get('/bidang-usaha/{slug}', ServiceDetailController::class)->name('services.show');
Route::get('/galeri', GalleryController::class)->name('gallery');
Route::view('/kontak', 'contact')->name('contact');

/*
|--------------------------------------------------------------------------
| CMS / Admin  (/admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login: hanya untuk tamu, dibatasi 5 percobaan per menit
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:5,1');
    });

    // Semua yang di bawah ini wajib login
    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');

        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        // Teks halaman publik (tab Beranda, Tentang Kami, Bidang Usaha, Galeri, Kontak)
        Route::get('teks-halaman/{page?}', [Admin\PageTextController::class, 'edit'])
            ->where('page', 'home|about|services|gallery|contact')->name('pages.edit');
        Route::put('teks-halaman/{page}', [Admin\PageTextController::class, 'update'])
            ->where('page', 'home|about|services|gallery|contact')->name('pages.update');

        // Kontak & alamat perusahaan: telepon/WhatsApp, email, alamat, peta
        Route::get('kontak-alamat', [Admin\CompanyController::class, 'edit'])->name('company.edit');
        Route::put('kontak-alamat', [Admin\CompanyController::class, 'update'])->name('company.update');

        // Profil admin: ubah nama/email dan ganti password
        Route::get('profil', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profil', [Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profil/password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('gambar-situs', [Admin\SiteImageController::class, 'index'])->name('images.index');
        Route::put('gambar-situs/{key}', [Admin\SiteImageController::class, 'update'])
            ->where('key', '[a-z_]+')->name('images.update');
        Route::delete('gambar-situs/{key}', [Admin\SiteImageController::class, 'destroy'])
            ->where('key', '[a-z_]+')->name('images.destroy');

        Route::resource('bidang-usaha', Admin\ServiceController::class)
            ->except('show')
            ->parameters(['bidang-usaha' => 'service'])
            ->names('services');

        // Galeri: khusus foto. Teks banner Galeri diatur di Teks Halaman > Galeri.
        Route::resource('galeri', Admin\GalleryController::class)
            ->except('show')
            ->parameters(['galeri' => 'photo'])
            ->names('gallery');
    });
});