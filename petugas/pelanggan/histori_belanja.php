<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$pelanggan = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM pelanggan WHERE PelangganID='$id'"));
$current_dir = 'pelanggan'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Belanjaa - <?= $pelanggan['NamaPelanggan']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
            min-height: 100vh;
        }
        
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .header-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        
        .header-section h4 {
            color: #1a1a1a;
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }
        
        .header-section .customer-name {
            color: #6366f1;
            font-weight: 500;
        }
        
        .btn-back {
            background: white;
            border: 1px solid #e5e7eb;
            color: #6b7280;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            color: #374151;
            transform: translateX(-2px);
        }
        
        .transaction-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.3s ease;
            border: 1px solid #f3f4f6;
        }
        
        .transaction-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .transaction-date {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .transaction-date i {
            color: #9ca3af;
            margin-right: 0.5rem;
        }
        
        .transaction-total {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.125rem;
        }
        
        .products-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .products-list li {
            padding: 0.625rem 0;
            color: #374151;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
        }
        
        .products-list li:before {
            content: "•";
            color: #6366f1;
            font-weight: bold;
            display: inline-block;
            width: 1.5rem;
            font-size: 1.25rem;
        }
        
        .product-quantity {
            color: #9ca3af;
            font-weight: 500;
            margin-left: 0.5rem;
        }
        
        .btn-invoice {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-invoice:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .empty-state {
            background: white;
            border-radius: 16px;
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1.5rem;
        }
        
        .empty-state h5 {
            color: #6b7280;
            font-weight: 500;
            margin: 0;
        }
        
        .transaction-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="main-container flex-grow-1">
            <!-- Back Buttonn -->
            <div class="mb-3">
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <!-- Header Section -->
            <div class="header-section">
                <h4>
                    <i class="fas fa-receipt me-2" style="color: #6366f1;"></i>
                    Histori Transaksi 
                    <span class="customer-name"><?= $pelanggan['NamaPelanggan']; ?></span>
                </h4>
            </div>

            <!-- Transactions List -->
            <?php 
            $query = mysqli_query($connect, "SELECT * FROM penjualan WHERE PelangganID='$id' ORDER BY TanggalPenjualan DESC");
            if(mysqli_num_rows($query) > 0) {
                while($t = mysqli_fetch_array($query)){
                    $pID = $t['PenjualanID'];
            ?>
            <div class="transaction-card">
                <div class="transaction-header">
                    <div class="transaction-date">
                        <i class="far fa-calendar-alt"></i>
                        <?= date('d M Y, H:i', strtotime($t['TanggalPenjualan'])); ?>
                    </div>
                    <div class="transaction-total">
                        Rp <?= number_format($t['TotalHarga'], 0, ',', '.'); ?>
                    </div>
                </div>
                
                <div class="transaction-body">
                    <ul class="products-list">
                        <?php 
                        $detail = mysqli_query($connect, "SELECT detailpenjualan.*, produk.NamaProduk 
                                                      FROM detailpenjualan 
                                                      JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                      WHERE PenjualanID='$pID'");
                        while($d = mysqli_fetch_array($detail)){
                        ?>
                        <li>
                            <?= $d['NamaProduk']; ?>
                            <span class="product-quantity">×<?= $d['JumlahProduk']; ?></span>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                
                <div class="transaction-footer">
                    <a href="../penjualan/detail.php?id=<?= $pID; ?>" class="btn-invoice">
                        <i class="fas fa-file-invoice me-2"></i>Lihat Invoice
                    </a>
                </div>
            </div>
            <?php 
                } 
            } else {
            ?>
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h5>Belum Ada Transaksi</h5>
                <p class="text-muted mb-0 mt-2">Pelanggan ini belum pernah melakukan pembelian</p>
            </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>