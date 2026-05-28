<?php
// Data passed from controller: $anggaranModel data is passed via $data array
// Use $komisi_list, $program_list, $anggaran_list variables from controller

// Set tahun default
$currentYear = date('Y');
$currentMonth = date('m');

// Ambil parameter filter
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;
$komisi_id = isset($_GET['komisi_id']) ? $_GET['komisi_id'] : 'all';

// Jika ketua komisi, hanya bisa melihat anggaran komisinya
if ($userRole == 'ketua_komisi') {
    $userKomisi = $komisiModel->getKomisiByKetua($userId);
    if ($userKomisi) {
        $komisi_id = $userKomisi['id'];
    }
}

// Ambil data untuk dashboard
// 1. Ringkasan anggaran per komisi
$summary = $anggaranModel->getAnggaranSummary($tahun);

// 2. Anggaran yang perlu perhatian
$overdueAnggaran = $anggaranModel->getOverdueAnggaran();
$upcomingDeadline = $anggaranModel->getUpcomingDeadlineAnggaran(30);

// 3. Anggaran menunggu persetujuan (untuk admin/bendahara)
$pendingApproval = [];
if ($userRole == 'admin' || $userRole == 'bendahara') {
    $pendingApproval = $anggaranModel->getAnggaranByStatus('diajukan', $tahun);
}

// 4. Total anggaran semua komisi
$totalAnggaran = 0;
$totalRealisasi = 0;
foreach ($summary as $item) {
    $totalAnggaran += $item['total_disetujui'];
    $totalRealisasi += $item['total_realisasi'];
}
$persentaseRealisasi = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 1) : 0;

