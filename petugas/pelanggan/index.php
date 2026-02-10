<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

// Proteksi halaman
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../../auth/login.php");
    exit;
}

// Variabel untuk menandai menu aktif di sidebar
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
        }

        .wrapper {
            padding: 60px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header Section */
        .header-section {
            margin-bottom: 30px;
        }

        .main-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        /* Card Container */
        .card-main {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Card Header */
        .card-top {
            padding: 24px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .card-heading {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        /* Search Box */
        .search-box {
            position: relative;
            width: 320px;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 42px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
        }

        .customer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customer-table thead {
            background: #f9fafb;
        }

        .customer-table thead th {
            padding: 16px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        .customer-table thead th.text-center {
            text-align: center;
        }

        .customer-table tbody td {
            padding: 18px 20px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            color: #374151;
        }

        .customer-table tbody tr {
            transition: background-color 0.2s;
        }

        .customer-table tbody tr:hover {
            background-color: #f9fafb;
        }

        .customer-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Number Badge - sama seperti di produk */
        .number-badge {
            width: 32px;
            height: 32px;
            background: #f3f4f6;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6b7280;
            font-size: 13px;
        }

        /* Customer Name Cell */
        .customer-name {
            font-weight: 600;
            color: #1f2937;
        }

        /* Contact & Address */
        .contact-info,
        .address-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
        }

        .contact-info i,
        .address-info i {
            color: #9ca3af;
            font-size: 13px;
            width: 16px;
        }

        /* Button Styles */
        .btn-view-history {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view-history:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-view-history i {
            font-size: 12px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-edit {
            background: #fef3c7;
            color: #f59e0b;
        }

        .btn-edit:hover {
            background: #fde68a;
            color: #f59e0b;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #fee2e2;
            color: #ef4444;
        }

        .btn-delete:hover {
            background: #fecaca;
            color: #ef4444;
            transform: translateY(-1px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: #e5e7eb;
            margin-bottom: 16px;
        }

        .empty-state h5 {
            font-size: 16px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 14px;
            color: #9ca3af;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .wrapper {
                padding: 24px 16px;
            }

            .main-title {
                font-size: 24px;
            }

            .card-top {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .customer-table thead th,
            .customer-table tbody td {
                padding: 14px 16px;
                font-size: 13px;
            }

            .btn-view-history {
                padding: 7px 14px;
                font-size: 12px;
            }

            .btn-action {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="wrapper flex-grow-1">
            <!-- Header -->
            <div class="header-section">
                <h1 class="main-title">Data Pelanggan</h1>
                <p class="subtitle">Kelola dan pantau informasi pelanggan Anda</p>
            </div>

            <!-- Main Card -->
            <div class="card-main">
                <!-- Card Header -->
                <div class="card-top">
                    <h2 class="card-heading">Daftar Pelanggan</h2>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               id="searchInput" 
                               class="search-input" 
                               placeholder="Cari nama, telepon, atau alamat...">
                    </div>
                </div>

                <!-- Table -->
                <div class="table-container">
                    <table class="customer-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th style="width: 25%;">Nama Pelanggan</th>
                                <th style="width: 20%;">Telepon</th>
                                <th style="width: 25%;">Alamat</th>
                                <th class="text-center" style="width: 15%;">Histori</th>
                                <th class="text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php 
                            $no = 1;
                            // Diubah dari ORDER BY NamaPelanggan ASC menjadi ORDER BY PelangganID ASC
                            // Sehingga pelanggan yang daftar/pesan duluan muncul lebih dulu
                            $query = mysqli_query($connect, "SELECT * FROM pelanggan ORDER BY PelangganID ASC");
                            
                            if(mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_array($query)) {
                                    $pelangganID = $row['PelangganID'];
                                    $namaPelanggan = htmlspecialchars($row['NamaPelanggan']);
                                    $nomorTelepon = htmlspecialchars($row['NomorTelepon']);
                                    $alamat = htmlspecialchars($row['Alamat']);
                            ?>
                            <tr>
                                <td>
                                    <span class="number-badge"><?= $no++; ?></span>
                                </td>
                                <td>
                                    <div class="customer-name"><?= $namaPelanggan; ?></div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <i class="fas fa-phone"></i>
                                        <span><?= $nomorTelepon; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="address-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?= $alamat; ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="histori_belanja.php?id=<?= $pelangganID; ?>" class="btn-view-history">
                                        <i class="fas fa-history"></i>
                                        <span>Lihat</span>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?= $pelangganID; ?>" 
                                           class="btn-action btn-edit" 
                                           title="Edit Pelanggan">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                        <a href="javascript:void(0)" 
                                           onclick="hapusPelanggan(<?= $pelangganID; ?>)" 
                                           class="btn-action btn-delete"
                                           title="Hapus Pelanggan">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else {
                            ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <h5>Belum Ada Data Pelanggan</h5>
                                        <p>Silakan tambahkan pelanggan untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search Function
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('tableBody');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.getElementsByTagName('tr');
            
            for(let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                
                if(rowText.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
        
        // Delete Confirmation Function
        function hapusPelanggan(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus pelanggan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash-alt me-2"></i>Ya, Hapus',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>