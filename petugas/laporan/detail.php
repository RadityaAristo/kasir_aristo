<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($connect, "SELECT * FROM penjualan 
           JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
           WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan <?= $id; ?> - Kasir Aristoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body { 
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
            padding: 2rem 1rem;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .invoice-header {
            padding: 2rem;
            border-bottom: 3px solid #6366f1;
        }

        .invoice-header h2 {
            font-size: 2rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .invoice-id {
            color: #6366f1;
            font-size: 1rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #6366f1;
            border: none;
        }

        .btn-primary:hover {
            background: #5558e3;
            transform: translateY(-1px);
        }

        .btn-light {
            background: #f3f4f6;
            color: #374151;
            border: none;
        }

        .btn-light:hover {
            background: #e5e7eb;
        }

        .invoice-body {
            padding: 2rem;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-label {
            font-size: 0.8125rem;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .info-sub {
            color: #6b7280;
            font-size: 0.9375rem;
        }

        .table {
            margin-bottom: 2rem;
        }

        .table thead th {
            background: #f9fafb;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            padding: 1rem;
            border: none;
            letter-spacing: 0.3px;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border: none;
        }

        .product-name {
            font-weight: 500;
            color: #1f2937;
        }

        .qty-box {
            background: #f3f4f6;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            color: #374151;
            display: inline-block;
        }

        .summary-section {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.625rem 0;
        }

        .summary-row .label {
            color: #6b7280;
            font-weight: 500;
        }

        .summary-row .value {
            font-weight: 600;
            color: #1f2937;
        }

        .summary-total {
            border-top: 2px solid #e5e7eb;
            margin-top: 0.75rem;
            padding-top: 1rem;
        }

        .summary-total .label {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }

        .summary-total .value {
            font-size: 1.625rem;
            font-weight: 600;
            color: #10b981;
        }

        .print-footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px dashed #d1d5db;
        }

        .thank-note {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            margin-bottom: 2rem;
        }

        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3rem;
            text-align: center;
        }

        .signature-label {
            color: #9ca3af;
            font-size: 0.875rem;
            margin-bottom: 3rem;
        }

        .signature-name {
            font-weight: 600;
            color: #1f2937;
        }

        @media print {
            .no-print { display: none !important; }
            body { 
                background: white;
                padding: 0;
            }
            .invoice-wrapper {
                box-shadow: none;
                max-width: 100%;
            }
            .print-footer {
                display: block !important;
            }
        }

        @media (max-width: 768px) {
            .info-section {
                flex-direction: column;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="no-print">
            <?php include '../../template/sidebar.php'; ?>
        </div>
        
        <div class="invoice-wrapper flex-grow-1">
            <!-- Header -->
            <div class="invoice-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2>Tangihan</h2>
                        <p class="invoice-id mb-0">No : <?= $id; ?></p>
                    </div>
                    <div class="no-print d-flex gap-2">
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print me-2"></i>Cetak
                        </button>
                        <a href="index.php" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="invoice-body">
                <!-- Info Section -->
                <div class="info-section">
                    <div>
                        <div class="info-label">Pelanggan</div>
                        <div class="info-value"><?= $data['NamaPelanggan']; ?></div>
                        <div class="info-sub"><?= $data['Alamat']; ?></div>
                        <div class="info-sub"><?= $data['NomorTelepon']; ?></div>
                    </div>
                    <div class="text-end">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value"><?= date('d F Y', strtotime($data['TanggalPenjualan'])); ?></div>
                        <div class="info-sub"><?= date('H:i', strtotime($data['TanggalPenjualan'])); ?> WIB</div>
                    </div>
                </div>

                <!-- Products Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
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
                            <td class="product-name"><?= $d['NamaProduk']; ?></td>
                            <td class="text-center" style="color: #6b7280;">
                                Rp <?= number_format($d['Harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <span class="qty-box"><?= $d['JumlahProduk']; ?></span>
                            </td>
                            <td class="text-end" style="font-weight: 600;">
                                Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <!-- Summary -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="summary-section">
                            <div class="summary-row">
                                <span class="label">Total Belanja</span>
                                <span class="value">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="summary-row summary-total">
                                <span class="label">TOTAL BAYAR</span>
                                <span class="value">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print Footer -->
                <div class="print-footer d-none d-print-block">
                    <p class="thank-note">"Terima kasih telah berbelanja di Aristo Mart"</p>
                    <div class="signatures">
                        <div>
                            <div class="signature-label">Pelanggan</div>
                            <div class="signature-name">( <?= $data['NamaPelanggan']; ?> )</div>
                        </div>
                        <div>
                            <div class="signature-label">Kasir</div>
                            <div class="signature-name">( <?= $_SESSION['username'] ?? 'Admin'; ?> )</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>