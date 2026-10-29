<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteImageRequest;
use App\Models\SiteImage;
use App\Support\ImageStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SiteImageController extends Controller
{
        public function index(): View
    {
        $urls = SiteImage::urls();                    // aturan "custom atau bawaan" hanya ada di sini
        $custom = SiteImage::pluck('path', 'key');    // dipakai hanya untuk tahu slot mana yang sudah diganti

        $slots = collect(config('site_images'))->map(fn (array $slot, string $key) => $slot + [
            'key' => $key,
            'is_custom' => isset($custom[$key]),
            'url' => $urls[$key],
        ]);

        return view('admin.images.index', ['slots' => $slots]);
    }

    public function update(SiteImageRequest $request, string $key): RedirectResponse
    {
        $slot = $this->slot($key);

        $old = SiteImage::where('key', $key)->value('path');
        $path = ImageStore::put($request->file('image'), 'uploads/site', $slot['width'], $slot['ratio'] ?? null);

        SiteImage::updateOrCreate(['key' => $key], ['path' => $path]);
        ImageStore::delete($old);

        return back()->with('status', "Gambar \"{$slot['label']}\" berhasil diganti.");
    }

    /** Kembalikan slot ke gambar bawaan. */
    public function destroy(string $key): RedirectResponse
    {
        $slot = $this->slot($key);

        $image = SiteImage::where('key', $key)->first();
        if ($image) {
            ImageStore::delete($image->path);
            $image->delete();
        }

        return back()->with('status', "Gambar \"{$slot['label']}\" dikembalikan ke gambar bawaan.");
    }

    /** @return array<string, mixed> */
    private function slot(string $key): array
    {
        $slots = config('site_images');
        abort_unless(array_key_exists($key, $slots), 404);

        return $slots[$key];
    }
}