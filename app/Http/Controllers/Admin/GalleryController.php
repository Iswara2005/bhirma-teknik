<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryPhotoRequest;
use App\Models\GalleryPhoto;
use App\Support\ImageStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GalleryController extends Controller
{
    /** Lebar maksimum foto galeri (px). Tidak dipotong: kotak grid memotong sendiri lewat CSS. */
    private const IMAGE_WIDTH = 1600;

    /** Daftar foto galeri. */
    public function index(): View
    {
        return view('admin.gallery.index', [
            'photos' => GalleryPhoto::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['photo' => new GalleryPhoto(['is_active' => true])]);
    }

    public function store(GalleryPhotoRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $image = $this->savePhoto($request);

        try {
            $photo = GalleryPhoto::create([
                'title' => $data['title'] ?? null,
                'category' => $data['category'],
                'image' => $image,
                'sort_order' => $data['sort_order'] ?? ((int) GalleryPhoto::max('sort_order') + 1),
                'is_active' => $request->boolean('is_active'),
            ]);
        } catch (\Throwable $e) {
            ImageStore::delete($image); // jangan tinggalkan file yatim bila data gagal disimpan
            throw $e;
        }

        return redirect()->route('admin.gallery.index')
            ->with('status', "Foto \"{$this->label($photo)}\" berhasil ditambahkan.");
    }

    public function edit(GalleryPhoto $photo): View
    {
        return view('admin.gallery.form', ['photo' => $photo]);
    }

    public function update(GalleryPhotoRequest $request, GalleryPhoto $photo): RedirectResponse
    {
        $data = $request->validated();
        $oldImage = $photo->image;

        $photo->fill([
            'title' => $data['title'] ?? null,
            'category' => $data['category'],
            'sort_order' => $data['sort_order'] ?? $photo->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('image')) {
            $photo->image = $this->savePhoto($request);
        }

        $photo->save();

        // Foto lama dihapus dari server hanya bila diganti (link Unsplash tidak disentuh)
        if ($photo->wasChanged('image')) {
            ImageStore::delete($oldImage);
        }

        return redirect()->route('admin.gallery.index')
            ->with('status', "Foto \"{$this->label($photo)}\" berhasil diperbarui.");
    }

    public function destroy(GalleryPhoto $photo): RedirectResponse
    {
        $label = $this->label($photo);

        ImageStore::delete($photo->image);
        $photo->delete();

        return redirect()->route('admin.gallery.index')
            ->with('status', "Foto \"{$label}\" dihapus.");
    }

    /**
     * Simpan foto galeri: diperkecil, EXIF dibuang, dan disimpan juga versi besarnya
     * untuk zoom di lightbox. Dipakai oleh store() dan update().
     */
    private function savePhoto(GalleryPhotoRequest $request): string
    {
        return ImageStore::putWithFull(
            $request->file('image'),
            'uploads/gallery',
            self::IMAGE_WIDTH,
        );
    }

    /** Nama yang ditampilkan di pesan: judul foto, atau kategorinya bila judul kosong. */
    private function label(GalleryPhoto $photo): string
    {
        return $photo->title ?: ($photo->category ?: 'Foto');
    }
}