# Hasil Analisis dan Perancangan Routes CodeIgniter 4

Dokumen ini merupakan hasil sinkronisasi end-to-end antara **Backend (Controllers)** dan **Frontend (Views)**. Rute (routes) yang dirancang di bawah ini sudah disesuaikan agar **sama persis** dengan link yang telah ada di dalam folder `app/Views`, sehingga Anda **tidak perlu mengubah satupun baris kode pada UI**.

Dokumen ini disusun agar mudah dipahami oleh *Junior Programmer* maupun *AI Agent* lain yang akan melanjutkan pengembangan sistem ini.

---

## 1. Analisis Metodologi

Saat mendesain rute untuk CodeIgniter 4, kita mempertemukan dua sisi:
- **Dari sisi Frontend (`app/Views`)**: Terdapat banyak pemanggilan URL menggunakan `site_url('endpoint/action/id')`. Sebagian besar pemanggilan ini dilakukan di *root level* tanpa grup prefix (seperti `anggota/create`, bukan `admin/anggota/create`).
- **Dari sisi Backend (`app/Controllers`)**: Kita memiliki berbagai class controller dan method public (misal: class `AnggotaController` dengan method `index`, `create`, `show`, `edit`, dll.).

**Strategi Routing:**
Karena kita tidak ingin merusak UI yang sudah jadi, kita memetakan URL Frontend secara langsung (eksplisit) ke Method Backend. Kita juga menggunakan placeholder seperti `(:segment)` untuk menangkap variabel ID secara dinamis.

---

## 2. Struktur Controller vs Routes (Panduan untuk Junior Programmer)

Berikut adalah contoh cara membaca pemetaan rute:

> `$routes->get('anggota/edit/(:segment)', 'AnggotaController::edit/$1');`

- `get`: Metode HTTP yang digunakan (GET untuk mengambil data, POST untuk mengirim data form).
- `'anggota/edit/(:segment)'`: Endpoint URL yang muncul di browser dan dipanggil oleh Views. `(:segment)` bertindak sebagai penangkap ID (contoh: `/anggota/edit/12`).
- `'AnggotaController::edit/$1'`: Maksudnya adalah "Jalankan method `edit` di dalam file `AnggotaController.php`, dan berikan ID yang ditangkap tadi sebagai argumen pertama (`$1`)".

---

## 3. Implementasi Kode Routes (Siap Pakai)

> **Instruksi untuk Programmer/AI:**
> Buka file `app/Config/Routes.php`. Anda dapat menyalin dan menempelkan baris kode di bawah ini untuk mendaftarkan semua modul yang terdeteksi pada sistem.

