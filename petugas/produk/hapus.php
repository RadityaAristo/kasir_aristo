<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk - Kasir Aristoo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
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
        }

        /* Loading */
        .loading {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* SweetAlert Custom */
        .swal2-popup {
            border-radius: 16px !important;
            font-family: 'Inter', sans-serif !important;
            padding: 2rem !important;
        }

        .swal2-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
        }

        .swal2-html-container {
            color: #6b7280 !important;
            line-height: 1.6 !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #667eea, #4b74a2) !important;
            border-radius: 10px !important;
            padding: 0.75rem 2rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4) !important;
        }

        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.5) !important;
        }

        .info-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            text-align: left;
        }

        .info-box p {
            margin: 0;
            font-size: 0.875rem;
            color: #92400e;
        }

        .product-name {
            color: #1f2937;
            font-weight: 700;
        }

        .count {
            color: #f59e0b;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="loading">
    <div class="spinner"></div>
    <p>Memproses...</p>
</div>

<?php 
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);

    // Ambil info produk
    $produk_query = mysqli_query($connect, "SELECT NamaProduk FROM produk WHERE ProdukID='$id'");
    $produk = mysqli_fetch_array($produk_query);
    $nama_produk = $produk ? htmlspecialchars($produk['NamaProduk']) : 'Produk';

    // Cek apakah sudah pernah terjual
    $cek_transaksi = mysqli_query($connect, "SELECT COUNT(*) as total FROM detailpenjualan WHERE ProdukID='$id'");
    $transaksi = mysqli_fetch_array($cek_transaksi);
    $jumlah_transaksi = $transaksi['total'];
    
    if ($jumlah_transaksi > 0) {
        // Tidak bisa dihapus
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Dapat Dihapus',
                html: `
                    <p style='margin-bottom: 1rem;'>
                        Produk <span class='product-name'>$nama_produk</span> sudah tercatat dalam <span class='count'>$jumlah_transaksi transaksi</span>.
                    </p>
                    <div class='info-box'>
                        <p><strong>💡 Saran:</strong> Ubah stok menjadi 0 jika ingin menonaktifkan produk ini.</p>
                    </div>
                `,
                confirmButtonText: 'Kembali',
                width: '500px'
            }).then(() => { 
                window.location.href = 'index.php'; 
            });
        </script>";
    } else {
        // Proses hapus
        $query = mysqli_query($connect, "DELETE FROM produk WHERE ProdukID='$id'");

        if ($query) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Dihapus!',
                    html: `<p>Produk <span class='product-name'>$nama_produk</span> telah dihapus.</p>`,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    width: '450px'
                }).then(() => { 
                    window.location.href = 'index.php'; 
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menghapus',
                    html: `
                        <p>Terjadi kesalahan saat menghapus produk.</p>
                        <div class='info-box' style='background: #fee2e2; border-left-color: #ef4444;'>
                            <p style='color: #991b1b;'><strong>Error:</strong> " . mysqli_error($connect) . "</p>
                        </div>
                    `,
                    confirmButtonText: 'Tutup',
                    width: '500px'
                }).then(() => { 
                    window.location.href = 'index.php'; 
                });
            </script>";
        }
    }
} else {
    header("location:index.php");
    exit;
}
?>

</body>
</html>