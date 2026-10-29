<?php

namespace App\Models;

use App\Support\PageTexts;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

/**
 * Pengaturan teks situs yang bisa diubah admin (pasangan kunci -> isi).
 * Saat ini dipakai untuk teks banner halaman Galeri: kunci "gallery.<nama>".
 */
#[Fillable(['key', 'value'])]
class SiteSetting extends Model
{
    /** Bagian teks banner galeri yang bisa diubah admin. */
    public const GALLERY_FIELDS = ['eyebrow', 'title', 'subtitle', 'lead'];

    /** Teks bawaan, dipakai selama admin belum mengisi atau bila kolom dikosongkan. */
    public static function galleryDefaults(): array
    {
        $company = config('company');

        return [
            'eyebrow' => 'Galeri',
            'title' => 'Lihat lebih dekat.',
            'subtitle' => 'Toko dan kegiatan kami.',
            'lead' => 'Sekilas suasana toko dan kegiatan '.$company['name'].' di '.$company['city'].'.',
        ];
    }

    /**
     * Teks banner galeri yang siap tampil: isian admin, atau teks bawaan bila kosong.
     *
     * @return array{eyebrow: string, title: string, subtitle: string, lead: string}
     */
    public static function gallery(): array
    {
        $defaults = static::galleryDefaults();

        try {
            $saved = static::where('key', 'like', 'gallery.%')->pluck('value', 'key');
        } catch (QueryException $e) {
            report($e); // tabel belum dibuat: pakai teks bawaan, jangan matikan halaman

            return $defaults;
        }

        return collect($defaults)
            ->map(fn (string $default, string $field) => filled($saved["gallery.$field"] ?? null)
                ? $saved["gallery.$field"]
                : $default)
            ->all();
    }

    /** Simpan teks banner galeri dari form admin. */
    public static function saveGallery(array $values): void
    {
        foreach (self::GALLERY_FIELDS as $field) {
            static::updateOrCreate(
                ['key' => "gallery.$field"],
                ['value' => $values[$field] ?? null],
            );
        }
    }

    
    /**
     * Teks satu halaman publik (daftarnya di App\Support\PageTexts):
     * isian admin, atau teks bawaan bila kosong.
     *
     * @return array<string, string>
     */
    public static function pageText(string $page): array
    {
        $defaults = PageTexts::defaults($page);

        try {
            $saved = static::where('key', 'like', "$page.%")->pluck('value', 'key');
        } catch (QueryException $e) {
            report($e); // tabel belum dibuat: pakai teks bawaan, jangan matikan halaman

            return $defaults;
        }

        return collect($defaults)
            ->map(fn (string $default, string $field) => filled($saved["$page.$field"] ?? null)
                ? $saved["$page.$field"]
                : $default)
            ->all();
    }

    /**
     * Hanya teks yang benar-benar diisi admin (tanpa teks bawaan). Dipakai untuk mengisi form
     * di admin, supaya kolom yang belum diubah tetap kosong dan tidak ikut tersimpan
     * sebagai salinan teks bawaan.
     *
     * @return array<string, string>
     */
    public static function savedPageText(string $page): array
    {
        try {
            $saved = static::where('key', 'like', "$page.%")->pluck('value', 'key');
        } catch (QueryException $e) {
            report($e);

            return [];
        }

        return collect(PageTexts::defaults($page))
            ->mapWithKeys(fn (string $default, string $field) => [
                $field => (string) ($saved["$page.$field"] ?? ''),
            ])
            ->all();
    }

    /**
     * Simpan teks satu halaman dari form admin.
     * Kolom kosong, atau isinya sama persis dengan teks bawaan, disimpan sebagai null
     * (artinya: website memakai teks bawaan, sehingga perubahan teks bawaan ikut berlaku).
     */
    public static function savePageText(string $page, array $values): void
    {
        foreach (PageTexts::defaults($page) as $field => $default) {
            $value = trim((string) ($values[$field] ?? ''));

            static::updateOrCreate(
                ['key' => "$page.$field"],
                ['value' => ($value === '' || $value === trim($default)) ? null : $value],
            );
        }
    }
}