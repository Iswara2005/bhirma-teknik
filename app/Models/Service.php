<?php

namespace App\Models;

use App\Support\ImageStore;
use App\Support\Whatsapp;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'image', 'desc', 'sort_order', 'is_active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /** Slug dari nama, ditambah -2, -3, ... bila sudah dipakai bidang usaha lain. */
    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'bidang-usaha';
        $slug = $base;

        for ($i = 2; static::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    public function items(): HasMany
    {
        return $this->hasMany(ServiceItem::class)->orderBy('sort_order')->orderBy('id');
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /** Data untuk kartu bidang usaha (beranda & halaman Bidang Usaha). */
    public function toCard(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'desc' => $this->desc,
            'image' => ImageStore::url($this->image),
            'wa_url' => Whatsapp::ask($this->name),
        ];
    }

    /** Data untuk halaman detail, termasuk daftar produknya. */
    public function toDetail(): array
    {
        return $this->toCard() + [
            'image_full' => ImageStore::fullUrl($this->image), // versi besar untuk zoom
            'items' => $this->items->map(fn (ServiceItem $item) => [
                'name' => $item->name,
                'desc' => $item->desc,
                'points' => $item->points ?? [],
                'wa_url' => Whatsapp::ask($item->name),
            ])->all(),
        ];
    }
}