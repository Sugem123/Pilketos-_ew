# 🗳️ Pilketos — Sistem E-Voting OSIS

**Pilketos** adalah platform e-voting lengkap untuk pemilihan Ketua & Wakil Ketua OSIS sekolah.
Dibuat dalam rangka Sertifikasi Kompetensi (Sertikom) di sekolah.

Sistem ini membungkus seluruh siklus pemilihan — dari manajemen DPT, distribusi token, pencoblosan digital, hingga rekapitulasi suara manual (pleno) dan siaran langsung hasil — dalam satu aplikasi dengan antarmuka **dark luxury premium**.

---

## ✨ Fitur Utama

### 🗳️ Bilik Suara E-Voting
- Pemilihan dengan **Token Otorisasi sekali pakai** (auto-burn setelah memilih, 1 pemilih = 1 suara).
- Token personal tercetak di kartu pemilih — otomatis mengidentifikasi pemilih tanpa input NISN manual.
- Mendukung juga token tampilan (display token) dari panitia.
- Submit suara asinkron (fetch/JSON) — cepat, tanpa reload halaman.

### 🧑‍💼 Panel Admin
- **Calon Ketua**: CRUD calon lengkap dengan foto & visi-misi.
- **DPT (Hak Suara)**: klasifikasi **Siswa** (dengan kelas X–XII) dan **Guru/Tendik**, pencarian, filter, dan **import Excel** (+ template unduhan).
- **Token Display**: kelola token tampilan tambahan untuk bilik suara.
- **Audit Suara**: verifikasi manual suara digital (sah / tidak sah) dengan quick scanner token, verifikasi batch, dan tabel perbandingan suara digital vs pleno.
- **Konfigurasi**: profil sekolah (nama, kegiatan, tahun ajaran, alamat, **logo**), kuota hak suara, manajemen akun admin, dan **editor format surat undangan**.
- **Dashboard**: KPI real-time, grafik perolehan suara, dan matriks tracking partisipasi per kelas.

### 📺 Live Count Proyektor
- Halaman publik `/live-count` untuk ditampilkan di proyektor/layar besar saat pemungutan suara.
- Dua mode: **Quick Count** (hasil digital real-time) dan **Pleno Sah** (hasil audited panitia).

### 🖨️ Cetak Dokumen Resmi
- **Surat Undangan Panggilan** pemilih (per pemilih/kelas/kategori, lengkap dengan token masing-masing).
- **Kartu Hak Memilih** (voter card berisi token personal).
- **Berita Acara Pleno** (rekapitulasi resmi: total DPT, suara sah/tidak sah, perolehan per calon, pemenang).
- Redaksi surat undangan (kop, pembuka, jadwal, lokasi, catatan kaki, penandatangan) dapat **diedit langsung dari panel admin** tanpa ubah kode.

---

## 🛠️ Teknologi yang Digunakan

- 🐘 **PHP 8.3 + Laravel 13**
- 🧬 **SQLite** (default) / **MySQL** (opsional)
- 🌐 **Blade + Alpine.js**
- 🎨 **TailwindCSS v4** (tema dark luxury, responsif)
- 📊 **Chart.js** (grafik hasil suara)
- 💬 **SweetAlert2** (dialog & notifikasi)
- ⚡ **Vite** (build tool)

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/Sugem123/Pilketos-_ew.git
cd Pilketos-_ew

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Buat database & jalankan migrasi + seeder
php artisan migrate --seed

# 7. Buat symlink storage (untuk logo & foto calon)
php artisan storage:link

# 8. Build aset frontend
npm run build

# 9. Jalankan server
php artisan serve
```

Aplikasi berjalan di `http://localhost:8000`

> Seeder otomatis membuat 30 kelas (X-1 s.d. XII-10), akun admin, data calon contoh, dan DPT contoh.

### Akun Admin Default

| Field              | Value           |
|--------------------|-----------------|
| Email              | admin@gmail.com |
| Username/Nama      | admin           |
| Password           | admin123        |

> Login menerima email, nama lengkap, atau username `admin`. Ganti password setelah login pertama kali.

### Menggunakan MySQL (Opsional)

Ubah konfigurasi database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pilketos
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan ulang migrasi:

```bash
php artisan migrate --seed
```

---

## ⚙️ Konfigurasi Sekolah & Surat Undangan

Konfigurasi branding sekolah **tidak disimpan di database**, melainkan di file `config.json` di root proyek:

```json
{
    "nama_sekolah": "SMA NEGERI 1 PRAMBON",
    "nama_kegiatan": "PEMILIHAN KETUA & WAKIL KETUA OSIS",
    "tahun_ajaran": "2026/2027",
    "alamat_sekolah": "JL. A.YANI SUGIHWARAS PRAMBON",
    "url_logo": "storage/branding/logo.png",
    "haksuara": 1050,
    "undangan_judul_kop": "PANITIA PEMILIHAN KETUA & WAKIL KETUA OSIS",
    "undangan_pembuka": "Bersama ini Panitia Pemilihan Ketua OSIS mengundang ...",
    "undangan_hari_tanggal": "Kamis, 20 Agustus 2026",
    "undangan_waktu": "08.00 - 13.00 WIB",
    "undangan_lokasi": "Aula SMAN 1 Prambon",
    "undangan_penandatangan": "Ketua Panitia Pelaksana"
}
```

Semua field di atas (termasuk logo) dapat diubah dari menu **Admin → Konfigurasi** langsung dari antarmuka — tidak perlu edit file manual. Perubahan langsung tampil di bilik suara, live count, kartu pemilih, dan surat undangan.

---

## 📖 Alur Penggunaan Pemilihan

1. **Persiapan** — Admin mengisi profil sekolah & format surat undangan, menambah calon, dan mengimpor DPT (siswa per kelas + guru).
2. **Distribusi** — Cetak **Kartu Hak Memilih** (berisi token personal) dan **Surat Undangan**, bagikan ke pemilih.
3. **Pemungutan** — Pemilih membuka bilik suara, memasukkan token, memilih calon. Token terbakar otomatis (satu kali pakai).
4. **Monitoring** — Panitia menayangkan `/live-count` (mode Quick Count) di proyektor.
5. **Rekapitulasi** — Setelah pemungutan, panitia memverifikasi suara lewat modul **Audit Suara** (sah/tidak sah), lalu mode **Pleno Sah** di live count menampilkan hasil resmi.
6. **Dokumentasi** — Cetak **Berita Acara Pleno** sebagai dokumen resmi hasil pemilihan.

---

## 📸 Tampilan Antarmuka

> 🖼️ _Dokumentasi tangkapan layar antarmuka dark luxury terbaru menyusul._

---

> 🕒 **Waktu Pengerjaan:** 34 Jam  
> 🏆 **Nilai** : 100
