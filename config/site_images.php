<?php

/*
|--------------------------------------------------------------------------
| Slot gambar situs
|--------------------------------------------------------------------------
| Setiap slot adalah satu tempat gambar di website (banner, logo, dll).
| Admin bisa mengganti isinya lewat /admin/gambar-situs. Selama belum diganti,
| website memakai gambar bawaan ("default") di bawah ini.
|
| Gambar bawaan memakai foto Unsplash (lisensi gratis). Parameter ?w=... membuat
| Unsplash mengirim ukuran yang pas, supaya halaman tetap ringan.
|
| width = lebar maksimum (px) saat gambar diunggah lewat admin.
| ratio = bentuk kotak (lebar / tinggi). Isi null untuk tidak memotong (logo).
*/

$u = 'https://images.unsplash.com/';

return [
    'logo' => [
        'label' => 'Logo perusahaan',
        'used_in' => 'Header dan footer di semua halaman',
        'width' => 600,
        'ratio' => null,
        'default' => '/images/logo.jpg', // file milik sendiri di public/images/logo.jpg
    ],
    'hero' => [
        'label' => 'Foto utama Beranda',
        'used_in' => 'Banner besar di halaman Beranda',
        'width' => 1600,
        'ratio' => 16 / 9,
        'default' => $u.'photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=75',
    ],
    'about_header' => [
        'label' => 'Banner halaman Tentang Kami',
        'used_in' => 'Bagian atas halaman Tentang Kami',
        'width' => 1600,
        'ratio' => 16 / 9,
        'default' => $u.'photo-1508385082359-f38ae991e8f2?auto=format&fit=crop&w=1600&q=75',
    ],
    'about' => [
        'label' => 'Foto profil perusahaan',
        'used_in' => 'Di samping teks "Perjalanan & Komitmen Kami"',
        'width' => 1200,
        'ratio' => 4 / 3,
        'default' => $u.'photo-1758691737124-05c5bffe46f0?auto=format&fit=crop&w=1200&q=75',
    ],
    'tujuan' => [
        'label' => 'Latar "Tujuan Perusahaan"',
        'used_in' => 'Latar gelap di halaman Tentang Kami',
        'width' => 1600,
        'ratio' => 16 / 9,
        'default' => $u.'photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=1600&q=75',
    ],
    'layanan' => [
        'label' => 'Foto "Melayani Semua Kalangan"',
        'used_in' => 'Kotak foto di bagian Keunggulan, halaman Tentang Kami',
        'width' => 1200,
        'ratio' => 4 / 5,
        'default' => $u.'photo-1758525226653-490405551ce9?auto=format&fit=crop&w=1200&q=75',
    ],
    'service_header' => [
        'label' => 'Banner halaman Bidang Usaha',
        'used_in' => 'Bagian atas halaman Bidang Usaha',
        'width' => 1600,
        'ratio' => 16 / 9,
        'default' => $u.'photo-1560179707-f14e90ef3623?auto=format&fit=crop&w=1600&q=75',
    ],
    'contact_header' => [
        'label' => 'Banner halaman Kontak',
        'used_in' => 'Bagian atas halaman Kontak',
        'width' => 1600,
        'ratio' => 16 / 9,
        'default' => $u.'photo-1512531123205-560f5974e686?auto=format&fit=crop&w=1600&q=75',
    ],
    'gallery_header' => [
        'label' => 'Banner halaman Galeri',
        'used_in' => 'Bagian atas halaman Galeri',
        'width' => 1600,
        'ratio' => 16 / 9,
        'default' => $u.'photo-1462206092226-f46025ffe607?auto=format&fit=crop&w=1600&q=75',
    ],
];