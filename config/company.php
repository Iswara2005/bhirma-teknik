<?php

/*
|--------------------------------------------------------------------------
| Data perusahaan
|--------------------------------------------------------------------------
| Teks yang jarang berubah. Gambar dan bidang usaha TIDAK di sini:
| keduanya dikelola admin lewat CMS (/admin).
*/

$phone = '0811356143';

return [
    'name' => 'CV. Bhirma Teknik',
    'tagline' => 'Mitra Perdagangan Eceran Terpercaya',
    'city' => 'Bangli, Bali',

    'phone' => $phone,
    // Nomor WhatsApp diturunkan dari nomor telepon: 0811... -> 62811...
    'wa' => '62'.ltrim($phone, '0'),
    'email' => 'cv.bhirmateknik21@gmail.com',

    'address' => 'Lingkungan Banjar Tegal, Kel. Bebalang, Kec. Bangli, Kab. Bangli, Provinsi Bali',
    'short_address' => 'Bebalang, Bangli, Bali',
    'maps_url' => 'https://maps.app.goo.gl/k3MPRg3jyjcU6nXu8',
    'maps_embed' => 'https://maps.google.com/maps?q=CV.%20Bhirma%20Teknik,%20Bangli,%20Bali&t=&z=15&ie=UTF8&iwloc=&output=embed',

    // TODO: ganti dengan visi & misi asli perusahaan (sekarang masih teks contoh).
    'visi' => 'menjadi perusahaan perdagangan eceran dan pengadaan resmi yang berpusat di Lingkungan Banjar Tegal, Kelurahan Bebalang, Kecamatan Bangli, Kabupaten Bangli, Bali.',

    'misi' => [
        'Menyediakan produk dan layanan berkualitas tinggi.',
        'Menjadi mitra terpercaya bagi pelanggan di berbagai kalangan.',
        'Meningkatkan kualitas pelayanan dan kepuasan pelanggan.',
    ],

    // Boleh berisi HTML (ditampilkan dengan {!! !!}), jadi isi hanya dari developer, bukan dari input pengunjung.
    'profil' => [
        '<strong>CV. Bhirma Teknik</strong> merupakan perusahaan perdagangan eceran dan pengadaan resmi yang berpusat di Lingkungan Banjar Tegal, Kelurahan Bebalang, Kecamatan Bangli, Kabupaten Bangli, Bali.',
        'Berkomitmen menjadi mitra terpercaya, kami hadir menyediakan solusi lengkap untuk berbagai sektor. mulai dari perangkat IT, komputer, piranti lunak (software), alat telekomunikasi, hingga perlengkapan listrik, furnitur, pakaian, alat tulis kantor (ATK), material bangunan, serta mesin kantor.',
        'Dengan mengutamakan kualitas produk dan respon pelayanan yang cepat, kami siap melayani pembelian eceran maupun pengadaan skala besar untuk perorangan, perusahaan swasta, sekolah, hingga instansi pemerintahan di Bali.',
    ],

    'goals' => [
        [
            'title' => 'Semua Kebutuhan, Satu Tempat',
            'desc' => 'Menyediakan beragam kebutuhan teknik dan perlengkapan agar pelanggan tidak perlu berpindah-pindah toko.',
        ],
        [
            'title' => 'Produk yang Layak Dipercaya',
            'desc' => 'Memilih produk yang sesuai kebutuhan, layak pakai, dan dapat diandalkan untuk pemakaian sehari-hari.',
        ],
        [
            'title' => 'Mitra Berbagai Kalangan',
            'desc' => 'Menjadi mitra pengadaan bagi perorangan, perusahaan swasta, sekolah, dan instansi pemerintahan di Bali.',
        ],
    ],

    'keuntungan' => [
        ['icon' => 'bi-lightning-charge-fill', 'title' => 'Respons Cepat',
            'desc' => 'Tanya produk, stok, dan penawaran langsung lewat WhatsApp.'],
        ['icon' => 'bi-box-seam-fill', 'title' => 'Eceran & Pengadaan',
            'desc' => 'Dari pembelian satuan sampai kebutuhan skala besar, semuanya dilayani.'],
        ['icon' => 'bi-geo-alt-fill', 'title' => 'Usaha Lokal Bangli',
            'desc' => 'Berpusat di Bebalang, Bangli, dekat dan mudah dijangkau pelanggan di Bali.'],
    ],
];
