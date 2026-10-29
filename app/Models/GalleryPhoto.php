<?php

namespace App\Models;

use App\Support\ImageStore;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'category', 'image', 'sort_order', 'is_active'])]
class GalleryPhoto extends Model
{
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /** Data untuk halaman galeri: foto ringan untuk grid, versi besar untuk lightbox. */
    public function toCard(): array
    {
        return self::makeCard($this->title, $this->category, $this->image);
    }

    /**
     * Dipakai juga untuk foto bawaan (config/gallery.php), jadi tidak harus dari database.
     * Link Unsplash otomatis diperkecil: grid ~800 px, lightbox ~2000 px.
     */
    public static function makeCard(?string $title, ?string $category, string $path): array
    {
        $image = ImageStore::url($path);
        $full = ImageStore::fullUrl($path);

        if (str_contains($path, 'images.unsplash.com')) {
            $base = strtok($path, '?');
            $image = $base.'?auto=format&fit=crop&w=800&q=70';
            $full = $base.'?auto=format&w=2000&q=80';
        }

        return [
            'title' => $title,
            'category' => $category,
            'image' => $image,
            'image_full' => $full,
        ];
    }
}