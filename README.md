# CV. Bhirma Teknik — Website Perusahaan

Website company profile CV. Bhirma Teknik (Bangli, Bali), dibangun dengan **Laravel 13** dan **Bootstrap 5**,
memakai database **SQLite**, dan dilengkapi **CMS** sederhana agar admin bisa mengubah tulisan, gambar, kontak,
bidang usaha, dan galeri tanpa menyentuh kode.

## Kebutuhan

- PHP 8.3 atau lebih baru, dengan ekstensi `pdo_sqlite`, `gd` (untuk memperkecil foto saat diunggah), dan `fileinfo`
- Composer

Database memakai **SQLite**: seluruh data tersimpan dalam satu file, `database/database.sqlite`. Tidak perlu memasang MySQL.

## Cara menjalankan (pertama kali)

```bash
composer install
cp .env.example .env              # bawaannya sudah SQLite (DB_CONNECTION=sqlite)
php artisan key:generate
touch database/database.sqlite    # membuat file database kosong (Windows: type nul > database\database.sqlite)
php artisan migrate --seed        # membuat tabel + mengisi 9 bidang usaha dan foto galeri contoh
php artisan admin:create          # membuat akun admin (nama, email, password)
php artisan serve
```

- Website: <http://localhost:8000>
- CMS / admin: <http://localhost:8000/admin>

`php artisan migrate --seed` aman dijalankan ulang: bidang usaha dan foto galeri yang sudah diedit admin tidak akan
tertimpa dan tidak dobel. Satu catatan: foto galeri contoh yang **sudah dihapus** admin akan muncul lagi bila seeder
dijalankan ulang. Jalankan `php artisan migrate` (tanpa `--seed`) untuk pembaruan berikutnya.

> Session, cache, dan antrean memakai tabel di database SQLite yang sama. File `database/database.sqlite` dan
> foldernya (`database/`) harus bisa ditulis oleh web server.

## Halaman website

| Halaman | Alamat |
|---|---|
| Beranda | `/` |
| Tentang Kami | `/tentang-kami` |
| Bidang Usaha | `/bidang-usaha` |
| Detail Bidang Usaha | `/bidang-usaha/{slug}` |
| Galeri | `/galeri` |
| Kontak | `/kontak` |

Form di halaman Kontak tidak menyimpan pesan ke database: setelah diisi, form membuka WhatsApp dengan pesan yang sudah terisi.

## Apa itu CMS di proyek ini?

CMS (*Content Management System*) = halaman khusus admin untuk mengubah isi website lewat browser,
tanpa mengedit kode. Fitur yang tersedia di `/admin`:

| Menu | Fungsi |
|---|---|
| **Dashboard** | Ringkasan jumlah bidang usaha, produk, gambar yang sudah diganti, dan foto galeri, serta pintasan ke teks tiap halaman. |
| **Teks Halaman** | Mengubah tulisan di halaman Beranda, Tentang Kami, Bidang Usaha, Galeri, dan Kontak (termasuk visi & misi dan FAQ). Tiap bagian punya pratinjau kecil. Kolom yang dikosongkan memakai teks bawaan. |
| **Gambar Situs** | Mengganti logo dan 8 foto banner/latar di halaman publik. Tombol "Kembali ke gambar bawaan" tersedia. |
| **Bidang Usaha** | Menambah, mengubah, mengurutkan, menyembunyikan, dan menghapus bidang usaha beserta fotonya dan daftar produknya. |
| **Galeri** | Menambah, mengubah, menyembunyikan, dan menghapus foto galeri. Teks banner galeri diatur di Teks Halaman > Galeri. |
| **Kontak & Alamat** | Mengubah nomor telepon/WhatsApp, email, alamat, alamat singkat, dan lokasi peta. Nomor WhatsApp dan peta dibuat otomatis dari isian ini. |
| **Profil** | Mengubah nama, email, foto, dan password akun admin. |

Foto yang diunggah otomatis dipotong di tengah agar pas dengan kotaknya di website (portrait/landscape sama saja), diperkecil (mis. 4000 px → 1600 px), orientasi foto HP dibetulkan, dan metadata/GPS dibuang. Bentuk kotak tiap slot diatur lewat `ratio` di `config/site_images.php`; kotak bidang usaha 4:3.
Format yang diterima: JPG, PNG, WebP, maksimal 4 MB.

## Di mana data disimpan?

Semua tabel berada di file SQLite `database/database.sqlite`.

| Data | Lokasi | Siapa yang mengubah |
|---|---|---|
| Foto banner, logo | Tabel `site_images` (slot didefinisikan di `config/site_images.php`) | Admin lewat CMS (Gambar Situs) |
| Bidang usaha + foto | Tabel `services` | Admin lewat CMS (Bidang Usaha) |
| Produk per bidang usaha (tab "Detail produk") | Tabel `service_items` | Admin lewat CMS (Bidang Usaha → Edit → Detail produk) |
| Foto galeri | Tabel `gallery_photos` (kategori di `config/gallery.php`) | Admin lewat CMS (Galeri) |
| Tulisan di halaman publik (judul, profil, visi/misi, tujuan, FAQ, dst.) | Tabel `site_settings`, kunci `halaman.kolom` (teks bawaan di `app/Support/PageTexts.php`) | Admin lewat CMS (Teks Halaman) |
| Nama, telepon, email, alamat, peta | Tabel `site_settings`, kunci `company.*` (nilai awal di `config/company.php`) | Admin lewat CMS (Kontak & Alamat) |
| Akun admin | Tabel `users` | Perintah `php artisan admin:create` dan menu Profil |

Selama admin belum mengubah sesuatu, website memakai nilai bawaan dari file konfigurasi (`config/company.php`, `config/site_images.php`,
`app/Support/PageTexts.php`). Nama perusahaan, kota, dan tagline hanya bisa diubah lewat `config/company.php`.
Gambar bawaan memakai foto Unsplash (logo memakai `public/images/logo.jpg`).
Foto unggahan admin disimpan di `public/uploads/`.

## Struktur kode

```
app/Http/Controllers/
    ServiceDetailController.php      halaman detail bidang usaha (publik)
    GalleryController.php            halaman galeri (publik)
    Admin/                           controller CMS (login, dashboard, teks halaman, kontak & alamat, gambar, bidang usaha, galeri, profil)
app/Http/Requests/Admin/             aturan validasi form CMS
app/Models/                          SiteImage, SiteSetting, Service, ServiceItem, GalleryPhoto, User
app/Support/ImageStore.php           simpan / perkecil / hapus / URL gambar
app/Support/PageTexts.php            daftar teks halaman yang bisa diubah admin
app/Support/PagePreviews.php         pratinjau tiap bagian di Admin > Teks Halaman
app/Support/CompanyProfile.php       kontak & alamat: nilai dari CMS menimpa config/company.php saat aplikasi mulai
app/Support/Whatsapp.php             pembuat link WhatsApp
app/View/Composers/SiteComposer.php  data bersama untuk semua halaman publik
config/company.php                   data perusahaan (nilai awal)
config/site_images.php               daftar slot gambar
config/gallery.php                   kategori dan foto contoh galeri
routes/web.php                       route publik + /admin
resources/views/admin/               tampilan CMS
public/css, public/js                gaya dan skrip website (Bootstrap dimuat dari CDN)
tests/Feature/                       tes otomatis (lihat bagian Pengujian)
```

## Pengujian

```bash
php artisan test      # menjalankan semua tes
vendor/bin/pint       # merapikan gaya penulisan kode PHP
```

Tes memakai SQLite di memori (lihat `phpunit.xml`), jadi **tidak menyentuh** `database/database.sqlite`; ekstensi `pdo_sqlite` harus aktif di `php.ini`.

| File tes | Yang diuji |
|---|---|
| `PublicPagesTest` | Halaman publik, seeder bidang usaha, detail produk, 404, bidang usaha tersembunyi, gambar bawaan/ganti |
| `GalleryPageTest` | Halaman galeri: pesan galeri kosong, teks banner, foto nonaktif |
| `AdminAuthTest` | Login/logout dan perlindungan halaman admin |
| `AdminPageTextTest` | Teks Halaman: semua tab, simpan, kembali ke teks bawaan, batas karakter |
| `AdminCompanyTest` | Kontak & Alamat: simpan, validasi, nomor WhatsApp, tampil di website |
| `AdminSiteImageTest` | Ganti dan kembalikan gambar situs |
| `AdminServiceTest` | Tambah/ubah/hapus bidang usaha dan produk |
| `AdminGalleryTest` | Tambah/ubah/hapus foto galeri, teks banner lewat Teks Halaman |
| `AdminProfileTest` | Ubah profil, foto, dan password |

## Catatan untuk deployment (hosting)

1. Di `.env` produksi: `APP_ENV=production`, `APP_DEBUG=false`, dan buat `APP_KEY` baru (`php artisan key:generate`).
2. Pastikan file `database/database.sqlite` ada, lalu jalankan `php artisan migrate --force` dan `php artisan admin:create`.
3. Folder `database/` (beserta file `database.sqlite`) **harus bisa ditulis** web server, dan ikut di-backup:
   file itu berisi seluruh isi CMS dan akun admin. Jangan ditimpa saat memperbarui website.
4. Batas unggah file ditentukan `php.ini` (`upload_max_filesize`, `post_max_size`). Bawaan banyak hosting hanya 2 MB;
   naikkan ke minimal 8M bila admin sering mengunggah foto besar.
5. Foto unggahan CMS disimpan di `public/uploads/` (tanpa symlink). Folder ini harus bisa ditulis web server,
   dan ikut di-backup (jangan ditimpa saat memperbarui website).
6. Folder `storage/` dan `bootstrap/cache/` harus bisa ditulis oleh web server. Kosongkan isi `bootstrap/cache/`
   (kecuali `.gitignore`) sebelum mengunggah: file cache di sana mencatat paket dev dan bisa menyebabkan error di hosting.
7. Jangan unggah atau bagikan `.env` maupun `database/database.sqlite` milik pengembangan (berisi kunci aplikasi,
   hash password admin, dan sesi login). Di hosting, buat `.env` dan akun admin baru.