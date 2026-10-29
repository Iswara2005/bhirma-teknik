<?php

namespace App\View\Composers;

use App\Models\Service;
use App\Models\SiteImage;
use App\Support\Whatsapp;
use Illuminate\View\View;

/**
 * Menyuplai data yang dipakai hampir semua halaman publik
 * ($company, $images, $services, $keuntungan), menggantikan
 * method page() yang dulu ada di base Controller.
 */
class SiteComposer
{
    public function compose(View $view): void
    {
        $company = config('company');

        $view->with([
            // Data teks perusahaan (dari config/company.php) + link WhatsApp umum.
            'company' => [
                ...collect($company)->except(['goals', 'keuntungan'])->all(),
                'wa_url' => Whatsapp::link('saya ingin menanyakan produk dan penawaran.'),
            ],
            // Semua gambar situs, termasuk logo: $images['logo'], $images['hero'], dst.
            'images' => SiteImage::urls(),
            'services' => Service::active()->ordered()->get()->map->toCard()->all(),
            'keuntungan' => $company['keuntungan'],
        ]);
    }
}