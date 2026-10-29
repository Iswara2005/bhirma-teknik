<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Support\ImageStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /** Lebar maksimum gambar kartu bidang usaha (px). */
    private const IMAGE_WIDTH = 1000;

    /** Bentuk kotak foto di website (4:3). Foto apa pun dipotong otomatis agar pas. */
    private const IMAGE_RATIO = 4 / 3;

    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::ordered()->withCount('items')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service(['is_active' => true])]);
    }

        public function store(ServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $image = $this->savePhoto($request);

        try {
            $service = Service::create([
                'name' => $data['name'],
                'desc' => $data['desc'],
                'slug' => Service::uniqueSlug($data['name']),
                'image' => $image,
                'sort_order' => $data['sort_order'] ?? ((int) Service::max('sort_order') + 1),
                'is_active' => $request->boolean('is_active'),
            ]);

            $this->syncItems($service, $request);
        } catch (\Throwable $e) {
            ImageStore::delete($image); // jangan tinggalkan file yatim bila data gagal disimpan
            isset($service) && $service->delete();
            throw $e;
        }

        return redirect()->route('admin.services.index')
            ->with('status', "Bidang usaha \"{$service->name}\" berhasil ditambahkan.");
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();
        $oldImage = $service->image;

        $service->fill([
            'name' => $data['name'],
            'desc' => $data['desc'],
            'sort_order' => $data['sort_order'] ?? $service->sort_order,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('image')) {
            $service->image = $this->savePhoto($request);
        }

        $service->save();

        $this->syncItems($service, $request);

        if ($service->wasChanged('image')) {
            ImageStore::delete($oldImage);
        }

        return redirect()->route('admin.services.index')
            ->with('status', "Bidang usaha \"{$service->name}\" berhasil diperbarui.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        ImageStore::delete($service->image);
        $service->delete(); // produk di dalamnya ikut terhapus (cascade)

        return redirect()->route('admin.services.index')
            ->with('status', "Bidang usaha \"{$service->name}\" dihapus.");
    }

    /**
     * Simpan foto bidang usaha: dipotong 4:3, diperkecil, dan disimpan juga
     * versi besarnya untuk fitur zoom. Dipakai oleh store() dan update(),
     * jadi pengaturan foto cukup diubah di satu tempat ini.
     */
    private function savePhoto(ServiceRequest $request): string
    {
        return ImageStore::putWithFull(
            $request->file('image'),
            'uploads/services',
            self::IMAGE_WIDTH,
            self::IMAGE_RATIO,
        );
    }

    /**
     * Ganti seluruh daftar produk dengan isi form. Hanya berjalan bila form
     * mengirim penanda "items_present", supaya permintaan tanpa bagian produk
     * tidak menghapus produk yang sudah ada.
     */
    private function syncItems(Service $service, ServiceRequest $request): void
    {
        if (! $request->has('items_present')) {
            return;
        }

        $items = array_values($request->validated('items') ?? []);

        DB::transaction(function () use ($service, $items) {
            ServiceItem::where('service_id', $service->id)->delete();

            foreach ($items as $i => $item) {
                $service->items()->create([
                    'name' => $item['name'],
                    'desc' => $item['desc'],
                    'points' => array_values($item['points'] ?? []),
                    'sort_order' => $i + 1,
                ]);
            }
        });
    }
}