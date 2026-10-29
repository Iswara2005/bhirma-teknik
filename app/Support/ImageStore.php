<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;


final class ImageStore
{
    private const DISK = 'media';

    /** Batas piksel (lebar x tinggi) yang aman diproses GD tanpa kehabisan memori. */
    private const MAX_PIXELS = 30_000_000;

    /** URL siap pakai untuk <img src>. */
    public static function url(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        return self::isExternal($path) ? $path : asset(ltrim($path, '/'));
    }

    /**
     * Simpan gambar unggahan ke folder $dir dan kembalikan path relatifnya.
     * Jika ekstensi GD aktif, gambar diperkecil ke lebar $maxWidth, orientasi
     * foto HP dibetulkan, dan metadata (EXIF/GPS) dibuang.
     *
     * $ratio (lebar / tinggi, mis. 4 / 3) = bentuk kotak tempat foto tampil.
     * Bila diisi, foto dipotong otomatis di bagian tengah agar pas dengan kotak,
     * jadi foto portrait, landscape, maupun persegi sama-sama bisa diunggah.
     * Isi null untuk tidak memotong (mis. logo).
     */
    public static function put(UploadedFile $file, string $dir, int $maxWidth, ?float $ratio = null): string
    {
        $extension = $file->guessExtension() ?: 'jpg';
        $path = trim($dir, '/').'/'.Str::random(40).'.'.$extension;

        $processed = self::resize($file->getRealPath(), $maxWidth, $ratio);

        if ($processed !== null) {
            Storage::disk(self::DISK)->put($path, $processed);
        } else {
            // GD tidak tersedia / gambar tidak bisa diproses: simpan apa adanya.
            Storage::disk(self::DISK)->putFileAs(trim($dir, '/'), $file, basename($path));
        }

        return $path;
    }

    /**
     * Seperti put(), tetapi menyimpan JUGA versi besar (tidak dipotong) untuk
     * fitur perbesar/zoom, supaya foto tidak pecah saat di-zoom.
     * Versi besar disimpan di samping file utama dengan akhiran "-full"
     * (mis. abc.jpg -> abc-full.jpg), sehingga tidak butuh kolom database baru.
     */
    public static function putWithFull(UploadedFile $file, string $dir, int $maxWidth, ?float $ratio = null, int $fullWidth = 2400): string
    {
        $path = self::put($file, $dir, $maxWidth, $ratio);
        $fullPath = self::fullPath($path);

        $processed = self::resize($file->getRealPath(), $fullWidth, null);

        if ($processed !== null) {
            Storage::disk(self::DISK)->put($fullPath, $processed);
        } else {
            Storage::disk(self::DISK)->putFileAs(dirname($fullPath), $file, basename($fullPath));
        }

        return $path;
    }

    /** URL versi besar untuk zoom; bila tidak ada (foto lama / Unsplash) pakai foto biasa. */
    public static function fullUrl(?string $path): string
    {
        if ($path === null || $path === '' || self::isExternal($path)) {
            return self::url($path);
        }

        $fullPath = self::fullPath($path);

        return Storage::disk(self::DISK)->exists($fullPath)
            ? asset(ltrim($fullPath, '/'))
            : self::url($path);
    }

    /** "uploads/services/abc.jpg" -> "uploads/services/abc-full.jpg" */
    private static function fullPath(string $path): string
    {
        return preg_replace('/(\.[^.\/]+)$/', '-full$1', $path);
    }

    /** Hapus file lama (termasuk versi besarnya). URL eksternal (Unsplash) tidak disentuh. */
    public static function delete(?string $path): void
    {
        if ($path !== null && $path !== '' && ! self::isExternal($path)) {
            foreach ([$path, self::fullPath($path)] as $file) {
                Storage::disk(self::DISK)->delete($file);
            }
        }
    }

    private static function isExternal(string $path): bool
    {
        return Str::startsWith($path, ['http://', 'https://']);
    }

    /** @return string|null isi file hasil olahan, atau null bila tidak bisa diproses */
    private static function resize(string $file, int $maxWidth, ?float $ratio): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        try {
            $info = @getimagesize($file);
            if ($info === false || $info[0] * $info[1] > self::MAX_PIXELS || ! self::fitsInMemory($info[0], $info[1])) {
                return null; // terlalu besar untuk diproses: simpan apa adanya, jangan sampai halaman error 500
            }
            $type = $info[2];

            $source = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($file),
                IMAGETYPE_PNG => @imagecreatefrompng($file),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file) : false,
                default => false,
            };
            if ($source === false) {
                return null;
            }

            if ($type === IMAGETYPE_JPEG) {
                $source = self::fixOrientation($source, $file);
            }

            $width = imagesx($source);
            $height = imagesy($source);

            // Area foto yang diambil (awalnya seluruh foto).
            $srcX = 0;
            $srcY = 0;
            $srcW = $width;
            $srcH = $height;

            // Potong di tengah agar bentuknya sama dengan kotak tujuan ($ratio).
            if ($ratio !== null && $ratio > 0) {
                if ($width / $height > $ratio) {
                    // Foto terlalu lebar: buang sisi kiri & kanan.
                    $srcW = (int) round($height * $ratio);
                    $srcX = intdiv($width - $srcW, 2);
                } else {
                    // Foto terlalu tinggi (portrait): buang sisi atas & bawah.
                    $srcH = (int) round($width / $ratio);
                    $srcY = intdiv($height - $srcH, 2);
                }
            }

            // Ukuran hasil: diperkecil bila lebih lebar dari $maxWidth, tidak pernah diperbesar.
            $dstW = min($srcW, $maxWidth);
            $dstH = max(1, (int) round($srcH * $dstW / $srcW));

            if ($srcW !== $dstW || $srcH !== $height || $srcW !== $width) {
                $out = imagecreatetruecolor($dstW, $dstH);
                imagealphablending($out, false);
                imagesavealpha($out, true);
                imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));
                imagecopyresampled($out, $source, 0, 0, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
                $source = $out;
            } else {
                imagesavealpha($source, true);
            }

            ob_start();
            match ($type) {
                IMAGETYPE_JPEG => imagejpeg($source, null, 82),
                IMAGETYPE_PNG => imagepng($source, null, 6),
                IMAGETYPE_WEBP => imagewebp($source, null, 82),
            };

            return ob_get_clean() ?: null;
        } catch (Throwable) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            return null;
        }
    }

        /**
     * GD memakai sekitar 4 byte per piksel, dan beberapa salinan ada di memori sekaligus
     * (asli, hasil putar, hasil perkecil). Kehabisan memori adalah error fatal yang tidak
     * bisa ditangkap try/catch, jadi dicek dulu: bila tidak cukup, foto disimpan apa adanya.
     */
    private static function fitsInMemory(int $width, int $height): bool
    {
        $limit = ini_parse_quantity((string) ini_get('memory_limit'));
        if ($limit <= 0) {
            return true; // -1 = tanpa batas
        }

        $needed = (int) ($width * $height * 4 * 2.5);

        return $needed < $limit - memory_get_usage(true);
    }

    /** Foto dari HP sering "landscape" di file aslinya; putar sesuai data EXIF. */
    private static function fixOrientation(\GdImage $image, string $file): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = @exif_read_data($file)['Orientation'] ?? 1;
        $angle = match ($orientation) {
            3 => 180,
            6 => -90,
            8 => 90,
            default => 0,
        };

        return $angle === 0 ? $image : (imagerotate($image, $angle, 0) ?: $image);
    }
}