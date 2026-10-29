<?php

/*
| Data awal bidang usaha beserta produknya (dipakai ServiceSeeder).
| Setelah di-seed, semua ini dikelola lewat CMS / database, bukan file ini.
*/

return [
    [
        'name' => 'Piranti Lunak (Software)',
        'label' => 'Software',
        'image' => 'https://images.unsplash.com/photo-1677078610072-7f11ebc4d4d7?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Sistem operasi, aplikasi produktivitas kantor, software keamanan, dan lisensi resmi.',
        'items' => [
            [
                'name' => 'Sistem operasi',
                'desc' => 'Sistem operasi untuk komputer dan laptop kantor, sekolah, maupun pribadi.',
                'points' => [
                    'Tersedia dalam lisensi resmi',
                    'Untuk satu perangkat maupun banyak perangkat',
                    'Sebutkan spesifikasi komputer Anda, kami bantu pilihkan',
                ],
            ],
            [
                'name' => 'Aplikasi perkantoran',
                'desc' => 'Aplikasi untuk mengolah dokumen, lembar kerja, dan presentasi sehari-hari.',
                'points' => [
                    'Dokumen, spreadsheet, dan presentasi',
                    'Cocok untuk kantor dan sekolah',
                    'Pilihan lisensi sesuai jumlah pengguna',
                ],
            ],
            [
                'name' => 'Antivirus dan software keamanan',
                'desc' => 'Perlindungan komputer dari virus, malware, dan ancaman lain saat berinternet.',
                'points' => [
                    'Melindungi komputer secara berkelanjutan',
                    'Untuk perorangan maupun banyak perangkat',
                    'Lisensi resmi dengan masa berlangganan',
                ],
            ],
            [
                'name' => 'Aplikasi akuntansi',
                'desc' => 'Aplikasi pencatatan keuangan dan pembukuan untuk usaha kecil hingga menengah.',
                'points' => [
                    'Pencatatan transaksi dan laporan keuangan',
                    'Cocok untuk toko dan perusahaan',
                    'Konsultasikan kebutuhan usaha Anda dulu',
                ],
            ],
            [
                'name' => 'Software desain',
                'desc' => 'Aplikasi untuk mengolah foto, membuat desain grafis, dan materi cetak.',
                'points' => [
                    'Pengolah gambar dan desain grafis',
                    'Untuk percetakan, sekolah, dan usaha',
                    'Tersedia pilihan lisensi',
                ],
            ],
        ],
    ],
    [
        'name' => 'Komputer dan Perlengkapannya',
        'label' => 'Komputer',
        'image' => 'https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Unit komputer PC, laptop, printer, tinta, scanner, serta komponen pendukung IT lainnya.',
        'items' => [
            [
                'name' => 'Laptop',
                'desc' => 'Laptop untuk kerja kantor, belajar, dan kebutuhan harian.',
                'points' => [
                    'Pilihan untuk pekerjaan ringan hingga berat',
                    'Pembelian satuan atau banyak unit',
                    'Sebutkan anggaran dan kebutuhan, kami bantu pilihkan',
                ],
            ],
            [
                'name' => 'PC dan all-in-one',
                'desc' => 'Komputer desktop untuk ruang kerja, laboratorium, dan administrasi.',
                'points' => [
                    'Unit lengkap sesuai kebutuhan',
                    'Cocok untuk kantor dan lab sekolah',
                    'Pengadaan jumlah besar dilayani',
                ],
            ],
            [
                'name' => 'Printer',
                'desc' => 'Printer untuk mencetak dokumen, formulir, dan laporan.',
                'points' => [
                    'Pilihan tipe sesuai volume cetak',
                    'Untuk rumah hingga kantor',
                    'Tinta dan toner tersedia terpisah',
                ],
            ],
            [
                'name' => 'Tinta dan toner',
                'desc' => 'Tinta dan toner pengganti agar printer tetap berfungsi.',
                'points' => [
                    'Sesuaikan dengan tipe printer Anda',
                    'Tersedia satuan maupun jumlah besar',
                    'Sebutkan tipe printer saat bertanya',
                ],
            ],
            [
                'name' => 'Scanner',
                'desc' => 'Scanner untuk mengubah dokumen kertas menjadi berkas digital.',
                'points' => [
                    'Untuk arsip dan administrasi',
                    'Cocok untuk kantor dan instansi',
                    'Pilihan sesuai volume pindai',
                ],
            ],
        ],
    ],
    [
        'name' => 'Alat Telekomunikasi',
        'label' => 'Telekomunikasi',
        'image' => 'https://images.unsplash.com/photo-1742534620800-fc7caae0dd45?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Perangkat komunikasi, modem, router, perlengkapan jaringan, dan aksesorisnya.',
        'items' => [
            [
                'name' => 'Modem',
                'desc' => 'Modem untuk menyambungkan perangkat ke jaringan internet.',
                'points' => [
                    'Untuk rumah dan kantor kecil',
                    'Sesuaikan dengan layanan internet yang dipakai',
                    'Tanyakan kecocokan sebelum membeli',
                ],
            ],
            [
                'name' => 'Router',
                'desc' => 'Router untuk membagi koneksi internet ke banyak perangkat.',
                'points' => [
                    'Jaringan kabel dan Wi-Fi',
                    'Cocok untuk kantor dan ruang kelas',
                    'Pilihan sesuai jumlah pengguna',
                ],
            ],
            [
                'name' => 'Access point',
                'desc' => 'Perangkat untuk memperluas sinyal Wi-Fi di ruangan yang luas.',
                'points' => [
                    'Jangkauan sinyal lebih merata',
                    'Cocok untuk kantor, sekolah, dan toko',
                    'Bisa dipasang lebih dari satu',
                ],
            ],
            [
                'name' => 'Kabel dan konektor jaringan',
                'desc' => 'Kabel dan konektor untuk membangun jaringan kabel yang rapi.',
                'points' => [
                    'Kabel LAN dan konektor',
                    'Panjang sesuai kebutuhan',
                    'Untuk instalasi rumah dan kantor',
                ],
            ],
            [
                'name' => 'Perangkat komunikasi',
                'desc' => 'Telepon dan perangkat komunikasi pendukung untuk kantor.',
                'points' => [
                    'Untuk komunikasi harian di kantor',
                    'Aksesori pendukung tersedia',
                    'Tanyakan jenis yang Anda cari',
                ],
            ],
        ],
    ],
    [
        'name' => 'Peralatan Listrik & Penerangan',
        'label' => 'Listrik & Penerangan',
        'image' => 'https://images.unsplash.com/photo-1475783006851-1d68dd683eff?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Lampu LED, kabel listrik, stopkontak, sakelar, dan komponen instalasi listrik rumah/kantor.',
        'items' => [
            [
                'name' => 'Lampu LED',
                'desc' => 'Lampu hemat energi untuk rumah, kantor, dan ruang usaha.',
                'points' => [
                    'Hemat daya',
                    'Pilihan daya dan warna cahaya',
                    'Cocok untuk penerangan harian',
                ],
            ],
            [
                'name' => 'Kabel listrik',
                'desc' => 'Kabel untuk instalasi listrik rumah dan gedung.',
                'points' => [
                    'Pilihan ukuran sesuai beban',
                    'Untuk instalasi baru dan perbaikan',
                    'Sebutkan ukuran dan panjang yang dibutuhkan',
                ],
            ],
            [
                'name' => 'Stopkontak',
                'desc' => 'Stopkontak untuk titik listrik di dinding maupun sambungan tambahan.',
                'points' => [
                    'Model tanam dan model luar',
                    'Pilihan jumlah lubang',
                    'Cocok untuk rumah dan kantor',
                ],
            ],
            [
                'name' => 'Sakelar',
                'desc' => 'Sakelar untuk menyalakan dan mematikan lampu atau peralatan.',
                'points' => [
                    'Model tunggal dan ganda',
                    'Tersedia model tanam dan luar',
                    'Untuk instalasi baru maupun penggantian',
                ],
            ],
            [
                'name' => 'MCB dan komponen instalasi',
                'desc' => 'Pengaman dan komponen pendukung instalasi listrik.',
                'points' => [
                    'Pengaman arus untuk instalasi',
                    'Pilihan sesuai kapasitas listrik',
                    'Untuk rumah, kantor, dan proyek',
                ],
            ],
        ],
    ],
    [
        'name' => 'Furnitur',
        'label' => 'Furnitur',
        'image' => 'https://images.unsplash.com/photo-1560185007-5f0bb1866cab?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Meja kerja, kursi kantor, lemari arsip, rak penyimpanan, dan perabot penunjang ruangan.',
        'items' => [
            [
                'name' => 'Meja kerja',
                'desc' => 'Meja untuk ruang kerja, ruang kelas, dan area administrasi.',
                'points' => [
                    'Pilihan ukuran sesuai ruangan',
                    'Untuk kantor dan sekolah',
                    'Pembelian satuan atau banyak unit',
                ],
            ],
            [
                'name' => 'Kursi kantor',
                'desc' => 'Kursi kerja yang nyaman untuk pemakaian sepanjang hari.',
                'points' => [
                    'Model kursi kerja dan kursi tamu',
                    'Cocok untuk kantor dan ruang rapat',
                    'Tersedia untuk pengadaan jumlah besar',
                ],
            ],
            [
                'name' => 'Lemari arsip',
                'desc' => 'Lemari untuk menyimpan berkas dan dokumen dengan rapi.',
                'points' => [
                    'Pilihan jumlah laci dan ukuran',
                    'Untuk arsip kantor dan instansi',
                    'Membantu dokumen tetap tertata',
                ],
            ],
            [
                'name' => 'Rak penyimpanan',
                'desc' => 'Rak untuk menata barang, buku, dan perlengkapan.',
                'points' => [
                    'Pilihan ukuran dan jumlah tingkat',
                    'Untuk gudang, toko, dan kantor',
                    'Sesuaikan dengan ruang yang tersedia',
                ],
            ],
            [
                'name' => 'Meja rapat',
                'desc' => 'Meja besar untuk ruang rapat dan diskusi tim.',
                'points' => [
                    'Pilihan kapasitas sesuai jumlah peserta',
                    'Untuk kantor dan instansi',
                    'Bisa dipadukan dengan kursi',
                ],
            ],
        ],
    ],
    [
        'name' => 'Pakaian',
        'label' => 'Pakaian',
        'image' => 'https://images.unsplash.com/photo-1679212622264-646085f5653f?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Penyediaan perlengkapan seragam kerja, pakaian operasional, dan kebutuhan tekstil.',
        'items' => [
            [
                'name' => 'Seragam kerja',
                'desc' => 'Seragam untuk karyawan, staf, dan petugas lapangan.',
                'points' => [
                    'Pesanan jumlah besar dilayani',
                    'Ukuran disesuaikan kebutuhan',
                    'Cocok untuk perusahaan dan instansi',
                ],
            ],
            [
                'name' => 'Pakaian operasional',
                'desc' => 'Pakaian kerja untuk kegiatan lapangan dan operasional harian.',
                'points' => [
                    'Untuk pekerjaan yang butuh pakaian khusus',
                    'Pilihan model sesuai bidang kerja',
                    'Sebutkan jumlah dan ukuran saat bertanya',
                ],
            ],
            [
                'name' => 'Kaos dan kemeja',
                'desc' => 'Kaos dan kemeja untuk acara, komunitas, dan keperluan kantor.',
                'points' => [
                    'Pilihan model dan ukuran',
                    'Cocok untuk kegiatan bersama',
                    'Pembelian satuan atau banyak',
                ],
            ],
            [
                'name' => 'Rompi dan jaket',
                'desc' => 'Rompi dan jaket untuk petugas dan kegiatan di luar ruangan.',
                'points' => [
                    'Untuk tim lapangan',
                    'Pilihan model sesuai kebutuhan',
                    'Pemesanan jumlah besar',
                ],
            ],
            [
                'name' => 'Kain dan tekstil',
                'desc' => 'Bahan tekstil untuk kebutuhan pakaian dan perlengkapan lainnya.',
                'points' => [
                    'Pilihan jenis bahan',
                    'Untuk kebutuhan menjahit dan produksi',
                    'Tanyakan ketersediaan bahan',
                ],
            ],
        ],
    ],
    [
        'name' => 'Alat Tulis Menulis dan Gambar',
        'label' => 'ATK',
        'image' => 'https://images.unsplash.com/photo-1722928755316-a2b02a8d2e00?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Kertas, buku, pulpen, map, dan perlengkapan tulis serta gambar untuk kantor dan sekolah.',
        'items' => [
            [
                'name' => 'Kertas',
                'desc' => 'Kertas untuk cetak, fotokopi, dan keperluan administrasi.',
                'points' => [
                    'Pilihan ukuran seperti A4 dan F4',
                    'Untuk kantor dan sekolah',
                    'Tersedia dalam jumlah besar',
                ],
            ],
            [
                'name' => 'Buku tulis dan agenda',
                'desc' => 'Buku untuk mencatat, belajar, dan agenda harian.',
                'points' => [
                    'Berbagai ukuran dan isi halaman',
                    'Cocok untuk sekolah dan kantor',
                    'Pembelian satuan atau banyak',
                ],
            ],
            [
                'name' => 'Pulpen dan pensil',
                'desc' => 'Alat tulis untuk menulis, mencatat, dan menandatangani dokumen.',
                'points' => [
                    'Pilihan jenis dan warna',
                    'Untuk keperluan harian',
                    'Tersedia satuan dan paket',
                ],
            ],
            [
                'name' => 'Map dan folder',
                'desc' => 'Map dan folder untuk menata dan menyimpan berkas.',
                'points' => [
                    'Pilihan bahan dan ukuran',
                    'Untuk arsip kantor dan sekolah',
                    'Membantu dokumen tetap rapi',
                ],
            ],
            [
                'name' => 'Perlengkapan gambar',
                'desc' => 'Alat gambar untuk pelajaran, desain, dan kerja teknik.',
                'points' => [
                    'Pensil gambar, penggaris, dan perlengkapan lain',
                    'Cocok untuk sekolah dan teknik',
                    'Sebutkan alat yang Anda cari',
                ],
            ],
        ],
    ],
    [
        'name' => 'Material Bangunan',
        'label' => 'Material Bangunan',
        'image' => 'https://images.unsplash.com/photo-1629608564457-5d74829a9e14?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Bahan dan perlengkapan bangunan untuk kebutuhan renovasi maupun proyek.',
        'items' => [
            [
                'name' => 'Cat',
                'desc' => 'Cat untuk dinding, kayu, dan besi pada renovasi maupun proyek.',
                'points' => [
                    'Pilihan jenis dan warna',
                    'Untuk bagian dalam dan luar bangunan',
                    'Perlengkapan mengecat tersedia',
                ],
            ],
            [
                'name' => 'Semen dan perekat',
                'desc' => 'Bahan dasar untuk pasangan dan pekerjaan konstruksi.',
                'points' => [
                    'Untuk pekerjaan pasangan dan plesteran',
                    'Pilihan perekat sesuai bahan',
                    'Jumlah besar untuk proyek',
                ],
            ],
            [
                'name' => 'Pipa dan fitting',
                'desc' => 'Pipa dan sambungannya untuk saluran air.',
                'points' => [
                    'Pilihan ukuran pipa',
                    'Fitting pendukung tersedia',
                    'Untuk rumah dan proyek',
                ],
            ],
            [
                'name' => 'Keramik dan lantai',
                'desc' => 'Bahan pelapis lantai dan dinding untuk renovasi.',
                'points' => [
                    'Pilihan ukuran dan motif',
                    'Tanyakan ketersediaan stok',
                    'Hitung kebutuhan sesuai luas ruangan',
                ],
            ],
            [
                'name' => 'Perkakas tangan',
                'desc' => 'Perkakas untuk pekerjaan perbaikan dan pemasangan.',
                'points' => [
                    'Palu, obeng, tang, dan lainnya',
                    'Untuk rumah dan bengkel',
                    'Tersedia satuan atau paket',
                ],
            ],
        ],
    ],
    [
        'name' => 'Mesin Kantor',
        'label' => 'Mesin Kantor',
        'image' => 'https://images.unsplash.com/photo-1706895040634-62055892cbbb?auto=format&fit=crop&w=1200&q=75',
        'desc' => 'Mesin fotokopi, mesin hitung, penghancur kertas, dan peralatan penunjang administrasi.',
        'items' => [
            [
                'name' => 'Mesin fotokopi',
                'desc' => 'Mesin untuk menggandakan dokumen dalam jumlah besar.',
                'points' => [
                    'Pilihan sesuai volume penggandaan',
                    'Cocok untuk kantor, sekolah, dan instansi',
                    'Tanyakan ketersediaan unit',
                ],
            ],
            [
                'name' => 'Mesin hitung',
                'desc' => 'Mesin hitung untuk perhitungan keuangan dan kasir.',
                'points' => [
                    'Model kalkulator meja',
                    'Untuk toko dan bagian keuangan',
                    'Pembelian satuan atau banyak',
                ],
            ],
            [
                'name' => 'Penghancur kertas',
                'desc' => 'Alat untuk memusnahkan dokumen rahasia secara aman.',
                'points' => [
                    'Mencacah kertas menjadi potongan kecil',
                    'Menjaga kerahasiaan dokumen',
                    'Cocok untuk kantor dan instansi',
                ],
            ],
            [
                'name' => 'Mesin laminating',
                'desc' => 'Mesin untuk melapisi dokumen agar tahan lama.',
                'points' => [
                    'Melindungi kertas dari air dan kusut',
                    'Untuk sertifikat, kartu, dan dokumen',
                    'Tanyakan ukuran plastik laminating',
                ],
            ],
            [
                'name' => 'Mesin penjilid',
                'desc' => 'Mesin untuk menjilid laporan dan dokumen agar rapi.',
                'points' => [
                    'Untuk laporan dan proposal',
                    'Cocok untuk kantor dan percetakan kecil',
                    'Tanyakan jenis jilidan yang dibutuhkan',
                ],
            ],
        ],
    ],
];