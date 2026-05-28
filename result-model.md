# Hasil Analisis Inkonsistensi Nama Tabel Database

Dokumen ini berisi hasil analisis pengecekan pemanggilan nama tabel di Models, Controllers, dan Query Builder yang tidak sesuai dengan skema Database aktual.

## 1. Daftar Tabel di Database Aktual
Berikut adalah daftar tabel yang saat ini **benar-benar ada** di database:
```
aktivitas_jemaats
anggarans
anggota_komisis
anggotas
approvals
calendars
ibadahs
inventaris
jadwal_ibadah
jadwal_ibadahs
jadwal_petugas_ibadahs
jemaats
jenis_kegiatans
jenis_pemasukans
kategori_inventaris
kegiatan_pelayanans
kegiatans
kehadirans
keuangans
komisi
komisis
log_activitys
log_aktivitas
lokasis
migrations
notifications
notifikasis
password_historys
password_resets
pelayanans
pelayans
pemasukans
pengeluarans
program_kerja
program_kerjas
programs
prokers
services
tukar_mimbars
users
wilayahs
anggota_pelayanan
```

## 2. Pemetaan Model ke Tabel
| Model | Tabel yang Didefinisikan | Status |
|-------|--------------------------|--------|
| JadwalIbadahModel.php | `jadwal_ibadah` | ✅ OK |
| KomisiModel.php | `komisi` | ✅ OK |
| ProgramKerjaModel.php | `program_kerja` | ✅ OK |
| UserModel.php | `users` | ✅ OK |
| LogActivityModel.php | `log_activitys` | ✅ OK |
| AktivitasJemaatModel.php | `aktivitas_jemaats` | ✅ OK |
| AnggotaModel.php | `anggotas` | ✅ OK |
| IbadahModel.php | `ibadahs` | ✅ OK |
| KegiatanModel.php | `kegiatans` | ✅ OK |
| PelayananModel.php | `pelayanans` | ✅ OK |
| WilayahModel.php | `wilayahs` | ✅ OK |
| AnggaranModel.php | `anggarans` | ✅ OK |
| PelayanModel.php | `pelayans` | ✅ OK |
| ProkerModel.php | `prokers` | ✅ OK |
| ApprovalModel.php | `approvals` | ✅ OK |
| NotificationModel.php | `notifications` | ✅ OK |
| CalendarModel.php | `calendars` | ✅ OK |
| ServiceModel.php | `services` | ✅ OK |
| ProgramModel.php | `programs` | ✅ OK |
| TukarMimbarModel.php | `tukar_mimbars` | ✅ OK |
| KegiatanPelayananModel.php | `kegiatan_pelayanans` | ✅ OK |
| InventarisModel.php | `inventaris` | ✅ OK |
| KategoriInventarisModel.php | `kategori_inventaris` | ✅ OK |
| LokasiModel.php | `lokasis` | ✅ OK |
| JadwalPetugasIbadahModel.php | `jadwal_petugas_ibadahs` | ✅ OK |
| JemaatModel.php | `jemaats` | ✅ OK |
| JenisKegiatanModel.php | `jenis_kegiatans` | ✅ OK |
| KehadiranModel.php | `kehadirans` | ✅ OK |
| KeuanganModel.php | `keuangans` | ✅ OK |
| AnggotaKomisiModel.php | `anggota_komisis` | ✅ OK |
| NotifikasiModel.php | `notifikasis` | ✅ OK |
| PasswordHistoryModel.php | `password_historys` | ✅ OK |
| PasswordResetModel.php | `password_resets` | ✅ OK |
| PemasukanModel.php | `pemasukans` | ✅ OK |
| JenisPemasukanModel.php | `jenis_pemasukans` | ✅ OK |
| PengeluaranModel.php | `pengeluarans` | ✅ OK |

## 3. Temuan Inkonsistensi (Mismatches)
Tabel berikut menunjukkan pemanggilan nama tabel dalam kode yang **TIDAK DITEMUKAN** di database.

🎉 **Tidak ada inkonsistensi yang ditemukan!** Semua nama tabel yang dipanggil di kode sesuai dengan database.

## 4. Penggunaan Tabel Hardcoded yang Valid
Tabel di bawah ini memetakan tabel database aktual yang dipanggil secara langsung menggunakan `->table()` atau `->join()` di luar properti model dasar.

| Nama Tabel | Digunakan Di (File & Tipe Pemanggilan) |
|------------|----------------------------------------|
