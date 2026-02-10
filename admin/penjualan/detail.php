<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

// Proteksi: Hanya Admin yang boleh masuk
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");

$id = $_GET['id'];

// Ambil data penjualan & pelanggan
$query = mysqli_query($connect, "SELECT * FROM penjualan 
          JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
          WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// Jika ID tidak ditemukan, kembalikan ke index
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi <?= $id; ?> - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        :root {
            /* Elegant Color Palette */
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
            --secondary: #8b5cf6;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            
            /* Neutral Palette */
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            /* Backgrounds */
            --bg-main: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            
            /* Text */
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            
            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            min-height: 100vh;
            color: var(--text-primary); 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            letter-spacing: -0.01em;
        }

        /* Subtle Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(37, 99, 235, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* Back Button */
        .back-nav {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            background: var(--bg-card);
            border: 1px solid var(--gray-200);
        }

        .back-nav:hover {
            color: var(--primary);
            background: var(--gray-50);
            transform: translateX(-4px);
        }

        /* Transaction Badge */
        .transaction-badge {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(37, 99, 235, 0.05));
            color: var(--primary-dark);
            padding: 0.65rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border: 1px solid rgba(37, 99, 235, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Invoice Card */
        .invoice-card {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-200);
            animation: fadeInUp 0.5s ease-out;
            position: relative;
        }

        /* Accent Bar */
        .accent-bar {
            height: 8px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: relative;
        }

        .accent-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Invoice Header */
        .invoice-header {
            padding: 3rem 3rem 2rem 3rem;
            border-bottom: 2px dashed var(--gray-200);
            background: linear-gradient(to bottom, var(--gray-50), transparent);
        }

        .company-name {
            font-family: 'Poppins', sans-serif;
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }

        .invoice-subtitle {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .info-item {
            background: var(--bg-card);
            padding: 1.25rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
        }

        .info-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
        }

        /* Table Styling */
        .invoice-table-container {
            padding: 2rem 3rem;
        }

        .invoice-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .invoice-table thead th {
            background: var(--gray-50);
            padding: 1rem 1.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--gray-200);
        }

        .invoice-table thead th:first-child {
            border-radius: var(--radius-sm) 0 0 0;
        }

        .invoice-table thead th:last-child {
            border-radius: 0 var(--radius-sm) 0 0;
        }

        .invoice-table tbody tr {
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.2s ease;
        }

        .invoice-table tbody tr:hover {
            background: var(--gray-50);
        }

        .invoice-table tbody td {
            padding: 1.25rem;
        }

        .product-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .product-id {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Total Section */
        .total-section {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(139, 92, 246, 0.05));
            border-radius: var(--radius-md);
            margin: 0 3rem 2rem 3rem;
            overflow: hidden;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.75rem 2rem;
        }

        .total-label {
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
        }

        .total-amount {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.02em;
        }

        /* Footer Actions */
        .invoice-footer {
            padding: 0 3rem 3rem 3rem;
        }

        .info-box {
            background: linear-gradient(135deg, var(--gray-50), var(--bg-card));
            padding: 1.5rem;
            border-radius: var(--radius-md);
            border-left: 4px solid var(--primary);
            border: 1px solid var(--gray-200);
        }

        .info-box-text {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin: 0;
            display: flex;
            align-items: start;
            gap: 0.75rem;
        }

        .info-box-icon {
            color: var(--primary);
            margin-top: 0.15rem;
        }

        /* Print Button */
        .btn-print {
            background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
            color: white;
            border: none;
            padding: 0.85rem 2rem;
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            color: white;
        }

        /* Print Timestamp */
        .print-timestamp {
            text-align: center;
            padding: 2rem 3rem;
            border-top: 1px solid var(--gray-200);
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Print Styles */
        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important;
                font-size: 12px;
            }
            body::before { display: none; }
            .invoice-card { 
                border: 1px solid #ddd;
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
                margin: 0;
            }
            .invoice-header {
                background: white;
                padding: 2rem;
            }
            .invoice-table-container {
                padding: 1rem 2rem;
            }
            .total-section {
                margin: 0 2rem 1rem 2rem;
            }
            .invoice-footer {
                padding: 0 2rem 2rem 2rem;
            }
            .print-timestamp {
                display: block !important;
            }
            .info-item {
                border: 1px solid #ddd;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .invoice-header {
                padding: 2rem 1.5rem 1.5rem 1.5rem;
            }

            .company-name {
                font-size: 1.75rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .invoice-table-container {
                padding: 1.5rem;
            }

            .total-section {
                margin: 0 1.5rem 1.5rem 1.5rem;
            }

            .total-amount {
                font-size: 1.5rem;
            }

            .invoice-footer {
                padding: 0 1.5rem 1.5rem 1.5rem;
            }

            .invoice-table {
                font-size: 0.85rem;
            }

            .invoice-table thead th,
            .invoice-table tbody td {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div class="no-print">
        <?php include '../../template/sidebar.php'; ?>
    </div>

    <div class="container-fluid p-4 p-lg-5" style="position: relative; z-index: 1;">
        
        <!-- Navigation -->
        <div class="row mb-4 no-print">
            <div class="col-lg-10 mx-auto">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <a href="index.php" class="back-nav">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Daftar Penjuakan</span>
                    </a>
                    <div class="transaction-badge">
                        <i class="fas fa-receipt"></i>
                        <span>ID TRANSAKSI: <?= $id; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="col-lg-10 mx-auto">
            <div class="invoice-card">
                
                <!-- Accent Bar -->
                <div class="accent-bar"></div>
                
                <!-- Header -->
                <div class="invoice-header">
                    <div class="text-center">
                        <h1 class="company-name">KASIR ARISTO</h1>
                        <p class="invoice-subtitle">Bukti Transaksi Resmi</p>
                    </div>

                    <!-- Info Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-user me-1"></i> Pelanggan
                            </div>
                            <div class="info-value text-uppercase">
                                <?= $data['NamaPelanggan']; ?>
                            </div>
                            <div class="product-id">ID: <?= $data['PelangganID']; ?></div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar me-1"></i> Tanggal Transaksi
                            </div>
                            <div class="info-value">
                                <?= date('d F Y', strtotime($data['TanggalPenjualan'])); ?>
                            </div>
                            <div class="product-id"><?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag me-1"></i> Nomor Nota
                            </div>
                            <div class="info-value">
                                <?= str_pad($id, 2, '0', STR_PAD_LEFT); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="invoice-table-container">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center">Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $detail = mysqli_query($connect, "SELECT * FROM detailpenjualan 
                                      JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                      WHERE PenjualanID = '$id'");
                            $no = 1;
                            while($d = mysqli_fetch_array($detail)){
                            ?>
                            <tr>
                                <td>
                                    <div class="product-name"><?= $no++; ?>. <?= $d['NamaProduk']; ?></div>
                                </td>
                                <td class="text-center" style="color: var(--text-secondary);">
                                    Rp <?= number_format($d['Harga'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-center" style="font-weight: 700;">
                                    <?= $d['JumlahProduk']; ?> <span style="font-size: 0.8rem; color: var(--text-muted);">pcs</span>
                                </td>
                                <td class="text-end" style="font-weight: 700; color: var(--text-primary);">
                                    Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Total Section -->
                <div class="total-section">
                    <div class="total-row">
                        <div class="total-label">Total Pembayaran</div>
                        <div class="total-amount">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="invoice-footer">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-8">
                            <div class="info-box">
                                <p class="info-box-text">
                                    <i class="fas fa-info-circle info-box-icon"></i>
                                    <span>Simpan nota ini sebagai bukti transaksi yang sah. Semua data tercatat otomatis dalam sistem inventaris dan dapat digunakan untuk keperluan retur atau layanan purna jual.</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end no-print">
                            <button onclick="window.print()" class="btn-print">
                                <i class="fas fa-print"></i>
                                <span>Cetak Nota</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Print Timestamp -->
                <div class="d-none d-print-block print-timestamp">
                    <p class="mb-0">Dokumen ini dicetak secara otomatis oleh Sistem Kasir Pro</p>
                    <p class="mb-0">Waktu Cetak: <?= date('d F Y, H:i:s'); ?> WIB</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Add animation to table rows on load
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.invoice-table tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(10px)';
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});

// Enhanced print with pre-print processing
document.querySelector('.btn-print')?.addEventListener('click', function() {
    // Add any pre-print processing here if needed
    setTimeout(() => {
        window.print();
    }, 100);
});
</script>

</body>
</html>