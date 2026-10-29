<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Isi bidang usaha awal. Aman dijalankan berulang: bidang usaha yang
     * sudah ada (dan mungkin sudah diedit admin) tidak ditimpa.
     */
    public function run(): void
    {
        foreach (require __DIR__.'/data/services.php' as $order => $data) {
            $service = Service::firstOrCreate(
                ['slug' => Str::slug($data['label'])],
                [
                    'name' => $data['label'],
                    'image' => $data['image'],
                    'desc' => $data['desc'],
                    'sort_order' => $order + 1,
                ],
            );

            if (! $service->wasRecentlyCreated) {
                continue;
            }

            foreach ($data['items'] as $i => $item) {
                $service->items()->create($item + ['sort_order' => $i + 1]);
            }
        }
    }
}