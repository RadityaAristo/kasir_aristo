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
    <title>Hapus Pelanggan - Kasir Aristo</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading {
            text-align: center;
            color: white;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .swal2-popup {
            border-radius: 12px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
            border-radius: 8px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
        }
    </style>
</head>
<body>
    <div class="loading">
        <div class="spinner"></div>
        <p>Memproses...</p>
    </div>

    <?php
    if(isset($_GET['id'])) {
        $id = mysqli_real_escape_string($connect, $_GET['id']);
        
        // Cek apakah pelanggan masih memiliki histori transaksi
        $checkQuery = mysqli_query($connect, "SELECT COUNT(*) as total FROM penjualan WHERE PelangganID='$id'");
        $checkData = mysqli_fetch_assoc($checkQuery);
        
        if($checkData['total'] > 0) {
            // Pelanggan masih memiliki histori transaksi
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Menghapus',
                    html: 'Pelanggan ini masih memiliki <strong>" . $checkData['total'] . " histori transaksi</strong>.<br>Hapus semua histori transaksi terlebih dahulu.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f39c12'
                }).then(() => window.location.href = 'index.php');
            </script>";
        } else {
            // Pelanggan tidak memiliki histori, boleh dihapus
            $query = mysqli_query($connect, "DELETE FROM pelanggan WHERE PelangganID='$id'");

            if($query) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Dihapus',
                        text: 'Data pelanggan telah dihapus',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    }).then(() => window.location.href = 'index.php');
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus',
                        text: 'Terjadi kesalahan saat menghapus data',
                        confirmButtonText: 'OK'
                    }).then(() => window.location.href = 'index.php');
                </script>";
            }
        }
    } else {
        // Jika tidak ada ID
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Valid',
                text: 'ID pelanggan tidak ditemukan',
                confirmButtonText: 'OK'
            }).then(() => window.location.href = 'index.php');
        </script>";
    }
    ?>
</body>
</html>