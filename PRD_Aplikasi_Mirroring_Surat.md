# PRD — Aplikasi Mirroring Surat

**Versi:** 1.0  
**Jenis aplikasi:** Web Application  
**Target pengguna:** Staf dan atasan  
**Status:** MVP (Minimum Viable Product)

## 1. Latar Belakang

Dalam kegiatan administrasi kantor, staf sering menerima atau mengelola surat yang perlu diketahui oleh atasan. Penyampaian surat secara manual dapat memerlukan waktu dan menyulitkan atasan ketika ingin memeriksa isi surat, terutama saat tidak berada di kantor.

Aplikasi Mirroring Surat berfungsi sebagai media penyimpanan dan penayangan salinan digital surat. Staf dapat mengunggah surat ke aplikasi, sedangkan atasan dapat masuk untuk melihat surat sesuai hak aksesnya.

**Prinsip utama:** surat asli tetap dikelola melalui prosedur administrasi kantor yang berlaku. Aplikasi ini menyediakan salinan digital untuk memudahkan pemantauan.

## 2. Tujuan Aplikasi

1. Memudahkan staf mengunggah dan menyimpan salinan digital surat.
2. Memudahkan atasan melihat isi surat tanpa harus meminta dokumen langsung kepada staf.
3. Mempercepat penyampaian informasi surat kepada atasan.
4. Menyediakan pencarian surat berdasarkan nomor, tanggal, perihal, atau pengirim.
5. Menjaga keamanan dokumen agar hanya pengguna berwenang yang dapat mengaksesnya.

## 3. Pengguna dan Hak Akses

### 3.1 Staf / Operator

Pengguna yang bertugas mengelola surat.

- Login ke aplikasi.
- Menambahkan data surat.
- Mengunggah file PDF atau hasil pemindaian surat.
- Mengubah data dan mengganti file sesuai kewenangan.
- Melihat status surat yang sudah diunggah.

### 3.2 Atasan / Viewer

Pengguna yang memantau surat.

- Login ke aplikasi.
- Melihat daftar surat.
- Membuka dan membaca surat PDF.
- Mencari dan memfilter surat.
- Melihat tanggal surat diunggah dan informasi lainnya.
- Mengunduh surat jika diizinkan oleh kebijakan kantor.

### 3.3 Admin Sistem (Opsional)

Pengelola teknis aplikasi.

- Membuat akun dan menetapkan hak akses.
- Menonaktifkan akun yang tidak digunakan.
- Memantau aktivitas dan membantu pencadangan data.

Untuk versi pertama, dua peran utama saja sudah cukup: **Staf dan Atasan**. Admin dapat ditambahkan jika diperlukan.

## 4. Fitur Utama

| No. | Fitur | Keterangan |
|---:|---|---|
| 1 | Login | Pengguna masuk menggunakan username/email dan password. |
| 2 | Dashboard | Menampilkan jumlah surat, surat terbaru, dan ringkasan data. |
| 3 | Upload surat | Staf mengunggah surat dalam format PDF. |
| 4 | Data surat | Menyimpan nomor surat, tanggal, perihal, pengirim, dan kategori. |
| 5 | Mirroring / Preview | Atasan membaca isi surat langsung di browser. |
| 6 | Pencarian | Mencari surat berdasarkan nomor, perihal, tanggal, atau pengirim. |
| 7 | Filter | Menyaring surat berdasarkan periode dan kategori. |
| 8 | Hak akses | Membatasi fitur sesuai peran pengguna. |
| 9 | Riwayat aktivitas | Mencatat aktivitas penting, seperti upload dan perubahan data. |

### Fitur tambahan untuk tahap berikutnya

- Notifikasi ketika surat baru diunggah.
- Tanda surat sudah dibaca oleh atasan.
- Disposisi dan komentar pada surat.

## 5. Alur Kerja Aplikasi

1. **Pengguna login.** Staf atau atasan masuk dengan akun masing-masing.
2. **Staf mengunggah surat.** Staf mengisi metadata dan mengunggah file PDF.
3. **Sistem menyimpan surat.** Data masuk ke database dan file disimpan secara aman.
4. **Atasan membuka dashboard.** Surat terbaru tersedia di daftar surat.
5. **Atasan membaca surat.** PDF ditampilkan langsung di aplikasi sesuai hak akses.

## 6. Rancangan Halaman

### 6.1 Dashboard

Menampilkan jumlah surat, surat terbaru, aktivitas pengguna, dan tombol untuk menambahkan surat. Atasan dapat langsung melihat dokumen yang baru masuk.

### 6.2 Daftar Surat

Berisi tabel dengan kolom:

- Nomor surat
- Tanggal surat
- Perihal
- Pengirim
- Tanggal upload
- Aksi: **Lihat Surat**

### 6.3 Detail / Preview Surat

Menampilkan metadata surat dan PDF langsung di halaman aplikasi, sehingga atasan tidak perlu membuka aplikasi lain untuk membaca surat.

## 7. Struktur Database MySQL

Untuk MVP, gunakan dua tabel inti dan satu tabel pendukung untuk pencatatan aktivitas.

