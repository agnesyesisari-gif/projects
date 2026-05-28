<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .status-badge {
            font-size: 0.8em;
        }
        .kegiatan-card {
            transition: transform 0.2s;
            border-left: 4px solid;
        }
        .kegiatan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .jenis-ibadah { border-left-color: #3498db; }
        .jenis-program { border-left-color: #2ecc71; }
        .jenis-lainnya { border-left-color: #f39c12; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><?= $title ?></h4>
                        <div>
                            <a href="<?= site_url('kegiatan/kalender') ?>" class="btn btn-info me-2">
                                <i class="fas fa-calendar-alt me-2"></i>Kalender
                            </a>
                            <a href="<?= site_url('kegiatan/create') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah Kegiatan
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Alert Messages -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Quick Stats -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4><?= $total_kegiatan ?></h4>
                                                <p class="mb-0">Total Kegiatan</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-calendar fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4><?= count(array_filter($kegiatan, function($k) { return $k['status_kegiatan'] === 'Berlangsung'; })) ?></h4>
                                                <p class="mb-0">Sedang Berlangsung</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-play-circle fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4><?= count(array_filter($kegiatan, function($k) { return $k['jenis_kegiatan'] === 'Ibadah'; })) ?></h4>
                                                <p class="mb-0">Jadwal Ibadah</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-church fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h4><?= count(array_filter($kegiatan, function($k) { return $k['jenis_kegiatan'] === 'Program Kerja'; })) ?></h4>
                                                <p class="mb-0">Program Kerja</p>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-tasks fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="get" class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Jenis Kegiatan</label>
                                        <select name="jenis" class="form-select">
                                            <option value="">Semua Jenis</option>
                                            <option value="Ibadah" <?= $jenis_filter == 'Ibadah' ? 'selected' : '' ?>>Ibadah</option>
                                            <option value="Program Kerja" <?= $jenis_filter == 'Program Kerja' ? 'selected' : '' ?>>Program Kerja</option>
                                            <option value="Lainnya" <?= $jenis_filter == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Kategori</label>
                                        <select name="kategori" class="form-select">
                                            <option value="">Semua Kategori</option>
                                            <option value="Ibadah Minggu" <?= $kategori_filter == 'Ibadah Minggu' ? 'selected' : '' ?>>Ibadah Minggu</option>
                                            <option value="Ibadah Keluarga" <?= $kategori_filter == 'Ibadah Keluarga' ? 'selected' : '' ?>>Ibadah Keluarga</option>
                                            <option value="Ibadah Pemuda" <?= $kategori_filter == 'Ibadah Pemuda' ? 'selected' : '' ?>>Ibadah Pemuda</option>
                                            <option value="Ibadah Doa" <?= $kategori_filter == 'Ibadah Doa' ? 'selected' : '' ?>>Ibadah Doa</option>
                                            <option value="Evangelisasi" <?= $kategori_filter == 'Evangelisasi' ? 'selected' : '' ?>>Evangelisasi</option>
                                            <option value="Pelatihan" <?= $kategori_filter == 'Pelatihan' ? 'selected' : '' ?>>Pelatihan</option>
                                            <option value="Sosial" <?= $kategori_filter == 'Sosial' ? 'selected' : '' ?>>Sosial</option>
                                            <option value="Administrasi" <?= $kategori_filter == 'Administrasi' ? 'selected' : '' ?>>Administrasi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="Terencana" <?= $status_filter == 'Terencana' ? 'selected' : '' ?>>Terencana</option>
                                            <option value="Berlangsung" <?= $status_filter == 'Berlangsung' ? 'selected' : '' ?>>Berlangsung</option>
                                            <option value="Selesai" <?= $status_filter == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Kegiatan List -->
                        <div class="row">
                            <?php if (empty($kegiatan)): ?>
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle me-2"></i>Tidak ada data kegiatan
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($kegiatan as $item): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 kegiatan-card 
                                            <?= $item['jenis_kegiatan'] == 'Ibadah' ? 'jenis-ibadah' : '' ?>
                                            <?= $item['jenis_kegiatan'] == 'Program Kerja' ? 'jenis-program' : '' ?>
                                            <?= $item['jenis_kegiatan'] == 'Lainnya' ? 'jenis-lainnya' : '' ?>">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <span class="badge 
                                                    <?= $item['jenis_kegiatan'] == 'Ibadah' ? 'bg-primary' : '' ?>
                                                    <?= $item['jenis_kegiatan'] == 'Program Kerja' ? 'bg-success' : '' ?>
                                                    <?= $item['jenis_kegiatan'] == 'Lainnya' ? 'bg-warning' : '' ?>">
                                                    <?= $item['jenis_kegiatan'] ?>
                                                </span>
                                                <span class="badge 
                                                    <?= $item['status_kegiatan'] == 'Terencana' ? 'bg-secondary' : '' ?>
                                                    <?= $item['status_kegiatan'] == 'Berlangsung' ? 'bg-success' : '' ?>
                                                    <?= $item['status_kegiatan'] == 'Selesai' ? 'bg-info' : '' ?>
                                                    <?= $item['status_kegiatan'] == 'Dibatalkan' ? 'bg-danger' : '' ?> status-badge">
                                                    <?= $item['status_kegiatan'] ?>
                                                </span>
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title"><?= esc($item['nama_kegiatan']) ?></h6>
                                                <p class="card-text text-muted small">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?= date('d M Y', strtotime($item['tanggal_mulai'])) ?>
                                                    <?= $item['waktu_mulai'] ? ' - ' . $item['waktu_mulai'] : '' ?>
                                                </p>
                                                <p class="card-text small">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <?= esc($item['lokasi']) ?>
                                                </p>
                                                <p class="card-text small text-truncate">
                                                    <?= esc(character_limiter($item['deskripsi'], 80)) ?>
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i>
                                                        <?= esc($item['penanggung_jawab']) ?>
                                                    </small>
                                                    <div class="btn-group">
                                                        <a href="<?= site_url('kegiatan/view/' . $item['id_kegiatan']) ?>" 
                                                           class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('kegiatan/edit/' . $item['id_kegiatan']) ?>" 
                                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Hapus"
                                                                onclick="confirmDelete(<?= $item['id_kegiatan'] ?>, '<?= esc($item['nama_kegiatan']) ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kegiatan <strong id="deleteName"></strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="deleteButton" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id, name) {
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteButton').href = '<?= site_url('kegiatan/delete/') ?>' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>