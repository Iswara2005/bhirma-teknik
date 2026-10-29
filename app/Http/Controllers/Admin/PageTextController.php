<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\PageTexts;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\SiteImage;
use App\Support\PagePreviews;

/** Satu controller untuk semua tab Teks Halaman (Beranda, Tentang Kami, dst.). */
class PageTextController extends Controller
{
    /** Form teks satu halaman. Tanpa alamat halaman, yang dibuka Beranda. */
    public function edit(string $page = 'home'): View
    {
        abort_unless(PageTexts::exists($page), 404);

        $pages = PageTexts::pages();

        return view('admin.pages', [
            'page' => $page,
            'tabs' => collect($pages)->map(fn (array $p) => $p['label']),
            'groups' => $pages[$page]['groups'],
            'text' => SiteSetting::savedPageText($page), // hanya isian admin; teks bawaan jadi placeholder
            'defaults' => PageTexts::defaults($page),
            'previews' => PagePreviews::for($page),
            'images' => SiteImage::urls(),
        ]);
    }

    /** Simpan teks satu halaman. Kolom yang dikosongkan kembali memakai teks bawaan. */
    public function update(Request $request, string $page): RedirectResponse
    {
        abort_unless(PageTexts::exists($page), 404);

        $data = $request->validate(PageTexts::rules($page), PageTexts::messages($page));

        SiteSetting::savePageText($page, $data);

        return redirect()->route('admin.pages.edit', $page)
            ->with('status', 'Teks halaman '.PageTexts::pages()[$page]['label'].' berhasil disimpan.');
    }
}