### 7.1 Tabel `users`

| Kolom | Tipe data | Keterangan |
|---|---|---|
| `id` | BIGINT | Primary key |
| `name` | VARCHAR(100) | Nama pengguna |
| `email` | VARCHAR(150) | Email login |
| `password` | VARCHAR(255) | Password yang sudah di-hash |
| `role` | ENUM / VARCHAR | `staf`, `atasan`, atau `admin` |
| `is_active` | BOOLEAN | Status akun |

### 7.2 Tabel `surat`

| Kolom | Tipe data | Keterangan |
|---|---|---|
| `id` | BIGINT | Primary key |
| `nomor_surat` | VARCHAR(100) | Nomor surat |
| `tanggal_surat` | DATE | Tanggal dokumen |
| `perihal` | VARCHAR(255) | Perihal surat |
| `pengirim` | VARCHAR(150) | Asal surat |
| `kategori` | VARCHAR(50) | Kategori surat |
| `file_path` | VARCHAR(255) | Lokasi file PDF |
| `keterangan` | TEXT | Catatan tambahan |
| `uploaded_by` | BIGINT | Relasi ke `users.id` |
| `created_at` | TIMESTAMP | Waktu upload |
| `updated_at` | TIMESTAMP | Waktu perubahan |

### 7.3 Tabel `activity_logs`

Mencatat pengguna yang mengunggah, mengubah metadata, mengganti file, atau membuka surat. Jika aktivitas baca perlu diaudit, simpan waktu dan identitas pengguna dengan pembatasan akses yang sesuai.

## 8. Teknologi yang Disarankan

| Komponen | Teknologi | Kegunaan |
|---|---|---|
| Backend | Laravel 10 atau 11 | Login, hak akses, validasi, upload, dan pengelolaan surat |
| Database | MySQL | Menyimpan data surat, pengguna, dan aktivitas |
| Frontend | Blade + Bootstrap 5 | Antarmuka responsif untuk komputer dan HP |
| File viewer | PDF viewer di browser | Menampilkan surat PDF di aplikasi |

## 9. Ketentuan Keamanan

- Login diwajibkan untuk semua pengguna.
- File surat disimpan di lokasi privat, bukan direktori publik yang dapat diakses melalui URL langsung.
- Akses PDF diperiksa melalui autentikasi dan otorisasi di server.
- Validasi format PDF, ukuran file, dan jenis konten dilakukan saat upload.
- Password disimpan dalam bentuk hash, bukan teks biasa.
- Lakukan pencadangan database dan file secara berkala.
- Dokumen rahasia atau berisi data pribadi hanya dapat diakses pengguna yang memiliki kewenangan.
- Penghapusan dan penggantian dokumen dicatat agar riwayat perubahan dapat diperiksa.

**Catatan:** fitur mirroring tidak otomatis berarti surat boleh dibagikan kepada semua atasan. Hak akses sebaiknya ditentukan berdasarkan unit kerja atau kewenangan jika dokumen bersifat terbatas.

## 10. Batasan Versi Pertama (MVP)

Versi pertama cukup mencakup:

- Login dan logout.
- Dashboard sederhana.
- Upload surat PDF beserta metadata.
- Daftar dan pencarian surat.
- Preview PDF langsung di browser.
- Hak akses staf dan atasan.
- Riwayat upload dan perubahan data.

Fitur disposisi, tanda tangan elektronik, OCR, notifikasi WhatsApp, dan integrasi dengan sistem persuratan resmi belum perlu dibuat pada tahap awal.

## 11. Kriteria Keberhasilan

Aplikasi dinyatakan siap digunakan jika:

1. Staf dapat mengunggah surat PDF beserta datanya.
2. Surat yang diunggah langsung muncul di daftar surat.
3. Atasan dapat melihat surat melalui akun miliknya.
4. Pencarian surat berfungsi berdasarkan nomor atau perihal.
5. Pengguna yang tidak berwenang tidak dapat membuka file surat melalui tautan langsung.
6. Aktivitas penting dapat ditelusuri kembali.

## 12. Keputusan yang Perlu Ditentukan Sebelum Coding

Tentukan kebutuhan berikut sebelum membangun aplikasi:

1. **Hak akses atasan**
   - Semua surat yang diunggah staf.
   - Hanya surat dari staf atau unit kerja tertentu.
   - Hanya surat yang ditujukan atau dibagikan kepada atasan.

2. **Jenis surat**
   - Surat masuk saja.
   - Surat keluar saja.
   - Surat masuk dan surat keluar.

3. **Lokasi aplikasi**
   - Komputer kantor / server lokal.
   - Hosting atau server yang dapat diakses melalui internet.
   - Belum ditentukan.

4. **Kewenangan atasan**
   - Hanya melihat dan membaca surat.
   - Melihat, membaca, dan mengunduh surat.
   - Melihat surat serta memberikan disposisi atau instruksi.

Setelah keputusan ini ditetapkan, PRD dapat dikembangkan menjadi struktur folder Laravel, migration database, model, controller, routing, dan desain halaman yang siap mulai dikoding.
