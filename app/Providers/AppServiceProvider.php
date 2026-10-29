<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Support\PageTexts;
use App\View\Composers\SiteComposer;
use Illuminate\Support\Facades\View;
use App\Support\CompanyProfile;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kontak & alamat yang diubah admin (Admin > Kontak & Alamat) menimpa config/company.php.
        // Harus paling awal, sebelum ada yang membaca config('company').
        CompanyProfile::apply();
        // Halaman publik. Layout, partials, dan komponen ikut mewarisi datanya.
        View::composer(
            ['home', 'about', 'services', 'service-detail', 'contact', 'gallery', 'errors::404', 'errors.404'],
            SiteComposer::class,
        );

        // Teks halaman yang diatur admin (Admin > Teks Halaman). Nama halaman di PageTexts
        // sama dengan nama view-nya, dan teksnya tersedia di view sebagai $pageText.
        foreach (array_keys(PageTexts::pages()) as $page) {
            View::composer($page, fn ($view) => $view->with('pageText', SiteSetting::pageText($page)));
        }
    }
}