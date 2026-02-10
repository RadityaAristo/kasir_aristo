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
    <title>Laporan Penjualan - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body { 
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
        }

        .main-content {
            padding: 60px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h3 {
            color: #1f2937;
            font-weight: 600;
            font-size: 1.75rem;
            margin: 0;
        }

        .page-header p {
            color: #6b7280;
            margin: 0.25rem 0 0 0;
            font-size: 0.9375rem;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.9375rem;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }

        .btn-print {
            background: #1f2937;
            color: white;
            border: none;
        }

        .btn-print:hover {
            background: #111827;
            color: white;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.success {
            background: #ecfdf5;
            color: #10b981;
        }

        .stat-icon.primary {
            background: #eff6ff;
            color: #3b82f6;
        }

        .stat-content h6 {
            font-size: 0.8125rem;
            color: #6b7280;
            font-weight: 500;
            margin: 0 0 0.25rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-content .value {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table {
            margin: 0;
        }

        .table thead th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 1.25rem;
            border: none;
        }

        .table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .btn-detail {
            background: white;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-detail:hover {
            background: #6366f1;
            color: white !important;
            border-color: #6366f1;
            transform: translateY(-1px);
        }
        
        .btn-detail:active,
        .btn-detail:focus {
            color: #6b7280;
        }
        
        .btn-detail:hover:active,
        .btn-detail:hover:focus {
            color: white !important;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .main-content { padding: 1rem; }
            .data-card { box-shadow: none; border: 1px solid #e5e7eb; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="main-content flex-grow-1">
            <!-- Header -->
            <div class="page-header d-flex justify-content-between align-items-start no-print">
                <div>
                    <h3><i class="fas fa-chart-line me-2" style="color: #6366f1;"></i>Laporan Penjualan</h3>
                    <p>Pantau performa penjualan toko Anda</p>
                </div>
                <button class="btn btn-print" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>

            <!-- Filter -->
            <div class="filter-card no-print">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="index.php" class="btn btn-light border">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>

            <?php 
            $where = "";
            if($tgl_mulai != '' && $tgl_selesai != '') {
                $where = " WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'";
            }
            $summary = mysqli_query($connect, "SELECT SUM(TotalHarga) as total, COUNT(*) as jml FROM penjualan $where");
            $ds = mysqli_fetch_assoc($summary);
            ?>

            <!-- Statistics -->
            <div class="stats-row no-print">
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h6>Total Omset</h6>
                        <p class="value" style="color: #10b981;">Rp <?= number_format($ds['total'] ?? 0, 0, ',', '.'); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-content">
                        <h6>Total Transaksi</h6>
                        <p class="value" style="color: #3b82f6;"><?= $ds['jml']; ?> Pesanan</p>
                    </div>
                </div>
            </div>

            <!-- Print Header -->
            <div class="text-center py-4 d-none d-print-block">
                <h2 class="fw-bold m-0">LAPORAN PENJUALAN</h2>
                <p class="m-0 text-muted">Periode: <?= ($tgl_mulai ?: 'Semua') ?> s/d <?= ($tgl_selesai ?: 'Sekarang') ?></p>
                <hr class="mx-auto w-75">
            </div>

            <!-- Data Table -->
            <div class="data-card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Waktu Transaksi</th>
                                <th>Pelanggan</th>
                                <th class="text-end">Total</th>
                                <th class="no-print text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $query = mysqli_query($connect, "SELECT * FROM penjualan JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID $where ORDER BY TanggalPenjualan DESC");
                            if(mysqli_num_rows($query) > 0) {
                                while($d = mysqli_fetch_array($query)){
                            ?>
                            <tr>
                                <td class="text-muted"><?= $no++; ?></td>
                                <td>
                                    <div style="font-weight: 500;"><?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?></small>
                                </td>
                                <td style="font-weight: 500;"><?= $d['NamaPelanggan']; ?></td>
                                <td class="text-end" style="font-weight: 600; color: #6366f1;">Rp <?= number_format($d['TotalHarga'], 0, ',', '.'); ?></td>
                                <td class="no-print text-center">
                                    <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="btn-detail">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else {
                            ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p class="mb-0">Tidak ada data transaksi</p>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Print Footer -->
            <div class="d-none d-print-block mt-4 text-end">
                <p>Dicetak pada: <?= date('d/m/Y H:i'); ?></p>
                <br><br>
                <p class="fw-bold">( ____________________ )</p>
                <p>Admin Kasir Aristo</p>
            </div>
        </div>
    </div>
</body>
</html>