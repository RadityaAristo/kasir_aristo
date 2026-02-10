<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

// Proteksi: Hanya Admin yang boleh menghapus
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
if($_SESSION['role'] != 'admin') header("location:../../petugas/dashboard/index.php");

$id = $_GET['id'] ?? null;

// Validasi ID
if (!$id) {
    header("location:index.php?pesan=id_tidak_valid");
    exit;
}

// Cek apakah ada konfirmasi
$confirm = $_GET['confirm'] ?? null;

if ($confirm === 'yes') {
    // Mulai Transaction untuk keamanan data
    mysqli_begin_transaction($connect);
    
    try {
        // 1. Ambil detail barang yang dibeli untuk mengembalikan stok
        $detail = mysqli_query($connect, "SELECT * FROM detailpenjualan WHERE PenjualanID = '$id'");
        
        if (!$detail) {
            throw new Exception("Gagal mengambil detail penjualan");
        }
        
        while($d = mysqli_fetch_array($detail)){
            $produkID = $d['ProdukID'];
            $qty = $d['JumlahProduk'];
            
            // Tambahkan kembali stoknya
            $update_stok = mysqli_query($connect, "UPDATE produk SET Stok = Stok + $qty WHERE ProdukID = '$produkID'");
            
            if (!$update_stok) {
                throw new Exception("Gagal mengembalikan stok produk");
            }
        }

        // 2. Hapus data di detailpenjualan
        $hapus_detail = mysqli_query($connect, "DELETE FROM detailpenjualan WHERE PenjualanID = '$id'");
        
        if (!$hapus_detail) {
            throw new Exception("Gagal menghapus detail penjualan");
        }

        // 3. Hapus data di penjualan
        $hapus_penjualan = mysqli_query($connect, "DELETE FROM penjualan WHERE PenjualanID = '$id'");
        
        if (!$hapus_penjualan) {
            throw new Exception("Gagal menghapus data penjualan");
        }

        // Commit transaction jika semua berhasil
        mysqli_commit($connect);
        
        header("location:index.php?pesan=berhasil_dihapus");
        exit;
        
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($connect);
        header("location:index.php?pesan=gagal_hapus&error=" . urlencode($e->getMessage()));
        exit;
    }
}

// Jika belum konfirmasi, tampilkan halaman konfirmasi
// Ambil data transaksi untuk ditampilkan
$query = mysqli_query($connect, "SELECT p.*, pel.NamaPelanggan 
                               FROM penjualan p 
                               JOIN pelanggan pel ON p.PelangganID = pel.PelangganID 
                               WHERE p.PenjualanID = '$id'");
$data = mysqli_fetch_array($query);

if (!$data) {
    header("location:index.php?pesan=data_tidak_ditemukan");
    exit;
}

// Hitung jumlah item
$count_items = mysqli_query($connect, "SELECT COUNT(*) as total FROM detailpenjualan WHERE PenjualanID = '$id'");
$items = mysqli_fetch_array($count_items);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Transaksi - Kasir Aristoo<?= $id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 480px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, #448bef, #1959ad);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-wrapper i {
            font-size: 36px;
        }

        .card-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.95;
        }

        .card-body {
            padding: 30px;
        }

        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-box p {
            font-size: 13px;
            color: #92400e;
            line-height: 1.6;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }

        .detail-value {
            color: #111827;
            font-weight: 600;
            font-size: 14px;
        }

        .detail-value.price {
            color: #ef4444;
            font-size: 16px;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            padding: 14px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: linear-gradient(135deg, #449cef, #265ddc);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        .btn-delete:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 576px) {
            .card-header {
                padding: 30px 20px;
            }

            .card-body {
                padding: 20px;
            }

            .button-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        
        <div class="card-header">
            <div class="icon-wrapper">
                <i class="fas fa-trash-alt"></i>
            </div>
            <h2>Hapus Transaksi</h2>
            <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
        </div>

        <div class="card-body">
            
            <div class="info-box">
                <p><strong>Perhatian:</strong> Data yang dihapus tidak dapat dikembalikan. Stok produk akan otomatis dikembalikan.</p>
            </div>

            <div class="detail-item">
                <span class="detail-label">ID Transaksi</span>
                <span class="detail-value">#<?= str_pad($id, 3, '0', STR_PAD_LEFT); ?></span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Pelanggan</span>
                <span class="detail-value"><?= $data['NamaPelanggan']; ?></span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Tanggal</span>
                <span class="detail-value"><?= date('d/m/Y H:i', strtotime($data['TanggalPenjualan'])); ?></span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Jumlah Item</span>
                <span class="detail-value"><?= $items['total']; ?> Produk</span>
            </div>

            <div class="detail-item">
                <span class="detail-label">Total</span>
                <span class="detail-value price">Rp <?= number_format($data['TotalHarga'], 0, ',', '.'); ?></span>
            </div>

            <div class="button-group">
                <button onclick="window.location.href='index.php'" class="btn btn-cancel">
                    <i class="fas fa-arrow-left"></i>
                    Batal
                </button>
                
                <button onclick="deleteTransaction()" class="btn btn-delete" id="deleteBtn">
                    <i class="fas fa-check"></i>
                    <span id="btnText">Ya, Hapus</span>
                </button>
            </div>

        </div>

    </div>
</div>

<script>
function deleteTransaction() {
    const btn = document.getElementById('deleteBtn');
    const btnText = document.getElementById('btnText');
    
    btn.disabled = true;
    btnText.textContent = 'Menghapus...';
    
    setTimeout(() => {
        window.location.href = 'hapus.php?id=<?= $id; ?>&confirm=yes';
    }, 800);
}

// ESC untuk batal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        window.location.href = 'index.php';
    }
});
</script>

</body>
</html>