// Format currency helper
function formatCurrency($amount) {
    if ($amount == 0) return '-';
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Format persentase
function formatPercentage($value) {
    return round($value, 1) . '%';
}

// Get status badge class
function getStatusBadgeClass($status) {
    $classes = [
        'draft' => 'badge-draft',
        'diajukan' => 'badge-diajukan',
        'disetujui' => 'badge-disetujui',
        'direalisasi' => 'badge-direalisasi',
        'direalisasi_sebagian' => 'badge-direalisasi_sebagian',
        'ditolak' => 'badge-ditolak'
    ];
    return $classes[$status] ?? 'badge-secondary';
}

// Get status text
function getStatusText($status) {
    $texts = [
        'draft' => 'Draft',
        'diajukan' => 'Diajukan',
        'disetujui' => 'Disetujui',
        'direalisasi' => 'Direalisasi',
        'direalisasi_sebagian' => 'Direalisasi Sebagian',
        'ditolak' => 'Ditolak'
    ];
    return $texts[$status] ?? $status;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Anggaran - Gereja <?php echo $_SESSION['gereja_nama']; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --purple-color: #8e44ad;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color), #1a252f);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        
        .dashboard-header {
            background: linear-gradient(rgba(44, 62, 80, 0.9), rgba(44, 62, 80, 0.9)),
                        url('../../assets/images/budget-header.jpg') center/cover;
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
            position: relative;
        }
        
        .dashboard-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            border-radius: 2px;
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            margin-top: -60px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }
        
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
        }
        
        .card-custom:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
            border-bottom: none;
            position: relative;
        }
        
        .card-header-custom::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: rgba(255,255,255,0.3);
        }
        
        .stat-card {
            text-align: center;
            padding: 25px 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: rgba(255,255,255,0.3);
        }
        
        .stat-card.diajukan { 
            background: linear-gradient(135deg, var(--info-color), #3498db);
        }
        
        .stat-card.disetujui { 
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
        }
        
        .stat-card.realisasi { 
            background: linear-gradient(135deg, var(--warning-color), #f1c40f);
        }
        
        .stat-card.total { 
            background: linear-gradient(135deg, var(--primary-color), #34495e);
        }
        
        .stat-card.overall { 
            background: linear-gradient(135deg, var(--purple-color), #9b59b6);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .stat-sub {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .budget-progress {
            margin-top: 15px;
        }
        
        .progress-budget {
            height: 12px;
            border-radius: 10px;
            background-color: rgba(255,255,255,0.2);
            overflow: hidden;
            margin-bottom: 5px;
        }
        
        .progress-budget .progress-bar {
            border-radius: 10px;
            font-weight: bold;
            transition: width 1s ease-in-out;
        }
        
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-draft { background-color: #e9ecef; color: #495057; }
        .badge-diajukan { background-color: #cff4fc; color: #055160; }
        .badge-disetujui { background-color: #d1e7dd; color: #0a3622; }
        .badge-direalisasi { background-color: #d4edda; color: #155724; }
        .badge-direalisasi_sebagian { background-color: #fff3cd; color: #664d03; }
        .badge-ditolak { background-color: #f8d7da; color: #721c24; }
        
        .budget-summary-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid;
            transition: all 0.3s;
        }
        
        .budget-summary-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .komisi-1 { border-left-color: #3498db; }
        .komisi-2 { border-left-color: #2ecc71; }
        .komisi-3 { border-left-color: #e74c3c; }
        .komisi-4 { border-left-color: #f39c12; }
        .komisi-5 { border-left-color: #9b59b6; }
        .komisi-6 { border-left-color: #1abc9c; }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .quick-action-btn {
            background: white;
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            height: 100%;
        }
        
        .quick-action-btn:hover {
            border-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .quick-action-icon {
            font-size: 2.5rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .urgent-item {
            border-left: 4px solid var(--danger-color) !important;
            background-color: #fff5f5 !important;
        }
        
        .warning-item {
            border-left: 4px solid var(--warning-color) !important;
            background-color: #fff9e6 !important;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .btn-custom {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
            color: white;
        }
        
        .btn-new {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
        }
        
        .btn-report {
            background: linear-gradient(135deg, var(--purple-color), #9b59b6);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .komisi-color {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .date-badge {
            background: #e9ecef;
            color: #495057;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
        }
        
        .recent-activity {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--secondary-color);
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 30px 0;
            }
            
            .welcome-card {
                padding: 20px;
                margin-top: -40px;
            }
            
            .stat-number {
                font-size: 1.8rem;
            }
            
            .stat-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="../kehadiran/index.php">
                <div class="bg-white rounded-circle p-1 me-2">
                    <i class="fas fa-coins text-primary"></i>
                </div>
                <div>
                    <div class="fw-bold">Gereja <?php echo $_SESSION['gereja_nama']; ?></div>
                    <small class="opacity-75">Manajemen Anggaran</small>
                </div>
            </a>
            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <?php if (!empty($notifications)): ?>
                    <div class="dropdown me-3 position-relative">
                        <a href="#" class="text-white" data-bs-toggle="dropdown">
                            <i class="fas fa-bell fa-lg"></i>
                            <?php 
                                $totalNotifications = array_sum(array_column($notifications, 'count'));
                                if ($totalNotifications > 0): 
                            ?>
                                <span class="notification-badge"><?php echo min($totalNotifications, 9); ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                            <h6 class="dropdown-header">Notifikasi Anggaran</h6>
                            <?php foreach ($notifications as $notification): ?>
                                <a class="dropdown-item" href="anggaran.php?status=<?php echo $notification['type'] == 'approval' ? 'diajukan' : 'draft'; ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-<?php 
                                                echo $notification['type'] == 'approval' ? 'clock' : 
                                                    ($notification['type'] == 'warning' ? 'exclamation-triangle' : 'bell');
                                            ?> text-<?php echo $notification['type'] == 'approval' ? 'warning' : 
                                                    ($notification['type'] == 'warning' ? 'danger' : 'primary'); ?>"></i>
                                        </div>
                                        <div>
                                            <small class="d-block"><?php echo $notification['message']; ?></small>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="#">
                                <small>Tandai sudah dibaca</small>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- User Menu -->
                <div class="dropdown">
                    <a href="#" class="text-white text-decoration-none dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <div class="bg-white text-dark rounded-circle p-1 me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="me-2 d-none d-md-block">
                            <small class="d-block"><?php echo $_SESSION['user_nama']; ?></small>
                            <small class="opacity-75"><?php echo ucfirst($userRole); ?></small>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="../kehadiran/profile.php">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a></li>
                        <li><a class="dropdown-item" href="../kehadiran/">
                            <i class="fas fa-clipboard-check me-2"></i> Kehadiran
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="anggaran.php">
                            <i class="fas fa-list me-2"></i> Daftar Anggaran
                        </a></li>
                        <li><a class="dropdown-item" href="laporan.php">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a></li>
                        <?php if ($userRole == 'admin' || $userRole == 'bendahara'): ?>
                            <li><a class="dropdown-item" href="approval.php">
                                <i class="fas fa-check-circle me-2"></i> Persetujuan
                            </a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold mb-3">Manajemen Anggaran</h1>
                    <p class="lead mb-0">Kelola anggaran kegiatan gereja dengan efisien dan transparan</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block bg-white text-dark px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Tahun <?php echo $tahun; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="container">
        <div class="welcome-card mb-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Ringkasan Anggaran Tahun <?php echo $tahun; ?>
                    </h4>
                    <p class="text-muted mb-0">
                        Total anggaran disetujui: <?php echo formatCurrency($totalAnggaran); ?> • 
                        Realisasi: <?php echo formatCurrency($totalRealisasi); ?> (<?php echo $persentaseRealisasi; ?>%)
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="anggaran.php?action=tambah" class="btn btn-custom btn-new">
                            <i class="fas fa-plus me-1"></i> Anggaran Baru
                        </a>
                        <a href="laporan.php" class="btn btn-custom btn-report">
                            <i class="fas fa-file-alt me-1"></i> Laporan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card total">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-number"><?php echo formatCurrency($statistics['total_disetujui'] ?? 0); ?></div>
                    <div class="stat-label">Total Anggaran Disetujui</div>
                    <div class="stat-sub"><?php echo $statistics['total_anggaran'] ?? 0; ?> item anggaran</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card realisasi">
                    <div class="stat-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="stat-number"><?php echo formatCurrency($statistics['total_realisasi'] ?? 0); ?></div>
                    <div class="stat-label">Total Realisasi</div>
                    <div class="budget-progress">
                        <div class="progress-budget">
                            <div class="progress-bar bg-white" 
                                 style="width: <?php echo $statistics['avg_realisasi'] ?? 0; ?>%"></div>
                        </div>
                        <div class="stat-sub"><?php echo formatPercentage($statistics['avg_realisasi'] ?? 0); ?> terealisasi</div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card diajukan">
                    <div class="stat-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div class="stat-number"><?php echo $statistics['diajukan'] ?? 0; ?></div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                    <div class="stat-sub"><?php echo formatCurrency($statistics['total_diajukan'] ?? 0); ?> diajukan</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card overall">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-number"><?php echo $statistics['direalisasi'] ?? 0; ?></div>
                    <div class="stat-label">Selesai Direalisasi</div>
                    <div class="stat-sub"><?php echo $statistics['disetujui'] ?? 0; ?> disetujui</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <h5 class="mb-3">Filter Dashboard</h5>
            <form method="GET" action="" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun" onchange="this.form.submit()">
                        <?php foreach ($tahun_list as $year): ?>
                            <option value="<?php echo $year; ?>" <?php echo $tahun == $year ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Komisi</label>
                    <select class="form-select" name="komisi_id" onchange="this.form.submit()" 
                        <?php echo $userRole == 'ketua_komisi' ? 'disabled' : ''; ?>>
                        <option value="all">Semua Komisi</option>
                        <?php foreach ($komisi_list as $komisi): ?>
                            <option value="<?php echo $komisi['id']; ?>" 
                                <?php echo $komisi_id == $komisi['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($komisi['nama_komisi']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="index.php" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i> Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <div class="row">
            <!-- Left Column: Charts & Summary -->
            <div class="col-lg-8">
                <!-- Chart Row -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-4">
                        <div class="card card-custom">
                            <div class="card-header card-header-custom">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    Distribusi Anggaran
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="distributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card card-custom">
                            <div class="card-header card-header-custom">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Realisasi Bulanan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Anggaran per Komisi -->
                <div class="card card-custom mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>
                            Anggaran per Komisi
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($summary)): ?>
                            <div class="row">
                                <?php foreach ($summary as $index => $item): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="budget-summary-card komisi-<?php echo ($index % 6) + 1; ?>">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($item['nama_komisi']); ?></h6>
                                                    <small class="text-muted"><?php echo $item['total_anggaran']; ?> item</small>
                                                </div>
                                                <span class="badge bg-primary">
                                                    <?php echo formatCurrency($item['total_disetujui']); ?>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small>Realisasi: <?php echo formatCurrency($item['total_realisasi']); ?></small>
                                                <small class="text-<?php echo $item['persentase_realisasi'] > 80 ? 'success' : 
                                                    ($item['persentase_realisasi'] > 50 ? 'warning' : 'danger'); ?>">
                                                    <?php echo formatPercentage($item['persentase_realisasi']); ?>
                                                </small>
                                            </div>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar bg-<?php echo $item['persentase_realisasi'] > 80 ? 'success' : 
                                                    ($item['persentase_realisasi'] > 50 ? 'warning' : 'danger'); ?>" 
                                                     style="width: <?php echo $item['persentase_realisasi']; ?>%">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Sisa: <?php echo formatCurrency($item['sisa_anggaran']); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <p>Belum ada data anggaran untuk tahun <?php echo $tahun; ?></p>
                                <a href="anggaran.php?action=tambah" class="btn btn-custom">
                                    <i class="fas fa-plus me-1"></i> Buat Anggaran Pertama
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Actions & Notifications -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="card card-custom mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="quick-action-btn" onclick="location.href='anggaran.php?action=tambah'">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <h6>Anggaran Baru</h6>
                                    <small class="text-muted">Buat anggaran baru</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="quick-action-btn" onclick="location.href='laporan.php'">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-file-export"></i>
                                    </div>
                                    <h6>Buat Laporan</h6>
                                    <small class="text-muted">Generate laporan</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="quick-action-btn" onclick="location.href='anggaran.php?status=diajukan'">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                    <h6>Persetujuan</h6>
                                    <small class="text-muted">
                                        <?php echo $statistics['diajukan'] ?? 0; ?> menunggu
                                    </small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="quick-action-btn" onclick="location.href='import.php'">
                                    <div class="quick-action-icon">
                                        <i class="fas fa-file-import"></i>
                                    </div>
                                    <h6>Import Data</h6>
                                    <small class="text-muted">Import dari Excel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Anggaran Perlu Perhatian -->
                <div class="card card-custom mb-4">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Perlu Perhatian
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty