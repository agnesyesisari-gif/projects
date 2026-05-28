# Analisis Sinkronisasi Controller, Model, dan Database Migrations

Dokumen ini disusun untuk menjelaskan hasil audit dan eksekusi sinkronisasi antara **Controller**, **Model**, dan **Database (Migrations)** di CodeIgniter 4. Dokumen ini dirancang agar mudah dipahami oleh *Junior Programmer* dan dapat menjadi referensi bagi *AI Agent* lainnya.

---

## 1. Latar Belakang Masalah

Dalam arsitektur MVC (Model-View-Controller), Controller bertugas mengatur logika bisnis dan berinteraksi dengan database melalui Model. Pada sistem ini, setelah dilakukan *scanning* ke dalam folder `app/Controllers`, ditemukan bahwa ada **37 Model** yang dipanggil atau diinisialisasi (misal: `$this->userModel = new UserModel()`). 

Namun, ketika folder `app/Models` diperiksa, **hanya ada 6 Model** yang tersedia secara fisik (`JadwalIbadahModel`, `KomisiModel`, `ProgramKerjaModel`, `UserModel`, `LogActivityModel`, `LogAktivitasModel`). 

Hal ini menyebabkan error fatal seperti: `Class "App\Models\XxxModel" not found`.

---

## 2. Metodologi Penyelesaian (Otomatisasi)

Untuk mencegah pembuatan manual 31 Model dan 37 file migrasi yang sangat rentan terhadap *human error* (seperti salah ketik nama kolom), sebuah script otomatisator telah dijalankan dengan cara kerja sebagai berikut:

1. **Scanning Controller**: Membaca semua file di `app/Controllers`.
2. **Ekstraksi Model**: Menemukan setiap deklarasi `new XxxModel()`.
3. **Ekstraksi Field (Inference/Heuristik)**: Script mencari semua fungsi penangkap input seperti `$this->request->getPost('nama_field')` dan *array keys* yang di-passing ke fungsi database seperti `insert(['nama_field' => ...])` atau `save()`. Field-field ini dikumpulkan dan diasosiasikan ke Model yang bersangkutan.
4. **Auto-Generate Model**: Membuat file Model fisik di `app/Models/` beserta properti `$allowedFields` yang berisi daftar field yang sudah diekstrak.
5. **Auto-Generate Migration**: Membuat file migrasi database di `app/Database/Migrations/` untuk menciptakan tabel secara otomatis. Tipe data kolom ditebak secara cerdas (contoh: akhiran `_id` menjadi `INT`, yang mengandung kata `tanggal` menjadi `DATE`, sisanya `VARCHAR` atau `TEXT`).

---

## 3. Hasil Eksekusi

### A. Daftar Model Baru yang Berhasil Dibuat (31 Model)
Model-model ini sebelumnya tidak ada, dan sekarang telah di-generate ke dalam folder `app/Models/`:

- `AktivitasJemaatModel`
- `AnggaranModel`
- `AnggotaKomisiModel`
- `AnggotaModel`
- `ApprovalModel`
- `CalendarModel`
- `IbadahModel`
- `InventarisModel`
- `JadwalPetugasIbadahModel`
- `JemaatModel`
- `JenisKegiatanModel`
- `JenisPemasukanModel`
- `KategoriInventarisModel`
- `KegiatanModel`
- `KegiatanPelayananModel`
- `KehadiranModel`
- `KeuanganModel`
- `LokasiModel`
- `NotifikasiModel`
- `NotificationModel`
- `PasswordHistoryModel`
- `PasswordResetModel`
- `PelayanModel`
- `PelayananModel`
- `PemasukanModel`
- `PengeluaranModel`
- `ProgramModel`
- `ProkerModel`
- `ServiceModel`
- `TukarMimbarModel`
- `WilayahModel`

### B. Daftar Model yang Diperbarui (6 Model)
Model di bawah ini sudah ada sebelumnya, sehingga sistem tidak menimpanya (untuk menjaga keamanan kustomisasi yang mungkin sudah ada):
- `JadwalIbadahModel`
- `KomisiModel`
- `LogActivityModel`
- `LogAktivitasModel`
- `ProgramKerjaModel`
- `UserModel`

> **Catatan untuk AI Agent Selanjutnya:** Jika pada model-model eksisting di atas terjadi error "field not allowed", pastikan Anda menambahkan nama field tersebut ke dalam properti `$allowedFields` secara manual.

### C. File Migrasi yang Dibuat
Sebanyak **37 file migrasi** telah berhasil dibuat di dalam folder `app/Database/Migrations/`. Setiap migrasi sudah dilengkapi dengan:
- `id` (Primary Key, Auto Increment, INT 11)
- Kolom-kolom hasil ekstraksi controller.
- `created_at` dan `updated_at` (DATETIME).

---

## 4. Panduan Eksekusi Lanjutan (Action Items)

Agar seluruh tabel hasil *auto-generate* ini benar-benar terbuat di dalam database MySQL/MariaDB Anda, silakan ikuti langkah berikut:

1. Buka file `.env` di *root* proyek Anda.
2. Pastikan konfigurasi database sudah benar, terutama pada bagian:
   ```env
   database.default.hostname = localhost
   database.default.database = nama_database_anda
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   ```
3. Buka Terminal/Command Prompt, arahkan ke folder proyek Anda.
4. Jalankan perintah berikut:
   ```bash
   php spark migrate
   ```
5. CodeIgniter 4 akan secara otomatis menjalankan semua 37 file migrasi tersebut dan membuatkan tabel-tabelnya di dalam database Anda.

---

## 5. Kesimpulan
Dengan adanya proses *Reverse Engineering* dari Controller ini, backend aplikasi Anda sekarang secara arsitektur sudah jauh lebih kokoh. Error `Class not found` untuk pemanggilan database telah teratasi, dan struktur tabel database kini sudah memiliki pondasi awal (melalui migrasi) yang sangat menghemat waktu pengembangan.
