<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

$current_dir = 'pelanggan'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - Kasir Aristoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
        }

        .main-content {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.9375rem;
        }

        .table-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table thead th {
            background: #f9fafb;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            padding: 1rem 1.25rem;
            border: none;
        }

        .table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
        }

        .table tbody tr:last-child td {
            border: none;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .row-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .customer-name {
            font-weight: 500;
            color: #1f2937;
        }

        .contact-info {
            color: #6b7280;
            font-size: 0.9375rem;
        }

        .btn-history {
            background: #6366f1;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-history:hover {
            background: #5558e3;
            color: white;
            transform: translateY(-1px);
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-action.edit:hover {
            background: #fffbeb;
            border-color: #f59e0b;
        }

        .btn-action.delete:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-state h5 {
            color: #6b7280;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="flex-grow-1">
            <div class="main-content">
                
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">
                        <i class="fas fa-users me-2" style="color: #6366f1;"></i>Data Pelanggan
                    </h1>
                    <p class="page-subtitle">Kelola data pelanggan dan histori belanja</p>
                </div>

                <!-- Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Pelanggan</th>
                                    <th width="18%">Nomor Telepon</th>
                                    <th width="27%">Alamat</th>
                                    <th width="15%" class="text-center">Histori</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                // Diubah dari ORDER BY NamaPelanggan ASC menjadi ORDER BY PelangganID ASC
                                // Sehingga pelanggan yang daftar/pesan duluan muncul lebih dulu
                                $query = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY PelangganID ASC");
                                
                                if(mysqli_num_rows($query) > 0) {
                                    while($row = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td><span class="row-number"><?= $no++; ?></span></td>
                                    <td class="customer-name"><?= $row['NamaPelanggan']; ?></td>
                                    <td class="contact-info">
                                        <i class="fas fa-phone me-2" style="color: #10b981;"></i>
                                        <?= $row['NomorTelepon']; ?>
                                    </td>
                                    <td class="contact-info">
                                        <i class="fas fa-map-marker-alt me-2" style="color: #ef4444;"></i>
                                        <?= $row['Alamat']; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="histori_belanja.php?id=<?= $row['PelangganID']; ?>" class="btn-history">
                                            <i class="fas fa-history me-1"></i>Lihat
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="edit.php?id=<?= $row['PelangganID']; ?>" class="btn-action edit">
                                                <i class="fas fa-edit" style="color: #f59e0b;"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="konfirmasiHapus(<?= $row['PelangganID']; ?>)" class="btn-action delete">
                                                <i class="fas fa-trash-alt" style="color: #ef4444;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='6'>
                                            <div class='empty-state'>
                                                <i class='fas fa-user-friends'></i>
                                                <h5>Belum Ada Data Pelanggan</h5>
                                                <p class='text-muted mb-0'>Mulai tambahkan pelanggan untuk memulai</p>
                                            </div>
                                          </td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "hapus.php?id=" + id;
            }
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>