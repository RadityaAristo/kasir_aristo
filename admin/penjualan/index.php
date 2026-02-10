<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

// Proteksi Halaman
if ($_SESSION['status'] != "login")
    header("location:../../auth/login.php");
if ($_SESSION['role'] != 'admin')
    header("location:../../petugas/dashboard/index.php");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penjualan - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            font-family: 'Inter', sans-serif;
            color: var(--gray-800);
            line-height: 1.6;
        }

        .main-container {
             max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--gray-600);
            font-size: 0.938rem;
        }

        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .filter-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.938rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        /* Buttons */
        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-primary-custom:hover {
            background: var(--primary-light);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary-custom {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            padding: 0.625rem 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-secondary-custom:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            transform: translateY(-2px);
        }

        .btn-dark-custom {
            background: var(--gray-800);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-dark-custom:hover {
            background: var(--gray-700);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Alert */
        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Table */
        .table {
            margin: 0;
        }

        .table thead {
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
        }

        .table thead th {
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.813rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            border: none;
        }

        .table tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background 0.2s;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Number Box */
        .number-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 42px;
            padding: 0 0.75rem;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.938rem;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
            letter-spacing: 0.5px;
        }

        /* Badge */
        .badge-nota {
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 0.5rem 0.875rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid var(--gray-300);
        }

        /* Date & Time */
        .date-text {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.938rem;
            margin-bottom: 0.125rem;
        }

        .time-text {
            color: var(--gray-500);
            font-size: 0.813rem;
        }

        /* Customer */
        .customer-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.938rem;
            margin-bottom: 0.125rem;
        }

        .customer-id {
            color: var(--gray-500);
            font-size: 0.813rem;
        }

        /* Price */
        .price {
            font-weight: 700;
            font-size: 1.063rem;
            color: var(--primary);
        }

        /* Action Buttons */
        .action-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--gray-300);
            background: white;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .action-btn.view:hover {
            background: #eff6ff;
            border-color: var(--primary);
        }

        .action-btn.delete:hover {
            background: #fef2f2;
            border-color: var(--danger);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 3.5rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--gray-500);
            font-size: 0.938rem;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .filter-card,
            .table-card {
                border: 1px solid #dee2e6;
                box-shadow: none;
            }

            .number-box {
                box-shadow: none;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .filter-card {
                padding: 1.25rem;
            }

            .table thead th,
            .table tbody td {
                padding: 1rem;
            }

            .number-box {
                min-width: 36px;
                height: 36px;
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>

        <div class="flex-grow-1">
            <div class="main-container">

                <!-- Header -->
                <div class="page-header no-print">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-receipt me-2" style="color: var(--primary);"></i>Data Penjualan
                        </h1>
                        <p class="page-subtitle">Kelola dan pantau riwayat transaksi toko Anda</p>
                    </div>
                    <?php if (isset($_GET['tgl_mulai'])): ?>
                        <button onclick="window.print()" class="btn-dark-custom">
                            <i class="fas fa-print me-2"></i>Cetak Laporan
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Filter Section -->
                <div class="filter-card no-print">
                    <h6 class="filter-title">
                        <i class="fas fa-filter"></i>
                        Filter Periode Penjualan
                    </h6>
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Mulai Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control"
                                value="<?= $_GET['tgl_mulai'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" class="form-control"
                                value="<?= $_GET['tgl_selesai'] ?? ''; ?>" required>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn-primary-custom flex-grow-1">
                                <i class="fas fa-search me-2"></i>Terapkan Filter
                            </button>
                            <a href="index.php" class="btn-secondary-custom">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Alert Info -->
                <?php
                $tgl_mulai = $_GET['tgl_mulai'] ?? '';
                $tgl_selesai = $_GET['tgl_selesai'] ?? '';

                if ($tgl_mulai != '' && $tgl_selesai != '') {
                    echo "<div class='alert-info no-print'>
                        <i class='fas fa-calendar-check'></i>
                        <span>Laporan Penjualan: " . date('d M Y', strtotime($tgl_mulai)) . " s/d " . date('d M Y', strtotime($tgl_selesai)) . "</span>
                      </div>";

                    $query_str = "SELECT * FROM penjualan 
                              JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                              WHERE TanggalPenjualan BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'
                              ORDER BY PenjualanID DESC";
                } else {
                    $query_str = "SELECT * FROM penjualan 
                              JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                              ORDER BY PenjualanID DESC";
                }
                $sql = mysqli_query($connect, $query_str);
                ?>

                <!-- Table Section -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Pelanggan</th>
                                    <th class="text-end">Total Bayar</th>
                                    <th class="text-center no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($sql) == 0) {
                                    echo "<tr><td colspan='5' class='p-0'>
                                        <div class='empty-state'>
                                            <div class='empty-icon'><i class='fas fa-inbox'></i></div>
                                            <div class='empty-title'>Data Tidak Ditemukan</div>
                                            <div class='empty-text'>Coba sesuaikan tanggal filter Anda</div>
                                        </div>
                                      </td></tr>";
                                }
                                while ($d = mysqli_fetch_array($sql)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="number-box"><?= $d['PenjualanID']; ?></span>
                                        </td>
                                        <td>
                                            <div class="date-text"><?= date('d M Y', strtotime($d['TanggalPenjualan'])); ?>
                                            </div>
                                            <div class="time-text"><?= date('H:i', strtotime($d['TanggalPenjualan'])); ?>
                                                WIB</div>
                                        </td>
                                        <td>
                                            <div class="customer-name"><?= $d['NamaPelanggan']; ?></div>
                                            <div class="customer-id">ID: <?= $d['PelangganID']; ?></div>
                                        </td>
                                        <td class="text-end">
                                            <span class="price">Rp
                                                <?= number_format($d['TotalHarga'], 0, ',', '.'); ?></span>
                                        </td>
                                        <td class="text-center no-print">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="detail.php?id=<?= $d['PenjualanID']; ?>" class="action-btn view"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye" style="color: var(--primary);"></i>
                                                </a>
                                                <a href="hapus.php?id=<?= $d['PenjualanID']; ?>" class="action-btn delete"
                                                    onclick="return confirm('Yakin ingin menghapus transaksi ini?')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt" style="color: var(--danger);"></i>
                                                </a>
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
                    <p style="font-size: 0.875rem; color: var(--gray-600);">
                        Dicetak pada: <?= date('d/m/Y H:i'); ?> WIB
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>