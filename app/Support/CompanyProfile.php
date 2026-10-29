<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;

/**
 * Kontak & alamat perusahaan yang bisa diubah admin lewat Admin > Kontak & Alamat.
 *
 * Nilai awal dari config/company.php. Bila admin pernah menyimpan, nilainya ada di tabel
 * site_settings (kunci "company.*") dan menimpa config('company') saat aplikasi mulai.
 */
final class CompanyProfile
{
    /** Kolom yang bisa diubah admin. Kunci di database: "company.{kolom}". */
    public const FIELDS = ['phone', 'email', 'address', 'short_address', 'maps_url', 'maps_query'];

    /** Dipanggil sekali saat aplikasi mulai (AppServiceProvider::boot). */
    public static function apply(): void
    {
        $saved = self::saved();

        if ($saved === []) {
            return; // belum pernah diubah admin: pakai config/company.php apa adanya
        }

        $company = array_merge(config('company'), $saved);

        // Nomor WhatsApp diturunkan dari nomor telepon (0811... -> 62811...)
        if (isset($saved['phone'])) {
            $company['wa'] = self::waNumber($saved['phone']);
        }

        // Peta di halaman Kontak dibuat dari kata pencarian yang diisi admin
        if (isset($saved['maps_query'])) {
            $company['maps_embed'] = self::embedUrl($saved['maps_query']);
        }

        Config::set('company', $company);
    }

    /**
     * Nilai yang disimpan admin (hanya yang terisi).
     *
     * @return array<string, string>
     */
    public static function saved(): array
    {
        try {
            $rows = SiteSetting::where('key', 'like', 'company.%')->pluck('value', 'key');
        } catch (\Throwable) {
            return []; // tabel belum ada (sebelum migrate) atau database belum siap
        }

        $saved = [];

        foreach (self::FIELDS as $field) {
            $value = $rows["company.$field"] ?? null;

            if (filled($value)) {
                $saved[$field] = trim($value);
            }
        }

        return $saved;
    }

    /**
     * Nilai yang sedang dipakai website, untuk mengisi form admin.
     *
     * @return array<string, string>
     */
    public static function current(): array
    {
        $company = config('company');
        $values = [];

        foreach (self::FIELDS as $field) {
            $values[$field] = (string) ($company[$field] ?? '');
        }

        // Kata pencarian peta belum pernah disimpan: ambil dari link peta bawaan
        if ($values['maps_query'] === '') {
            $values['maps_query'] = self::queryFromEmbed((string) ($company['maps_embed'] ?? ''));
        }

        return $values;
    }

    /** @param array<string, mixed> $values */
    public static function save(array $values): void
    {
        foreach (self::FIELDS as $field) {
            SiteSetting::updateOrCreate(
                ['key' => "company.$field"],
                ['value' => trim((string) ($values[$field] ?? ''))],
            );
        }
    }

    /** Ubah nomor yang diketik admin menjadi format WhatsApp: "0811-3561-43" atau "+62 811..." -> "62811..." */
    public static function waNumber(string $phone): string
    {
        $digits = (string) preg_replace('/\D/', '', $phone);

        return match (true) {
            str_starts_with($digits, '62') => $digits,
            str_starts_with($digits, '0') => '62'.substr($digits, 1),
            default => '62'.$digits,
        };
    }

    private static function embedUrl(string $query): string
    {
        return 'https://maps.google.com/maps?q='.rawurlencode($query).'&t=&z=15&ie=UTF8&iwloc=&output=embed';
    }

    private static function queryFromEmbed(string $url): string
    {
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        return (string) ($query['q'] ?? '');
    }
}