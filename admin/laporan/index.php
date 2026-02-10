<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Logika Filter Tanggal
$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Kasir Aristoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --success: #059669;
            --bg-main: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .page-header {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Card */
        .card-simple {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        /* Filter */
        .filter-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 0.625rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
            color: white;
        }

        .btn-outline {
            background: white;
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--bg-main);
            color: var(--text-primary);
        }

        .btn-print {
            background: var(--text-primary);
            color: white;
        }

        .btn-print:hover {
            background: #334155;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .stat-icon.green {
            background: rgba(5, 150, 105, 0.1);
            color: var(--success);
        }

        .stat-icon.blue {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        /* Table */
        .table-card {
            margin-bottom: 2rem;
        }

        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin: 0;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-secondary);
            border: none;
            background: var(--bg-main);
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: var(--bg-main);
        }

        /* Table Elements */
        .badge-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 0.75rem;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .date-cell {
            min-width: 150px;
        }

        .date-main {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9375rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .date-time {
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .customer-cell {
            min-width: 200px;
        }

        .customer-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9375rem;
        }

        .price-cell {
            min-width: 180px;
        }

        .price-text {
            font-weight: 700;
            color: var(--success);
            font-size: 1.0625rem;
        }

        .btn-detail {
            background: var(--bg-main);
            color: var(--text-primary);
            border: 1px solid var(--border);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            white-space: nowrap;
        }

        .btn-detail:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
        }

        /* Print */
        .print-header {
            display: none;
            text-align: center;
            padding: 2rem 0;
            border-bottom: 2px solid var(--text-primary);
            margin-bottom: 2rem;
        }

        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none !important;
            }

            .print-header {
                display: block;
            }

            .card-simple {
                box-shadow: none;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div style="flex: 1;">
            <div class="main-container">
                
                <!-- Header -->
                <div class="page-header no-print">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-chart-line me-2"></i>
                            Laporan Penjualan
                        </h1>
                        <p class="page-subtitle">Analisis performa penjualan dan transaksi</p>
                    </div>
                    <button class="btn btn-print" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>
                        Cetak
                    </button>
                </div>

                <!-- Filter -->
                <div class="no-print">
                    <div class="card-simple filter-card">
                        <h3 class="filter-title">Filter Periode</h3>
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Tanggal Selesai</label>
                                    <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary flex-fill">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <a href="index.php" class="btn btn-outline">
                                            <i class="fas fa-redo"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stats -->
                <?php 
                $where = "";
                if($tgl_mulai != '' && $tgl_selesai != '') {
                    $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
                }
                $summary = mysqli_query($connect, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
                $ds = mysqli_fetch_assoc($summary);
                ?>
                
                <div class="stats-grid no-print">
                    <div class="card-simple stat-card">
                        <div class="stat-icon green">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-label">Total Omset</div>
                        <div class="stat-value">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?></div>
                    </div>

                    <div class="card-simple stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-label">Total Transaksi</div>
                        <div class="stat-value"><?= number_format($ds['jml'] ?? 0, 0, ',', '.'); ?></div>
                    </div>
                </div>

                <!-- Print Header -->
                <div class="print-header">
                    <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">LAPORAN PENJUALAN</h1>
                    <p style="font-size: 1.125rem; color: var(--text-secondary);">Kasir Aristo System</p>
                    <?php if($tgl_mulai != '' && $tgl_selesai != ''): ?>
                        <p style="margin-top: 1rem;"><strong>Periode:</strong> <?= date('d M Y', strtotime($tgl_mulai)) ?> - <?= date('d M Y', strtotime($tgl_selesai)) ?></p>
                    <?php else: ?>
                        <p style="margin-top: 1rem;"><strong>Periode:</strong> Semua Waktu</p>
                    <?php endif; ?>
                </div>

                <!-- Table -->
                <div class="card-simple table-card">
                    <div class="table-header">
                        <h3 class="table-title">Daftar Transaksi</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">No</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th class="text-end">Total</th>
                                    <th class="no-print text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($connect, "SELECT * FROM penjualan 
                                         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                         $where ORDER BY TanggalPenjualan DESC");
                                
                                if(mysqli_num_rows($query) == 0) {
                                    echo '<tr><td colspan="5" class="p-0">';
                                    echo '<div class="empty-state">';
                                    echo '<i class="fas fa-inbox"></i>';
                                    echo '<h5>Tidak Ada Data</h5>';
                                    echo '<p>Belum ada transaksi untuk periode ini</p>';
                                    echo '</div>';
                                    echo '</td></tr>';
                                } else {
                                    while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge-number"><?= str_pad($no++, 1, "0", STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td class="date-cell">
                                        <span class="date-main"><?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?></span>
                                        <span class="date-time">
                                            <i class="far fa-clock"></i> <?= date('H:i', strtotime($d['TanggalPenjualan'])); ?> WIB
                                        </span>
                                    </td>
                                    <td class="customer-cell">
                                        <span class="customer-name"><?= htmlspecialchars($d['NamaPelanggan']); ?></span>
                                    </td>
                                    <td class="text-end price-cell">
                                        <span class="price-text">Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?></span>
                                    </td>
                                    <td class="no-print text-center">
                                        <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn-detail">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>