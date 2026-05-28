# Analisis Routes & Views CodeIgniter 4

Laporan ini membandingkan endpoint yang digunakan di dalam `app/Views` dengan rute yang didefinisikan di `app/Config/Routes.php`.

## 1. Endpoint yang Digunakan di Views & Terdaftar di Routes

| Endpoint | Method | Handler | File View |
|---|---|---|---|
| `/log-activity` | `GET` | `\App\Controllers\LogActivity::index` | admin/log_activity/detail.php |
| `/notifikasi/store` | `POST` | `\App\Controllers\NotifikasiController::store` | admin/notifikasi/create.php |
| `/notifikasi` | `GET` | `\App\Controllers\NotifikasiController::index` | admin/notifikasi/create.php<br>admin/notifikasi/show.php |
| `/notifikasi/create` | `GET` | `\App\Controllers\NotifikasiController::create` | admin/notifikasi/dashboard.php<br>admin/notifikasi/list.php |
| `/pemasukan/store` | `POST` | `\App\Controllers\Pemasukan::store` | admin/pemasukan/create.php |
| `/pemasukan` | `GET` | `\App\Controllers\Pemasukan::index` | admin/pemasukan/create.php<br>admin/pemasukan/edit.php<br>admin/pemasukan/laporan.php<br>... |
| `/pemasukan/create` | `GET` | `\App\Controllers\Pemasukan::create` | admin/pemasukan/index.php |
| `/pengeluaran/store` | `POST` | `\App\Controllers\Pengeluaran::store` | admin/pengeluaran/create.php |
| `/pengeluaran` | `GET` | `\App\Controllers\Pengeluaran::index` | admin/pengeluaran/create.php<br>admin/pengeluaran/edit.php<br>admin/pengeluaran/laporan.php<br>... |
| `/pengeluaran/create` | `GET` | `\App\Controllers\Pengeluaran::create` | admin/pengeluaran/index.php |
| `/anggaran/store` | `POST` | `\App\Controllers\Anggaran::store` | anggaran/create.php |
| `/anggaran` | `GET` | `\App\Controllers\Anggaran::index` | anggaran/create.php<br>anggaran/edit.php<br>anggaran/show.php |
| `/anggaran/create` | `GET` | `\App\Controllers\Anggaran::create` | anggaran/Index.php |
| `/anggota/store` | `POST` | `\App\Controllers\AnggotaController::store` | anggota/Form.php |
| `/anggota` | `GET` | `\App\Controllers\AnggotaController::index` | anggota/Form.php<br>anggota/ViewAnggota.php |
| `/anggota/create` | `GET` | `\App\Controllers\AnggotaController::create` | anggota/Index.php |
| `/dashboard` | `GET` | `\App\Controllers\DashboardController::index` | jadwalibadah/Kalender.php<br>kegiatan/DetailKegiatan.php<br>user/Dashboard.php |
| `/kalender` | `GET` | `\App\Controllers\CalendarController::index` | jadwalibadah/Kalender.php |
| `/program-kerja` | `GET` | `\App\Controllers\Kerja::index` | jadwalibadah/Kalender.php<br>layout/Navbar.php |
| `/jadwal-petugas-ibadah/store` | `POST` | `\App\Controllers\JadwalPetugasIbadah::store` | jadwal_petugas_ibadah/create.php |
| `/jadwal-petugas-ibadah` | `GET` | `\App\Controllers\JadwalPetugasIbadah::index` | jadwal_petugas_ibadah/create.php<br>jadwal_petugas_ibadah/edit.php |
| `/jadwal-petugas-ibadah/create` | `GET` | `\App\Controllers\JadwalPetugasIbadah::create` | jadwal_petugas_ibadah/index.php |
| `/kegiatan` | `GET` | `\App\Controllers\Kegiatan::index` | kegiatan/detail.php<br>kegiatan/DetailKegiatan.php |
| `/kegiatan/store` | `POST` | `\App\Controllers\Kegiatan::save` | kegiatan/Form.php |
| `/kegiatan/create` | `GET` | `\App\Controllers\Kegiatan::index` | kegiatan/Index.php |
| `/kerja/store` | `POST` | `\App\Controllers\Kerja::store` | kerja/create.php |
| `/kerja` | `GET` | `\App\Controllers\Kerja::index` | kerja/create.php<br>kerja/dashboard.php<br>kerja/edit.php<br>... |
| `/kerja/create` | `GET` | `\App\Controllers\Kerja::create` | kerja/index.php |
| `/keuangan` | `GET` | `\App\Controllers\Keuangan::index` | keuangan/edit_pemasukan.php<br>keuangan/edit_pengeluaran.php<br>keuangan/tambah_pemasukan.php<br>... |
| `/keuangan/store-pemasukan` | `POST` | `\App\Controllers\Keuangan::tambahPemasukan` | keuangan/tambah_pemasukan.php |
| `/keuangan/store-pengeluaran` | `POST` | `\App\Controllers\Keuangan::tambahPengeluaran` | keuangan/tambah_pengeluaran.php |
| `/komisi/anggota/store` | `POST` | `\App\Controllers\Komisi::simpanAnggota` | komisi/anggota/create.php |
| `/komisi/store` | `POST` | `\App\Controllers\Komisi::create` | komisi/create.php |
| `/komisi` | `GET` | `\App\Controllers\Komisi::index` | komisi/create.php<br>komisi/edit.php<br>komisi/show.php |
| `/komisi/create` | `GET` | `\App\Controllers\Komisi::new` | komisi/index.php |
| `/laporan/cetak_pdf_ibadah` | `GET` | `\App\Controllers\Pdf::jadwalIbadah` | laporan/Index.php |
| `/laporan/cetak_pdf_program` | `GET` | `\App\Controllers\Pdf::programKerja` | laporan/Index.php |
| `/admin/dashboard` | `GET` | `\App\Controllers\DashboardController::index` | layout/Navbar.php<br>layout/Sidebar.php |
| `/profil` | `GET` | `\App\Controllers\AuthController::profile` | layout/Navbar.php |
| `/logout` | `GET` | `\App\Controllers\AuthController::logout` | layout/Navbar.php |
| `/login` | `GET` | `\App\Controllers\AuthController::login` | layout/Navbar.php<br>password/forgot.php |
| `/register` | `GET` | `\App\Controllers\AuthController::register` | layout/Navbar.php |
| `/notifikasi/save-settings` | `POST` | `\App\Controllers\NotifikasiController::saveUserSettings` | notifikasi/settings.php |
| `/password/update` | `POST` | `\App\Controllers\PasswordController::processChangePassword` | password/change.php |
| `/password/send-reset` | `POST` | `\App\Controllers\PasswordController::processResetPassword` | password/forgot.php |
| `/programkerja` | `GET` | `\App\Controllers\Kerja::index` | proker/TambahProker.php |
| `/auth/process_login` | `POST` | `\App\Controllers\AuthController::processLogin` | user/Login.php |
| `/auth/forgot_password` | `GET` | `\App\Controllers\AuthController::forgotPassword` | user/Login.php |
| `/auth/process_forgot_password` | `POST` | `\App\Controllers\AuthController::processForgotPassword` | user/LupaPassword.php |
| `/auth/login` | `GET` | `\App\Controllers\AuthController::login` | user/LupaPassword.php<br>user/Registrasi.php |
| `/admin/system/create-directories` | `GET` | `\App\Controllers\Admin\SystemController::createDirectories` | user/Paths.php |
| `/admin/system/download-paths-config` | `GET` | `\App\Controllers\Admin\SystemController::downloadPathsConfig` | user/Paths.php |

## 2. Endpoint yang Digunakan di Views TETAPI TIDAK Terdaftar di Routes (Potensi 404)

| Endpoint | File View (Tempat Digunakan) |
|---|---|
| `/anggaran.php` | anggaran/Approval.php (href/action)<br>anggaran/Detail.php (href/action)<br>anggaran/Tambah.php (href/action) |
| `/approval.php` | anggaran/Approval.php (href/action) |
| `/index.php` | anggaran/Detail.php (href/action)<br>anggaran/Tambah.php (href/action)<br>kehadiran/Detail.php (href/action)<br>... |
| `/../../logout.php` | anggaran/Detail.php (href/action)<br>anggaran/Tambah.php (href/action)<br>kehadiran/Detail.php (href/action)<br>... |
| `/anggota/delete` | anggota/Index.php |
| `/jadwal` | jadwalibadah/EditJadwal.php<br>jadwalibadah/Kalender.php<br>jadwalibadah/Tambah.php<br>... |
| `/jadwal/create` | jadwalibadah/Index.php |
| `/pengurus` | jadwalibadah/Kalender.php |
| `/jemaat` | jadwalibadah/Kalender.php<br>layout/Header.php |
| `/laporan` | jadwalibadah/Kalender.php<br>user/Dashboard.php |
| `/kalender/tambah` | jadwalibadah/Kalender.php |
| `/kehadiran.php` | kehadiran/Detail.php (href/action)<br>kehadiran/Import.php (href/action)<br>kehadiran/Kehadiran.php (href/action) |
| `/../logout.php` | kehadiran/Kehadiran.php (href/action)<br>Tambah.php (href/action) |
| `/jadwalpetugasibadah.php` | kehadiran/Kehadiran.php (href/action) |
| `/laporan.php` | kehadiran/Kehadiran.php (href/action) |
| `/petugas.php` | kehadiran/Kehadiran.php (href/action) |
| `/rekapitulasi.php` | kehadiran/Kehadiran.php (href/action) |
| `/../controllers/KehadiranController.php` | kehadiran/Kehadiran.php (href/action) |
| `/laporan/ibadah` | laporan/Index.php |
| `/laporan/program_kerja` | laporan/Index.php |
| `/jadwal-ibadah` | layout/Navbar.php |
| `/galeri` | layout/Navbar.php |
| `/tentang` | layout/Navbar.php |
| `/kontak` | layout/Navbar.php |
| `/admin/jadwal-ibadah` | layout/Sidebar.php |
| `/admin/jenis-ibadah` | layout/Sidebar.php |
| `/admin/program-kerja` | layout/Sidebar.php |
| `/admin/kategori-program` | layout/Sidebar.php |
| `/admin/laporan` | layout/Sidebar.php |
| `/admin/pengguna` | layout/Sidebar.php |
| `/admin/pengaturan` | layout/Sidebar.php |
| `/program` | proker/DetailProker.php<br>proker/EditProker.php<br>user/Dashboard.php |
| `/programkerja/tambah` | proker/Index.php |
| `/` | Tambah.php (href/action) |
| `/../controllers/JadwalController.php` | Tambah.php (href/action) |
| `/../controllers/ExportController.php` | Tambah.php (href/action) |
| `/jadwal/tambah` | user/Dashboard.php |
| `/program/tambah` | user/Dashboard.php |
| `/pelayan` | user/Dashboard.php |
| `/jadwal/mendatang` | user/Dashboard.php |
| `/jadwal/kalender` | user/Dashboard.php |
| `/pengumuman` | user/Dashboard.php |
| `/pengaturan` | user/Dashboard.php |
| `/jadwal/detail` | user/Dashboard.php |

## 3. Rute yang Terdaftar TETAPI TIDAK Digunakan/Ditemukan di Views

> *Catatan: Rute ini mungkin digunakan sebagai API endpoint, diakses via AJAX dinamis, atau redirect dari Controller.*

| Endpoint Terdaftar | Method | Handler |
|---|---|---|
| `/anggota/view/([^/]+)` | `GET` | `\App\Controllers\AnggotaController::show/$1` |
| `/anggota/edit/([^/]+)` | `GET` | `\App\Controllers\AnggotaController::edit/$1` |
| `/anggota/delete/([^/]+)` | `GET` | `\App\Controllers\AnggotaController::delete/$1` |
| `/komisi/show/([^/]+)` | `GET` | `\App\Controllers\Komisi::show/$1` |
| `/komisi/edit/([^/]+)` | `GET` | `\App\Controllers\Komisi::edit/$1` |
| `/anggaran/show/([^/]+)` | `GET` | `\App\Controllers\Anggaran::show/$1` |
| `/anggaran/edit/([^/]+)` | `GET` | `\App\Controllers\Anggaran::edit/$1` |
| `/anggaran/export-pdf` | `GET` | `\App\Controllers\Anggaran::exportPdf` |
| `/pemasukan/show/([^/]+)` | `GET` | `\App\Controllers\Pemasukan::show/$1` |
| `/pemasukan/edit/([^/]+)` | `GET` | `\App\Controllers\Pemasukan::edit/$1` |
| `/pengeluaran/show/([^/]+)` | `GET` | `\App\Controllers\Pengeluaran::show/$1` |
| `/pengeluaran/edit/([^/]+)` | `GET` | `\App\Controllers\Pengeluaran::edit/$1` |
| `/kegiatan/detail/([^/]+)` | `GET` | `\App\Controllers\Kegiatan::detail/$1` |
| `/hapus/kegiatan/([^/]+)` | `GET` | `\App\Controllers\HapusController::prosesHapus/$1` |
| `/jadwal-petugas-ibadah/edit/([^/]+)` | `GET` | `\App\Controllers\JadwalPetugasIbadah::edit/$1` |
| `/kerja/view/([^/]+)` | `GET` | `\App\Controllers\Kerja::show/$1` |
| `/kerja/edit/([^/]+)` | `GET` | `\App\Controllers\Kerja::edit/$1` |
| `/notifikasi/show/([^/]+)` | `GET` | `\App\Controllers\NotifikasiController::show/$1` |
| `//` | `GET` | `login` |
| `//` | `HEAD` | `login` |
| `//` | `POST` | `login` |
| `//` | `PATCH` | `login` |
| `//` | `PUT` | `login` |
| `//` | `DELETE` | `login` |
| `//` | `OPTIONS` | `login` |
| `//` | `TRACE` | `login` |
| `//` | `CONNECT` | `login` |
| `//` | `CLI` | `login` |
| `/anggota/update/([^/]+)` | `POST` | `\App\Controllers\AnggotaController::update/$1` |
| `/komisi/update/([^/]+)` | `POST` | `\App\Controllers\Komisi::update/$1` |
| `/keuangan/update/([^/]+)` | `POST` | `\App\Controllers\Keuangan::update/$1` |
| `/anggaran/update/([^/]+)` | `POST` | `\App\Controllers\Anggaran::update/$1` |
| `/pemasukan/update/([^/]+)` | `POST` | `\App\Controllers\Pemasukan::update/$1` |
| `/pengeluaran/update/([^/]+)` | `POST` | `\App\Controllers\Pengeluaran::update/$1` |
| `/kegiatan/update/([^/]+)` | `POST` | `\App\Controllers\Kegiatan::update/$1` |
| `/jadwal-petugas-ibadah/update/([^/]+)` | `POST` | `\App\Controllers\JadwalPetugasIbadah::update/$1` |
| `/kerja/update/([^/]+)` | `POST` | `\App\Controllers\Kerja::update/$1` |
