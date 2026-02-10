<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($connect, "SELECT * FROM penjualan 
         JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
         WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

// Jika ID tidak ditemukan
if (!$data) {
    header("location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan <?= $id; ?> - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: white;
            color: #495057;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #dee2e6;
            margin-bottom: 24px;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background-color: #f8f9fa;
            color: #212529;
            border-color: #adb5bd;
        }

        /* Invoice Card */
        .invoice-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        /* Header */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 32px 30px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .company-name {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .company-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .invoice-number {
            text-align: right;
        }

        .invoice-number h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }

        .invoice-label {
            font-size: 12px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .info-box .main-text {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-box .sub-text {
            font-size: 13px;
            opacity: 0.85;
        }

        /* Items Section */
        .items-section {
            padding: 30px;
        }

        .items-table {
            width: 100%;
            margin-bottom: 24px;
        }

        .items-table thead th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            padding-bottom: 12px;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table tbody td {
            padding: 16px 8px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .product-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .product-price,
        .product-qty,
        .product-total {
            color: #374151;
            font-size: 14px;
        }

        .product-qty {
            text-align: center;
            background-color: #f3f4f6;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            font-weight: 500;
        }

        .product-total {
            text-align: right;
            font-weight: 600;
        }

        /* Summary */
        .summary-box {
            background-color: #f9fafb;
            border-radius: 10px;
            padding: 24px;
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }

        .summary-label {
            color: #6b7280;
            font-weight: 500;
        }

        .summary-value {
            color: #1f2937;
            font-weight: 600;
        }

        .summary-divider {
            height: 1px;
            background-color: #d1d5db;
            margin: 12px 0;
        }

        .total-row {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 18px 20px;
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            color: white;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .total-amount {
            color: white;
            font-size: 28px;
            font-weight: 700;
        }

        /* Footer */
        .invoice-footer {
            padding: 0 30px 30px;
        }

        .thank-you-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .thank-you-box h3 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .thank-you-box p {
            font-size: 13px;
            opacity: 0.9;
            margin: 0;
        }

        .footer-text {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-container {
                padding: 20px 15px;
            }

            .invoice-header {
                padding: 24px 20px;
            }

            .items-section {
                padding: 20px;
            }

            .invoice-footer {
                padding: 0 20px 20px;
            }

            .header-row {
                flex-direction: column;
                gap: 20px;
            }

            .invoice-number {
                text-align: left;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .items-table thead {
                display: none;
            }

            .items-table tbody tr {
                display: block;
                margin-bottom: 20px;
                border-bottom: 2px solid #e5e7eb;
                padding-bottom: 12px;
            }

            .items-table tbody td {
                display: block;
                text-align: left !important;
                padding: 6px 0;
                border: none;
            }

            .product-qty {
                display: inline-block;
            }

            .total-amount {
                font-size: 24px;
            }
        }

        @media print {
            .back-btn {
                display: none;
            }
            body {
                background: white;
            }
            .invoice-card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div style="flex: 1;">
            <div class="page-container">
                
                <a href="index.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>

                <div class="invoice-card">
                    
                    <!-- Header -->
                    <div class="invoice-header">
                        <div class="header-row">
                            <div>
                                <h1 class="company-name">Kasir Aristo</h1>
                                <p class="company-subtitle">Invoice Penjualan</p>
                            </div>
                            <div class="invoice-number">
                                <p class="invoice-label">Invoice</p>    
                                <h2><?= str_pad($id, 1, "0", STR_PAD_LEFT); ?></h2>
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="info-box">
                                <h4><i class="fas fa-user"></i> Pelanggan</h4>
                                <div class="main-text"><?= htmlspecialchars($data['NamaPelanggan']); ?></div>
                                <div class="sub-text">ID: <?= $data['PelangganID']; ?></div>
                            </div>
                            <div class="info-box">
                                <h4><i class="fas fa-calendar"></i> Tanggal</h4>
                                <div class="main-text"><?= date('d M Y', strtotime($data['TanggalPenjualan'])); ?></div>
                                <div class="sub-text"><?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</div>
                            </div>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="items-section">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th style="text-align: left;">Produk</th>
                                    <th style="text-align: left;">Harga</th>
                                    <th style="text-align: center;">Qty</th>
                                    <th style="text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $detail = mysqli_query($connect, "SELECT * FROM detailpenjualan 
                                          JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                          WHERE PenjualanID = '$id'");
                                while($d = mysqli_fetch_array($detail)){
                                ?>
                                <tr>
                                    <td>
                                        <div class="product-name"><?= htmlspecialchars($d['NamaProduk']); ?></div>
                                    </td>
                                    <td class="product-price">Rp <?= number_format($d['Harga'], 0, ',', '.'); ?></td>
                                    <td style="text-align: center;">
                                        <span class="product-qty">×<?= $d['JumlahProduk']; ?></span>
                                    </td>
                                    <td class="product-total">Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Summary -->
                        <div class="summary-box">
                            <div class="summary-row">
                                <span class="summary-label">Subtotal</span>
                                <span class="summary-value">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Pajak (0%)</span>
                                <span class="summary-value">Rp 0</span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Diskon</span>
                                <span class="summary-value">Rp 0</span>
                            </div>
                            <div class="summary-divider"></div>
                            <div class="total-row">
                                <span class="total-label">Total Bayar</span>
                                <span class="total-amount">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="invoice-footer">
                        <div class="thank-you-box">
                            <h3>Terima Kasih!</h3>
                            <p>Transaksi berhasil. Simpan invoice ini sebagai bukti pembayaran.</p>
                        </div>
                        <div class="footer-text">
                            Untuk informasi lebih lanjut hubungi customer service kami<br>
                            atau kunjungi website resmi Kasir Aristo
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>