<?php

namespace App\Support;

class PageTexts
{
    /** @return array<string, array{label: string, groups: array}> */
    public static function pages(): array
    {
        $company = config('company');
        $name = $company['name'];
        $city = $company['city'];
        $gallery = \App\Models\SiteSetting::galleryDefaults();

        // Pembantu kecil: bentuk pengaturan satu kolom (label, batas karakter, teks bawaan, textarea?)
        $f = fn (string $label, int $max, string $default, bool $area = false): array => [
            'label' => $label, 'max' => $max, 'default' => $default, 'area' => $area,
        ];

        // Teks bawaan yang diambil dari config/company.php
        $profil = fn (int $i): string => strip_tags($company['profil'][$i] ?? '');
        $misi = fn (int $i): string => $company['misi'][$i] ?? '';
        $goal = fn (int $i, string $key): string => $company['goals'][$i][$key] ?? '';
        $adv = fn (int $i, string $key): string => $company['keuntungan'][$i][$key] ?? '';

        // Kolom yang berulang 3 kali (paragraf profil, misi, kartu tujuan, kartu keunggulan)
        $profilFields = $misiFields = $goalFields = $advFields = [];
        foreach ([1, 2, 3] as $n) {
            $profilFields["profil_$n"] = $f("Paragraf $n", 700, $profil($n - 1), true);
            $misiFields["misi_$n"] = $f("Misi $n", 200, $misi($n - 1), true);
            $goalFields["goal_{$n}_title"] = $f("Kartu $n: judul", 60, $goal($n - 1, 'title'));
            $goalFields["goal_{$n}_desc"] = $f("Kartu $n: keterangan", 200, $goal($n - 1, 'desc'), true);
            $advFields["adv_{$n}_title"] = $f("Kartu $n: judul", 60, $adv($n - 1, 'title'));
            $advFields["adv_{$n}_desc"] = $f("Kartu $n: keterangan", 200, $adv($n - 1, 'desc'), true);
        }

        return [
            // ================= BERANDA =================
            'home' => [
                'label' => 'Beranda',
                'groups' => [
                    [
                        'title' => 'Bagian atas (hero)',
                        'desc' => 'Judul besar dan paragraf di bagian paling atas Beranda.',
                        'icon' => 'bi-window',
                        'fields' => [
                            'hero_badge' => $f('Label kecil (di atas judul)', 80, 'Mitra Perdagangan Eceran · '.$city),
                            'hero_title_1' => $f('Judul baris 1 (putih)', 60, 'Semua Kebutuhan'),
                            'hero_title_accent' => $f('Judul baris 2 (merah)', 60, 'Teknik & Perlengkapan'),
                            'hero_title_2' => $f('Judul baris 3 (putih)', 60, 'Dalam Satu Tempat'),
                            'hero_lead' => $f('Paragraf pengantar', 400, $name.' adalah usaha perdagangan eceran yang berlokasi di '.$city.'. Kami menyediakan beragam kebutuhan mulai dari perangkat komputer dan telekomunikasi hingga perlengkapan rumah, kantor, dan bangunan.', true),
                            'hero_button' => $f('Tulisan tombol', 40, 'Tentang Kami'),
                        ],
                    ],
                    [
                        'title' => 'Bagian Bidang Usaha',
                        'desc' => 'Tulisan di atas dan di bawah slider bidang usaha. Angka jumlah di depan judul tampil otomatis.',
                        'icon' => 'bi-grid',
                        'fields' => [
                            'services_title' => $f('Judul', 80, 'Bidang Usaha yang Kami Sediakan'),
                            'services_lead' => $f('Paragraf pengantar', 300, 'Solusi perdagangan eceran dan pengadaan terpercaya untuk kebutuhan perorangan, kantor, sekolah, hingga instansi di '.$city.'.', true),
                            'services_button' => $f('Tulisan tombol', 40, 'Lihat Seluruh Bidang Usaha'),
                        ],
                    ],
                ],
            ],

            // ================= TENTANG KAMI =================
            'about' => [
                'label' => 'Tentang Kami',
                'groups' => [
                    [
                        'title' => 'Banner atas',
                        'desc' => 'Tulisan di bagian paling atas halaman Tentang Kami.',
                        'icon' => 'bi-window',
                        'fields' => [
                            'eyebrow' => $f('Label kecil (merah)', 30, 'Tentang Kami'),
                            'title' => $f('Judul (putih)', 80, 'Mengenal '.$name.'.'),
                            'subtitle' => $f('Baris kedua (abu)', 80, 'Mitra terpercaya di '.$city.'.'),
                            'lead' => $f('Keterangan kecil', 200, 'Usaha perdagangan eceran yang menghadirkan beragam kebutuhan teknik dan perlengkapan untuk Anda.', true),
                        ],
                    ],
                    [
                        'title' => 'Profil perusahaan',
                        'desc' => 'Cerita perusahaan di samping foto. Nama perusahaan di dalam paragraf otomatis tampil tebal.',
                        'icon' => 'bi-building',
                        'fields' => [
                            'profil_eyebrow' => $f('Label kecil (merah)', 40, 'Profil Perusahaan'),
                            'profil_title' => $f('Judul', 80, 'Perjalanan & Komitmen Kami'),
                        ] + $profilFields,
                    ],
                    [
                        'title' => 'Visi & Misi',
                        'desc' => 'Ganti dengan visi dan misi perusahaan terbaru.',
                        'icon' => 'bi-eye',
                        'fields' => [
                            'visi_eyebrow' => $f('Label kecil (merah)', 40, 'Komitmen Kami'),
                            'visi_title' => $f('Judul', 60, 'Visi & Misi'),
                            'visi' => $f('Visi', 400, $company['visi'], true),
                        ] + $misiFields,
                    ],
                    [
                        'title' => 'Tujuan perusahaan',
                        'desc' => 'bagian menampilkan tiga kartu',
                        'icon' => 'bi-bullseye',
                        'fields' => [
                            'goals_eyebrow' => $f('Label kecil (merah)', 40, 'Arah Usaha'),
                            'goals_title' => $f('Judul', 60, 'Tujuan Perusahaan'),
                            'goals_lead' => $f('Keterangan di bawah judul', 200, 'Tiga hal yang menjadi pegangan kami dalam melayani setiap pelanggan.', true),
                        ] + $goalFields,
                    ],
                    [
                        'title' => 'Keunggulan perusahaan',
                        'desc' => 'Dua kotak besar dan tiga kartu. Angka jumlah bidang usaha dan ikon kartu tampil otomatis.',
                        'icon' => 'bi-stars',
                        'fields' => [
                            'adv_eyebrow' => $f('Label kecil (merah)', 40, 'Mengapa Memilih Kami'),
                            'adv_title' => $f('Judul', 60, 'Keunggulan Perusahaan'),
                            'adv_services_title' => $f('Kotak merah: judul', 60, 'Bidang Usaha, Satu Tempat'),
                            'adv_services_lead' => $f('Kotak merah: keterangan', 120, 'Satu kunjungan, banyak kebutuhan terpenuhi.'),
                            'adv_people_title' => $f('Kotak foto: judul', 60, 'Melayani Semua Kalangan'),
                            'adv_people_lead' => $f('Kotak foto: keterangan', 200, 'Perorangan, perusahaan swasta, sekolah, hingga instansi pemerintahan.', true),
                        ] + $advFields,
                    ],
                    [
                        'title' => 'Ajakan WhatsApp',
                        'desc' => 'Bagian merah di bawah halaman, di samping peta lokasi.',
                        'icon' => 'bi-whatsapp',
                        'fields' => [
                            'cta_eyebrow' => $f('Label kecil', 40, 'Hubungi Kami'),
                            'cta_title' => $f('Judul', 80, 'Butuh produk atau penawaran?'),
                            'cta_lead' => $f('Paragraf pengantar', 300, 'Sebutkan produk yang Anda cari, jumlahnya, dan kebutuhannya. Tim kami siap membantu mengecek ketersediaan dan memberikan penawaran melalui WhatsApp.', true),
                            'cta_button' => $f('Tulisan tombol', 30, 'Chat WhatsApp'),
                        ],
                    ],
                ],
            ],

            
            // ================= BIDANG USAHA =================
            'services' => [
                'label' => 'Bidang Usaha',
                'groups' => [
                    [
                        'title' => 'Banner atas',
                        'desc' => 'Tulisan di bagian paling atas halaman Bidang Usaha.',
                        'icon' => 'bi-window',
                        'fields' => [
                            'eyebrow' => $f('Label kecil (merah)', 30, 'Bidang Usaha'),
                            'title' => $f('Judul (putih)', 80, 'Temukan yang Anda butuhkan.'),
                            'subtitle_suffix' => $f('Baris kedua (abu): tulisan setelah angka', 60, 'bidang perdagangan eceran.'),
                            'lead' => $f('Keterangan kecil', 200, 'Untuk kebutuhan rumah, kantor, dan usaha Anda.', true),
                        ],
                    ],
                    [
                        'title' => 'Kartu bidang usaha',
                        'desc' => 'Tulisan yang sama di setiap kartu. Judul kartu, foto, dan deskripsi diatur di menu Bidang Usaha.',
                        'icon' => 'bi-grid',
                        'fields' => [
                            'card_text' => $f('Keterangan di tiap kartu', 120, 'Tanyakan ketersediaan produk melalui WhatsApp.', true),
                            'button_detail' => $f('Tombol kiri', 30, 'Lihat Detail'),
                            'button_wa' => $f('Tombol kanan', 30, 'WhatsApp'),
                        ],
                    ],
                ],
            ],

                        // ================= GALERI =================
            'gallery' => [
                'label' => 'Galeri',
                'groups' => [
                    [
                        'title' => 'Banner atas',
                        'desc' => 'Tulisan di bagian paling atas halaman Galeri. Foto galeri diatur di menu Galeri.',
                        'icon' => 'bi-window',
                        'fields' => [
                            'eyebrow' => $f('Label kecil (merah)', 30, $gallery['eyebrow']),
                            'title' => $f('Judul (putih)', 80, $gallery['title']),
                            'subtitle' => $f('Baris kedua (abu)', 80, $gallery['subtitle']),
                            'lead' => $f('Keterangan kecil', 200, $gallery['lead'], true),
                        ],
                    ],
                ],
            ],

            // ================= KONTAK =================
            'contact' => [
                'label' => 'Kontak',
                'groups' => [
                    [
                        'title' => 'Banner atas',
                        'desc' => 'Tulisan di bagian paling atas halaman Kontak.',
                        'icon' => 'bi-window',
                        'fields' => [
                            'eyebrow' => $f('Label kecil (merah)', 30, 'Kontak'),
                            'title' => $f('Judul (putih)', 80, 'Hubungi kami.'),
                            'subtitle' => $f('Baris kedua (abu)', 80, 'Kami siap membantu Anda.'),
                            'lead' => $f('Keterangan kecil', 200, 'Tanyakan produk dan penawaran lewat WhatsApp, telepon, atau email.', true),
                        ],
                    ],
                    [
                        'title' => 'Kartu kontak cepat',
                        'desc' => 'Tulisan ajakan di empat kartu. Nomor telepon, email, dan alamat diatur di Admin > Kontak & Alamat.',
                        'icon' => 'bi-telephone',
                        'fields' => [
                            'card_wa_cta' => $f('Kartu WhatsApp', 30, 'Chat sekarang'),
                            'card_phone_cta' => $f('Kartu Telepon', 30, 'Hubungi langsung'),
                            'card_email_cta' => $f('Kartu Email', 30, 'Kirim email'),
                            'card_map_cta' => $f('Kartu Alamat', 30, 'Lihat di peta'),
                        ],
                    ],
                    [
                        'title' => 'Form pesan',
                        'desc' => 'Tulisan di atas dan di bawah form. Isi form (nama, bidang, pesan) tidak berubah.',
                        'icon' => 'bi-chat-dots',
                        'fields' => [
                            'form_eyebrow' => $f('Label kecil', 30, 'Kirim Pesan'),
                            'form_title' => $f('Judul', 60, 'Tulis kebutuhan Anda'),
                            'form_lead' => $f('Keterangan di bawah judul', 200, 'Isi form berikut, pesan akan langsung terbuka di WhatsApp kami.', true),
                            'form_button' => $f('Tulisan tombol kirim', 40, 'Kirim via WhatsApp'),
                            'form_note' => $f('Catatan kecil di bawah tombol', 200, 'Pesan dikirim lewat WhatsApp. Tidak ada data yang disimpan di website ini.', true),
                        ],
                    ],
                    [
                        'title' => 'Cara kerja',
                        'desc' => 'Kotak gelap di samping form, berisi tiga langkah. Nomor 01, 02, 03 tampil otomatis.',
                        'icon' => 'bi-list-ol',
                        'fields' => [
                            'steps_eyebrow' => $f('Label kecil', 30, 'Cara Kerja'),
                            'steps_title' => $f('Judul', 80, 'Dapatkan penawaran dalam 3 langkah'),
                            'step_1_title' => $f('Langkah 1: judul', 60, 'Sebutkan kebutuhan'),
                            'step_1_desc' => $f('Langkah 1: keterangan', 160, 'Tulis produk yang dicari, jumlah, dan kebutuhan Anda.', true),
                            'step_2_title' => $f('Langkah 2: judul', 60, 'Kami cek ketersediaan'),
                            'step_2_desc' => $f('Langkah 2: keterangan', 160, 'Tim kami mengecek produk dan menghubungi Anda lewat WhatsApp.', true),
                            'step_3_title' => $f('Langkah 3: judul', 60, 'Terima penawaran'),
                            'step_3_desc' => $f('Langkah 3: keterangan', 160, 'Anda menerima penawaran dan bisa langsung berdiskusi dengan kami.', true),
                            'steps_note' => $f('Catatan di bagian bawah kotak', 160, 'Melayani perorangan, kantor, sekolah, hingga instansi di '.$city.'.', true),
                        ],
                    ],
                    [
                        'title' => 'Peta lokasi',
                        'desc' => 'Bagian peta di bawah form. Alamat dan peta diatur di Admin > Kontak & Alamat.',
                        'icon' => 'bi-geo-alt',
                        'fields' => [
                            'map_eyebrow' => $f('Label kecil', 30, 'Lokasi'),
                            'map_title' => $f('Judul', 60, 'Kunjungi Kami'),
                            'map_button' => $f('Tulisan tombol', 30, 'Buka Peta'),
                        ],
                    ],
                    [
                        'title' => 'Pertanyaan umum (FAQ)',
                        'desc' => 'Empat pertanyaan beserta jawabannya.',
                        'icon' => 'bi-question-circle',
                        'fields' => [
                            'faq_eyebrow' => $f('Label kecil', 30, 'FAQ'),
                            'faq_title' => $f('Judul', 80, 'Pertanyaan yang Sering Diajukan'),
                            'faq_1_q' => $f('Pertanyaan 1', 120, 'Bagaimana cara meminta penawaran?'),
                            'faq_1_a' => $f('Jawaban 1', 400, 'Kirim pesan lewat form di atas atau chat WhatsApp kami. Sebutkan produk, jumlah, dan kebutuhan Anda, lalu tim kami akan mengecek ketersediaan dan memberikan penawaran.', true),
                            'faq_2_q' => $f('Pertanyaan 2', 120, 'Siapa saja yang bisa dilayani?'),
                            'faq_2_a' => $f('Jawaban 2', 400, 'Kami melayani kebutuhan perorangan, kantor, sekolah, hingga instansi di '.$city.'.', true),
                            'faq_3_q' => $f('Pertanyaan 3', 120, 'Produk apa saja yang tersedia?'),
                            'faq_3_a' => $f('Jawaban 3', 400, 'Kami bergerak di berbagai bidang perdagangan eceran, mulai dari piranti lunak, komputer, hingga material bangunan dan mesin kantor.', true),
                            'faq_3_link' => $f('Jawaban 3: tulisan tautan di akhir', 60, 'Lihat semua bidang usaha'),
                            'faq_4_q' => $f('Pertanyaan 4', 120, 'Di mana lokasi '.$name.'?'),
                            'faq_4_a' => $f('Jawaban 4', 400, $company['address'].'. Lihat petanya di bagian "Kunjungi Kami" di atas.', true),
                        ],
                    ],
                ],
            ],
        ];
    }


    public static function exists(string $page): bool
    {
        return array_key_exists($page, self::pages());
    }

    /** Semua kolom satu halaman (nama => pengaturan), tanpa pengelompokan. */
    public static function fields(string $page): array
    {
        return collect(self::pages()[$page]['groups'] ?? [])
            ->flatMap(fn (array $group) => $group['fields'])
            ->all();
    }

    /** Teks bawaan satu halaman (nama => teks). */
    public static function defaults(string $page): array
    {
        return array_map(fn (array $field) => $field['default'], self::fields($page));
    }

    /** Aturan validasi: semua kolom boleh kosong, dengan batas karakter masing-masing. */
    public static function rules(string $page): array
    {
        return array_map(
            fn (array $field) => ['nullable', 'string', 'max:'.$field['max']],
            self::fields($page),
        );
    }

    /** Pesan error validasi dalam bahasa Indonesia. */
    public static function messages(string $page): array
    {
        $messages = [];
        foreach (self::fields($page) as $name => $field) {
            $messages["$name.max"] = "{$field['label']} maksimal {$field['max']} karakter.";
        }

        return $messages;
    }
}