```php
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==========================================
// 1. OTENTIKASI & AKUN
// ==========================================
// Rute default UI memanggil login dan register secara flat
$routes->get('login', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');

// Rute auth form action
$routes->post('auth/process_login', 'AuthController::processLogin');
$routes->get('auth/forgot_password', 'AuthController::forgotPassword');
$routes->post('auth/process_forgot_password', 'AuthController::processForgotPassword');

// Rute manajemen kata sandi
$routes->post('password/send-reset', 'PasswordController::processResetPassword');
$routes->post('password/update', 'PasswordController::processChangePassword');

// ==========================================
// 2. DASHBOARD
// ==========================================
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Loadhelper::dashboard');
$routes->get('admin/dashboard', 'Loadhelper::dashboard');

// ==========================================
// 3. ANGGOTA & JEMAAT
// ==========================================
$routes->get('anggota', 'AnggotaController::index');
$routes->get('anggota/create', 'AnggotaController::create');
$routes->post('anggota/store', 'AnggotaController::store');
$routes->get('anggota/view/(:segment)', 'AnggotaController::show/$1'); // View sering pakai ID
$routes->get('anggota/edit/(:segment)', 'AnggotaController::edit/$1');
$routes->post('anggota/update/(:segment)', 'AnggotaController::update/$1');
$routes->get('anggota/delete/(:segment)', 'AnggotaController::delete/$1');

// ==========================================
// 4. KOMISI
// ==========================================
$routes->get('komisi', 'Komisi::index');
$routes->get('komisi/create', 'Komisi::new');
$routes->post('komisi/store', 'Komisi::create');
$routes->get('komisi/show/(:segment)', 'Komisi::show/$1');
$routes->get('komisi/edit/(:segment)', 'Komisi::edit/$1');
$routes->post('komisi/update/(:segment)', 'Komisi::update/$1');
$routes->post('komisi/anggota/store', 'Komisi::simpanAnggota'); // Relasi anggota komisi

// ==========================================
// 5. KEUANGAN (Anggaran, Pemasukan, Pengeluaran)
// ==========================================
// Utama
$routes->get('keuangan', 'Keuangan::index');
$routes->post('keuangan/store-pemasukan', 'Keuangan::tambahPemasukan');
$routes->post('keuangan/store-pengeluaran', 'Keuangan::tambahPengeluaran');
$routes->post('keuangan/update/(:segment)', 'Keuangan::update/$1');

// Anggaran
$routes->get('anggaran', 'Anggaran::index');
$routes->get('anggaran/create', 'Anggaran::create');
$routes->post('anggaran/store', 'Anggaran::store');
$routes->get('anggaran/show/(:segment)', 'Anggaran::show/$1');
$routes->get('anggaran/edit/(:segment)', 'Anggaran::edit/$1');
$routes->post('anggaran/update/(:segment)', 'Anggaran::update/$1');
$routes->get('anggaran/export-pdf', 'Anggaran::exportPdf');

// Pemasukan
$routes->get('pemasukan', 'Pemasukan::index');
$routes->get('pemasukan/create', 'Pemasukan::create');
$routes->post('pemasukan/store', 'Pemasukan::store');
$routes->get('pemasukan/show/(:segment)', 'Pemasukan::show/$1');
$routes->get('pemasukan/edit/(:segment)', 'Pemasukan::edit/$1');
$routes->post('pemasukan/update/(:segment)', 'Pemasukan::update/$1');

// Pengeluaran
$routes->get('pengeluaran', 'Pengeluaran::index');
$routes->get('pengeluaran/create', 'Pengeluaran::create');
$routes->post('pengeluaran/store', 'Pengeluaran::store');
$routes->get('pengeluaran/show/(:segment)', 'Pengeluaran::show/$1');
$routes->get('pengeluaran/edit/(:segment)', 'Pengeluaran::edit/$1');
$routes->post('pengeluaran/update/(:segment)', 'Pengeluaran::update/$1');

// ==========================================
// 6. KEGIATAN & JADWAL IBADAH
// ==========================================
$routes->get('kegiatan', 'Kegiatan::index');
$routes->get('kegiatan/create', 'Kegiatan::index'); 
$routes->post('kegiatan/store', 'Kegiatan::save');
$routes->get('kegiatan/detail/(:segment)', 'Kegiatan::detail/$1');
$routes->get('kegiatan/edit/(:segment)', 'Kegiatan::index');
$routes->post('kegiatan/update/(:segment)', 'Kegiatan::update/$1');
$routes->get('kegiatan/duplikat/(:segment)', 'Kegiatan::index');

// Rute khusus hapus kegiatan yang terdeteksi di views
$routes->get('hapus/kegiatan/(:segment)', 'HapusController::prosesHapus/$1');

// Jadwal Petugas Ibadah
$routes->get('jadwal-petugas-ibadah', 'JadwalPetugasIbadah::index');
$routes->get('jadwal-petugas-ibadah/create', 'JadwalPetugasIbadah::create');
$routes->post('jadwal-petugas-ibadah/store', 'JadwalPetugasIbadah::store');
$routes->get('jadwal-petugas-ibadah/edit/(:segment)', 'JadwalPetugasIbadah::edit/$1');
$routes->post('jadwal-petugas-ibadah/update/(:segment)', 'JadwalPetugasIbadah::update/$1');

// ==========================================
// 7. PROGRAM KERJA (Kerja)
// ==========================================
$routes->get('kerja', 'Kerja::index');
$routes->get('kerja/create', 'Kerja::create');
$routes->post('kerja/store', 'Kerja::store');
$routes->get('kerja/view/(:segment)', 'Kerja::show/$1');
$routes->get('kerja/edit/(:segment)', 'Kerja::edit/$1');
$routes->post('kerja/update/(:segment)', 'Kerja::update/$1');

// ==========================================
// 8. NOTIFIKASI
// ==========================================
$routes->get('notifikasi', 'NotifikasiController::index');
$routes->get('notifikasi/create', 'NotifikasiController::create');
$routes->post('notifikasi/store', 'NotifikasiController::store');
$routes->get('notifikasi/show/(:segment)', 'NotifikasiController::show/$1');
$routes->post('notifikasi/save-settings', 'NotifikasiController::saveUserSettings');

// ==========================================
// 9. LOG & SYSTEM ADMIN
// ==========================================
$routes->get('log-activity', 'LogActivity::index');

// Rute khusus maintenance sistem
$routes->get('admin/system/create-directories', 'Admin\SystemController::createDirectories');
$routes->get('admin/system/download-paths-config', 'Admin\SystemController::downloadPathsConfig');

// ==========================================
// 10. LAIN-LAIN (Kalender, Laporan PDF)
// ==========================================
$routes->get('kalender', 'CalendarController::index');
$routes->get('laporan/cetak_pdf_ibadah', 'Pdf::jadwalIbadah');
$routes->get('laporan/cetak_pdf_program', 'Pdf::programKerja');
```

---

## 4. Langkah Berikutnya (Action Items)

1. **Copy dan Paste**: Salin seluruh blok kode PHP di atas dan tempelkan ke dalam file `app/Config/Routes.php` (hapus definisi rute lama jika diperlukan).
2. **Uji Coba UI**: Buka aplikasi di browser dan klik menu-menu navigasi. Seharusnya semua aksi (seperti buka halaman Edit, Tambah Anggota, dll) akan berfungsi dan tidak menampilkan halaman 404 Not Found.
3. **Pembersihan Cache (Jika Perlu)**: Apabila Anda merasa URL belum berubah, jalankan perintah `php spark optimize` di terminal Anda.
