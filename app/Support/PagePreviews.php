<?php

namespace App\Support;

/**
 * Gambar pratinjau di Admin > Teks Halaman: tiap kelompok kolom diberi "foto kecil"
 * bagian website yang dimaksud, lengkap dengan foto latarnya, supaya admin langsung
 * tahu tulisan itu muncul di mana. Tulisan di pratinjau ikut berubah saat mengetik.
 *
 * Kunci array = judul kelompok di PageTexts. Kelompok yang tidak terdaftar di sini
 * tetap tampil normal, hanya tanpa pratinjau.
 *
 * Isi 'nodes' adalah potongan tampilan berurutan. Nama kolom ('f') harus sama dengan
 * nama kolom di PageTexts. Jenisnya:
 *   eyebrow, heading (parts + sub), lead, text, small, link, static, ph (kotak penanda),
 *   btns (deretan tombol), cards (kotak-kotak berisi node lagi), split (teks + foto).
 */
final class PagePreviews
{
    /** @return array<string, array> */
    public static function for(string $page): array
    {
        $eyebrow = fn (string $f): array => ['t' => 'eyebrow', 'f' => $f];
        $lead = fn (string $f): array => ['t' => 'lead', 'f' => $f];
        $text = fn (string $f): array => ['t' => 'text', 'f' => $f];
        $small = fn (string $f): array => ['t' => 'small', 'f' => $f];
        $link = fn (string $f): array => ['t' => 'link', 'f' => $f];
        $title = fn (string $f): array => ['t' => 'ctitle', 'f' => $f];
        $desc = fn (string $f): array => ['t' => 'cdesc', 'f' => $f];
        $static = fn (string $text, string $as = 'small'): array => ['t' => 'static', 'text' => $text, 'as' => $as];
        $ph = fn (string $label, int $h = 70): array => ['t' => 'ph', 'label' => $label, 'h' => $h];
        $btns = fn (array ...$b): array => ['t' => 'btns', 'items' => $b];
        $h = fn (array $parts, string $size = 'lg', ?array $sub = null, string $prefix = ''): array => [
            't' => 'heading', 'parts' => $parts, 'size' => $size, 'sub' => $sub, 'prefix' => $prefix,
        ];

        // Banner foto + judul besar: dipakai Tentang Kami, Bidang Usaha, Kontak, dan Galeri
        $banner = fn (string $image, string $where, string $subField = 'subtitle', string $subPrefix = ''): array => [
            'theme' => 'dark', 'bg' => $image, 'align' => 'left', 'where' => $where,
            'nodes' => [
                $eyebrow('eyebrow'),
                $h([['title']], 'xl', ['f' => $subField, 'prefix' => $subPrefix]),
                $lead('lead'),
            ],
        ];

        $previews = [
            'home' => [
                'Bagian atas (hero)' => [
                    'theme' => 'dark', 'bg' => 'hero', 'align' => 'center',
                    'where' => 'banner besar paling atas di Beranda',
                    'nodes' => [
                        $eyebrow('hero_badge'),
                        $h([['hero_title_1'], ['hero_title_accent', true], ['hero_title_2']], 'xl'),
                        $lead('hero_lead'),
                        $btns(['hero_button', 'solid']),
                    ],
                ],
                'Bagian Bidang Usaha' => [
                    'theme' => 'light', 'align' => 'center',
                    'where' => 'bagian slider bidang usaha di Beranda (angka 9 menyesuaikan jumlah bidang usaha Anda)',
                    'nodes' => [
                        $h([['services_title']], 'lg', null, '9 '),
                        $lead('services_lead'),
                        $ph('Slider foto bidang usaha (diatur di menu Bidang Usaha)', 60),
                        $btns(['services_button', 'outline']),
                    ],
                ],
            ],

            'about' => [
                'Banner atas' => $banner('about_header', 'banner paling atas di halaman Tentang Kami'),
                'Profil perusahaan' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'cerita perusahaan di samping foto',
                    'nodes' => [[
                        't' => 'split', 'image' => 'about',
                        'main' => [
                            $eyebrow('profil_eyebrow'),
                            $h([['profil_title']]),
                            $text('profil_1'), $text('profil_2'), $text('profil_3'),
                        ],
                    ]],
                ],
                'Visi & Misi' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'bagian Visi dan Misi',
                    'nodes' => [
                        $eyebrow('visi_eyebrow'),
                        $h([['visi_title']]),
                        $static('Visi', 'label'), $text('visi'),
                        $static('Misi', 'label'), $text('misi_1'), $text('misi_2'), $text('misi_3'),
                    ],
                ],
                'Tujuan perusahaan' => [
                    'theme' => 'dark', 'bg' => 'tujuan', 'align' => 'center',
                    'where' => 'bagian gelap dengan tiga kartu bernomor',
                    'nodes' => [
                        $eyebrow('goals_eyebrow'),
                        $h([['goals_title']]),
                        $lead('goals_lead'),
                        ['t' => 'cards', 'cols' => 3, 'items' => array_map(fn (int $n): array => [
                            'tone' => 'glass',
                            'nodes' => [$static(sprintf('%02d', $n), 'num'), $title("goal_{$n}_title"), $desc("goal_{$n}_desc")],
                        ], [1, 2, 3])],
                    ],
                ],
                'Keunggulan perusahaan' => [
                    'theme' => 'light', 'align' => 'center',
                    'where' => 'dua kotak besar dan tiga kartu keunggulan',
                    'nodes' => [
                        $eyebrow('adv_eyebrow'),
                        $h([['adv_title']]),
                        ['t' => 'cards', 'cols' => 2, 'items' => [
                            ['tone' => 'red', 'nodes' => [$title('adv_services_title'), $desc('adv_services_lead')]],
                            ['tone' => 'photo', 'img' => 'layanan', 'nodes' => [$title('adv_people_title'), $desc('adv_people_lead')]],
                        ]],
                        ['t' => 'cards', 'cols' => 3, 'items' => array_map(fn (int $n): array => [
                            'tone' => 'plain',
                            'nodes' => [$title("adv_{$n}_title"), $desc("adv_{$n}_desc")],
                        ], [1, 2, 3])],
                    ],
                ],
                'Ajakan WhatsApp' => [
                    'theme' => 'red', 'align' => 'left',
                    'where' => 'bagian merah di bawah halaman, di samping peta',
                    'nodes' => [
                        $eyebrow('cta_eyebrow'),
                        $h([['cta_title']]),
                        $text('cta_lead'),
                        $btns(['cta_button', 'white']),
                    ],
                ],
            ],

