# 🗳️ Pilketos

**Pilketos** adalah platform digital sederhana untuk pemilihan ketua OSIS secara online.  
Dibuat dalam rangka Sertifikasi Kompetensi (Sertikom) di sekolah.

Proyek ini bertujuan untuk membuat proses pemilihan ketua OSIS jadi lebih modern, efisien, dan transparan, dengan fitur lengkap untuk admin dan siswa.

---

## ✨ Fitur Utama

- 👤 **Voting**: Memilih calon ketua OSIS dengan input NISN.
- 🧑‍💼 **Panel Admin**:
    - Menambahkan & mengedit **data calon ketua OSIS** dan **data admin**.
    - Menambahkan **data pemilih (hak suara/NISN)**.
    - Melihat **hasil laporan pemungutan suara** lengkap.
- 📊 **Laporan**: Grafik & persentase suara otomatis dari database.

---

## 🛠️ Teknologi yang Digunakan

- 🐘 **PHP + Laravel 13**
- 🧬 **SQLite** (default) / **MySQL** (opsional)
- 🌐 **Blade + Alpine.js**
- 🎨 **TailwindCSS v4** (Desain modern & responsif)
- ⚡ **Vite** (Build tool)

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js & NPM

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/username/pilketos.git
cd pilketos

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

# 7. Buat symlink storage (untuk foto calon)
php artisan storage:link

# 8. Build aset frontend
npm run build

# 9. Jalankan server
php artisan serve
```

Aplikasi berjalan di `http://localhost:8000`

### Akun Admin Default

| Field    | Value             |
|----------|-------------------|
| Email    | admin@gmail.com   |
| Password | admin123          |

> Ganti password setelah login pertama kali.

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

## 📸 Tampilan Antarmuka

<div style="display: flex; gap: 20px;">
  <img src="https://ux.appcloud.id/imaging/images/TwYOv7EdlX.png" alt="Halaman Voting" width="48%">
  <img src="https://ux.appcloud.id/imaging/images/qBJ79qHWto.png" alt="Halaman Admin" width="48%">
  <img src="https://ux.appcloud.id/imaging/images/ZtpPG2zfwT.png" alt="Halaman Laporan" width="48%">
  <img src="https://ux.appcloud.id/imaging/images/TauK3TTrYd.png" alt="Halaman Admin" width="48%">
</div>

> 🖼️ _Kiri-Atas: Halaman Voting — Kanan-Atas: Panel Dashboard | Kiri-Bawah: Panel Laporan — Kanan-Bawah: Panel Calon_

---

> 🕒 **Waktu Pengerjaan:** 34 Jam  
> 🏆 **Nilai** : 100
