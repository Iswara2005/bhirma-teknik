<?php

namespace App\Models;

use App\Support\ImageStore;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'path'])]
class SiteImage extends Model
{
    /**
     * URL final untuk SEMUA slot di config/site_images.php:
     * gambar yang diunggah admin bila ada, selain itu gambar bawaan.
     *
     * @return array<string, string>
     */
    public static function urls(): array
    {
        $custom = static::pluck('path', 'key');

        return collect(config('site_images'))
            ->map(fn (array $slot, string $key) => isset($custom[$key])
                ? ImageStore::url($custom[$key])
                : ImageStore::url($slot['default']))
            ->all();
    }
}