            'services' => [
                'Banner atas' => $banner('service_header', 'banner paling atas di halaman Bidang Usaha', 'subtitle_suffix', '9 '),
                'Kartu bidang usaha' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'satu kartu bidang usaha (tulisan yang sama di setiap kartu)',
                    'nodes' => [['t' => 'cards', 'cols' => 1, 'items' => [[
                        'tone' => 'plain',
                        'nodes' => [
                            $ph('Foto bidang usaha', 46),
                            $static('Nama bidang usaha', 'ctitle'),
                            $desc('card_text'),
                            $btns(['button_detail', 'outline'], ['button_wa', 'solid']),
                        ],
                    ]]]],
                ],
            ],

            'gallery' => [
                'Banner atas' => $banner('gallery_header', 'banner paling atas di halaman Galeri'),
            ],

            'contact' => [
                'Banner atas' => $banner('contact_header', 'banner paling atas di halaman Kontak'),
                'Kartu kontak cepat' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'empat kartu kontak cepat di bawah banner',
                    'nodes' => [['t' => 'cards', 'cols' => 4, 'items' => [
                        ['tone' => 'plain', 'nodes' => [$static('WhatsApp', 'ctitle'), $static('Nomor Anda', 'cdesc'), $link('card_wa_cta')]],
                        ['tone' => 'plain', 'nodes' => [$static('Telepon', 'ctitle'), $static('Nomor Anda', 'cdesc'), $link('card_phone_cta')]],
                        ['tone' => 'plain', 'nodes' => [$static('Email', 'ctitle'), $static('Email Anda', 'cdesc'), $link('card_email_cta')]],
                        ['tone' => 'plain', 'nodes' => [$static('Alamat', 'ctitle'), $static('Alamat Anda', 'cdesc'), $link('card_map_cta')]],
                    ]]],
                ],
                'Form pesan' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'form pesan di halaman Kontak',
                    'nodes' => [
                        $eyebrow('form_eyebrow'),
                        $h([['form_title']]),
                        $lead('form_lead'),
                        $ph('Kolom nama, bidang, dan pesan', 44),
                        $btns(['form_button', 'solid']),
                        $small('form_note'),
                    ],
                ],
                'Cara kerja' => [
                    'theme' => 'dark', 'align' => 'left',
                    'where' => 'kotak gelap berisi tiga langkah, di samping form',
                    'nodes' => [
                        $eyebrow('steps_eyebrow'),
                        $h([['steps_title']]),
                        ['t' => 'cards', 'cols' => 3, 'items' => array_map(fn (int $n): array => [
                            'tone' => 'glass',
                            'nodes' => [$static(sprintf('%02d', $n), 'num'), $title("step_{$n}_title"), $desc("step_{$n}_desc")],
                        ], [1, 2, 3])],
                        $small('steps_note'),
                    ],
                ],
                'Peta lokasi' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'bagian peta lokasi',
                    'nodes' => [
                        $eyebrow('map_eyebrow'),
                        $h([['map_title']]),
                        $ph('Peta lokasi', 60),
                        $btns(['map_button', 'solid']),
                    ],
                ],
                'Pertanyaan umum (FAQ)' => [
                    'theme' => 'light', 'align' => 'left',
                    'where' => 'daftar pertanyaan yang sering diajukan',
                    'nodes' => [
                        $eyebrow('faq_eyebrow'),
                        $h([['faq_title']]),
                        ['t' => 'cards', 'cols' => 2, 'items' => [
                            ['tone' => 'plain', 'nodes' => [$title('faq_1_q'), $desc('faq_1_a')]],
                            ['tone' => 'plain', 'nodes' => [$title('faq_2_q'), $desc('faq_2_a')]],
                            ['tone' => 'plain', 'nodes' => [$title('faq_3_q'), $desc('faq_3_a'), $link('faq_3_link')]],
                            ['tone' => 'plain', 'nodes' => [$title('faq_4_q'), $desc('faq_4_a')]],
                        ]],
                    ],
                ],
            ],
        ];

        return $previews[$page] ?? [];
    }
}