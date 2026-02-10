<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

// Proteksi Halaman: Pastikan yang masuk adalah Petugas
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'petugas') {
    header("location:../../admin/dashboard/index.php");
}

$username = $_SESSION['username'];
date_default_timezone_set('Asia/Jakarta');
$tgl_hari_ini = date('Y-m-d');

// --- DATA LOGIC ---
// 1. Hitung total transaksi petugas ini saja (jika ada kolom PetugasID di tabel penjualan)
// Jika tidak ada, kita tampilkan total transaksi toko hari ini.
$query_trx = mysqli_query($connect, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
$data_trx = mysqli_fetch_assoc($query_trx);

// 2. Omset Hari Ini
$query_harian = mysqli_query($connect, "SELECT SUM(TotalHarga) as total_hari FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
$data_harian = mysqli_fetch_assoc($query_harian);
$total_hari = $data_harian['total_hari'] ?? 0;

// 3. Produk Hampir Habis (Untuk diingatkan ke admin)
$stok_low = mysqli_query($connect, "SELECT COUNT(*) as limit_stok FROM produk WHERE Stok < 10");
$d_stok = mysqli_fetch_assoc($stok_low);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Kasir Aristoo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }

        .main-wrapper {
            padding: 32px;
            max-width: 1400px;
        }

        /* Header Section */
        .header-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-text h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .welcome-text p {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0;
        }

        .btn-primary-action {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary-action:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            color: white;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-icon.blue {
            background: #dbeafe;
            color: #3b82f6;
        }

        .stat-icon.green {
            background: #d1fae5;
            color: #10b981;
        }

        .stat-icon.yellow {
            background: #fef3c7;
            color: #f59e0b;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-value small {
            font-size: 0.95rem;
            font-weight: 500;
            color: #64748b;
        }

        /* Content Layout */
        .content-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .table-header {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: #0f172a;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table thead th {
            background: #f8fafc;
            padding: 16px 24px;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        .custom-table tbody td {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .custom-table tbody tr:hover {
            background: #f8fafc;
        }

        .time-badge {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .customer-name {
            font-weight: 600;
            color: #0f172a;
        }

        .price-amount {
            color: #10b981;
            font-weight: 700;
        }

        .btn-action {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-action:hover {
            background: #3b82f6;
            color: white;
        }

        .empty-message {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        /* Sidebar */
        .sidebar-widgets {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .widget-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .widget-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .quick-link {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 16px;
            text-align: center;
            text-decoration: none;
            color: #475569;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .quick-link i {
            font-size: 1.5rem;
        }

        .quick-link span {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .quick-link:hover {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
        }

        .info-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 12px;
            padding: 16px;
        }

        .info-box-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box-text {
            font-size: 0.85rem;
            color: #78350f;
            margin: 0;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .content-layout {
                grid-template-columns: 1fr;
            }

            .quick-links {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-wrapper {
                padding: 20px;
            }

            .header-section {
                padding: 24px;
            }

            .welcome-text h2 {
                font-size: 1.4rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-links {
                grid-template-columns: repeat(2, 1fr);
            }

            .custom-table thead th,
            .custom-table tbody td {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="main-wrapper w-100">
            
            <!-- Header Section -->
            <div class="header-section">
                <div class="welcome-text">
                    <h2>Halo, <?= ucwords($username); ?>Selamat Bertugas!</h2>
                    <p>Semangat melayani pelanggan hari ini • <?= date('d F Y'); ?></p>
                </div>
                <a href="../penjualan/index.php" class="btn-primary-action">
                    <i class="fas fa-plus"></i>
                    Transaksi Baru
                </a>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon blue">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                    <div class="stat-label">Transaksi</div>
                    <div class="stat-value"><?= $data_trx['total']; ?> <small>Nota</small></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon green">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="stat-label">Total Penjualan</div>
                    <div class="stat-value">Rp <?= number_format($total_hari, 0, ',', '.'); ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon yellow">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-label">Perlu Restock</div>
                    <div class="stat-value"><?= $d_stok['limit_stok']; ?> <small>Produk</small></div>
                </div>
            </div>

            <!-- Content Layout -->
            <div class="content-layout">
                
                <!-- Table Card -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>Riwayat Transaksi Hari Ini</h3>
                    </div>
                    <div class="table-wrapper">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>WAKTU</th>
                                    <th>PELANGGAN</th>
                                    <th>TOTAL</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $log = mysqli_query($connect, "SELECT * FROM penjualan 
                                       JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                       WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'
                                       ORDER BY PenjualanID DESC LIMIT 6");
                                
                                if(mysqli_num_rows($log) == 0): ?>
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-message">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>Belum ada transaksi hari ini</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif;
                                while($l = mysqli_fetch_array($log)): ?>
                                <tr>
                                    <td>
                                        <span class="time-badge">
                                            <?= date('H:i', strtotime($l['TanggalPenjualan'])); ?>
                                        </span>
                                    </td>
                                    <td class="customer-name"><?= $l['NamaPelanggan']; ?></td>
                                    <td class="price-amount">Rp <?= number_format($l['TotalHarga']); ?></td>
                                    <td>
                                        <a href="../penjualan/detail.php?id=<?= $l['PenjualanID']; ?>" class="btn-action">
                                            <i class="fas fa-print me-1"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sidebar Widgets -->
                <div class="sidebar-widgets">
                    
                    <!-- Quick Links -->
                    <div class="widget-card">
                        <h4 class="widget-title">Menu Cepat</h4>
                        <div class="quick-links">
                            <a href="../penjualan/index.php" class="quick-link">
                                <i class="fas fa-calculator"></i>
                                <span>Kasir</span>
                            </a>
                            <a href="../produk/index.php" class="quick-link">
                                <i class="fas fa-box"></i>
                                <span>Stok</span>
                            </a>
                            <a href="../laporan/index.php" class="quick-link">
                                <i class="fas fa-chart-bar"></i>
                                <span>Laporan</span>
                            </a>
                            <a href="javascript:void(0)" onclick="confirmLogout()" class="quick-link">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="info-box">
                        <div class="info-box-title">
                            <i class="fas fa-lightbulb"></i>
                            Tips Hari Ini
                        </div>
                        <p class="info-box-text">
                            Pastikan stok barang di rak selalu terisi sebelum jam ramai pengunjung tiba.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Selesaikan Shift?',
                text: "Pastikan semua transaksi hari ini sudah tersimpan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../../auth/logout.php";
                }
            })
        }
    </script>
</body>
</html> 