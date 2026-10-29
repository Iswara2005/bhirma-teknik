<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\SiteImage;
use App\Support\PageTexts;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $serviceCount = Service::count();
        $hiddenCount = Service::where('is_active', false)->count();

        return view('admin.dashboard', [
            'serviceCount' => $serviceCount,
            'activeCount' => $serviceCount - $hiddenCount,
            'hiddenCount' => $hiddenCount,
            'itemCount' => ServiceItem::count(),
            'slotCount' => count(config('site_images')),
            'customImageCount' => SiteImage::count(),
            'galleryCount' => GalleryPhoto::count(),
            // Halaman yang teksnya bisa diedit (kunci => nama), dipakai untuk pintasan
            'pageTabs' => collect(PageTexts::pages())->map(fn (array $page) => $page['label']),
        ]);
    }
}