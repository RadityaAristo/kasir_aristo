<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$query = mysqli_query($connect, "SELECT * FROM penjualan 
        JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
        WHERE PenjualanID = '$id'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk <?= $id; ?> - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
            padding: 40px 20px;
        }
        
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .receipt-header {
            text-align: center;
            padding: 30px 25px 25px;
            border-bottom: 2px solid #000;
        }
        
        .receipt-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px;
            letter-spacing: 0.5px;
        }
        
        .receipt-header p {
            font-size: 13px;
            color: #666;
            margin: 2px 0;
        }
        
        .receipt-body {
            padding: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }
        
        .info-row .label {
            color: #666;
        }
        
        .info-row .value {
            font-weight: 600;
            color: #000;
        }
        
        .customer-name {
            font-size: 15px;
            font-weight: 700;
            margin: 15px 0 20px;
            padding: 12px;
            background: #f8f8f8;
            border-radius: 6px;
            text-align: center;
            text-transform: uppercase;
        }
        
        .divider {
            border-top: 1px dashed #ddd;
            margin: 20px 0;
        }
        
        .items {
            margin: 20px 0;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-size: 14px;
            font-weight: 500;
            color: #000;
        }
        
        .item-qty {
            font-size: 13px;
            color: #999;
            margin-top: 2px;
        }
        
        .item-price {
            font-size: 14px;
            font-weight: 600;
            color: #000;
            text-align: right;
        }
        
        .total-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #000;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .total-label {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .total-amount {
            font-size: 22px;
            font-weight: 700;
        }
        
        .footer {
            text-align: center;
            padding: 25px;
            border-top: 1px dashed #ddd;
        }
        
        .footer p {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .qr-code {
            background: #f8f8f8;
            padding: 12px;
            border-radius: 8px;
            display: inline-block;
        }
        
        .actions {
            padding: 0 25px 25px;
        }
        
        .btn-action {
            width: 100%;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-print {
            background: #000;
            color: white;
            margin-bottom: 10px;
        }
        
        .btn-print:hover {
            background: #333;
        }
        
        .btn-new {
            background: white;
            color: #000;
            border: 2px solid #000;
            text-decoration: none;
            display: block;
            text-align: center;
            line-height: 1;
        }
        
        .btn-new:hover {
            background: #f8f8f8;
            color: #000;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt {
                box-shadow: none;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="receipt">
        <div class="receipt-header">
            <h2>KASIR ARISTO</h2>
            <p>Pekanbaru, Riau</p>
            <p>0852-6323-9845</p>
        </div>

        <div class="receipt-body">
            <div class="info-row">
                <span class="label">No. Nota</span>
                <span class="value"><?= $id; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal</span>
                <span class="value"><?= date('d/m/Y H:i', strtotime($data['TanggalPenjualan'])); ?></span>
            </div>

            <div class="customer-name">
                <?= $data['NamaPelanggan']; ?>
            </div>

            <div class="divider"></div>

            <div class="items">
                <?php 
                $detail = mysqli_query($connect, "SELECT * FROM detailpenjualan 
                        JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                        WHERE PenjualanID = '$id'");
                while($d = mysqli_fetch_array($detail)){
                ?>
                <div class="item">
                    <div>
                        <div class="item-name"><?= $d['NamaProduk']; ?></div>
                        <div class="item-qty"><?= $d['JumlahProduk']; ?> item</div>
                    </div>
                    <div class="item-price">
                        Rp <?= number_format($d['Subtotal'], 0, ',', '.'); ?>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="total-section">
                <div class="total-row">
                    <div class="total-label">Total</div>
                    <div class="total-amount">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Terima Kasih</p>
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=INV-<?= $id; ?>" alt="QR">
            </div>
        </div>

        <div class="actions no-print">
            <button onclick="window.print()" class="btn-action btn-print">CETAK STRUK</button>
            <a href="index.php" class="btn-action btn-new">TRANSAKSI BARU</a>
        </div>
    </div>

</body>
</html>