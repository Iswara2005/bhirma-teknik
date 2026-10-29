<?php

return [
    /*
    | Kategori foto. Dipakai sebagai pilihan (dropdown) di Admin > Galeri
    | dan otomatis menjadi tombol filter di halaman Galeri.
    */
    'categories' => ['Toko', 'Kegiatan'],

    /*
    | Foto contoh untuk GallerySeeder (php artisan db:seed). Hanya dipakai saat seeding:
    | foto-foto ini dimasukkan ke database sekali, lalu bisa diedit atau dihapus di Admin > Galeri.
    | Website TIDAK lagi menampilkan foto ini secara otomatis saat galeri kosong.
    |
    | Dibiarkan kosong supaya db:seed tidak memasukkan foto stok kembali.
    | Format tiap baris bila ingin diisi: [link foto, judul, kategori].
    */
    'defaults' => [
    // Toko
    ['https://images.unsplash.com/photo-1696433933590-1086d1d53133', 'Toko komputer dan perlengkapan', 'Toko'],
    ['https://images.unsplash.com/photo-1582268979139-f2a961b1ea15', 'Laptop dan perangkat komputer',  'Toko'],
    ['https://images.unsplash.com/photo-1456735190827-d1262f71b8a3', 'Alat tulis kantor',              'Toko'],
    ['https://images.unsplash.com/photo-1571854003494-ab1b14c21249', 'Rak display produk',             'Toko'],

    // Kegiatan
    ['https://images.unsplash.com/photo-1770992225357-21eb44fcf04a', 'Ruang kerja dan perangkat kantor', 'Kegiatan'],
    ['https://images.unsplash.com/photo-1677078610072-7f11ebc4d4d7', 'Diskusi tim',                       'Kegiatan'],
    ['https://images.unsplash.com/photo-1758873268933-e0765262e58d', 'Kerja tim',                         'Kegiatan'],
],
];