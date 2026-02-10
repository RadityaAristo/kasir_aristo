<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if ($_SESSION['status'] != "login")
    header("location:../../auth/login.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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

        .toolbar {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9375rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .btn-add {
            background: #6366f1;
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #5558e3;
            color: white;
            transform: translateY(-1px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .stat-label {
            font-size: 0.8125rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2937;
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

        .number-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
        }

        .product-name {
            font-weight: 500;
            color: #1f2937;
        }

        .stock-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-block;
        }

        .stock-safe {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-danger {
            background: #fee2e2;
            color: #991b1b;
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

        .table-footer {
            background: #1f2937;
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-label {
            font-size: 0.875rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .footer-value {
            font-size: 1.375rem;
            font-weight: 600;
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
            
            .toolbar {
                flex-direction: column;
            }
            
            .search-box {
                width: 100%;
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
                        <i class="fas fa-box me-2" style="color: #6366f1;"></i>Data Produk
                    </h1>
                    <p class="page-subtitle">Kelola inventaris produk Anda</p>
                    
                    <div class="toolbar">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Cari produk...">
                        </div>
                        <a href="tambah.php" class="btn-add">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Produk</span>
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <?php
                $total_produk = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM produk"));
                $stok_kritis = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM produk WHERE Stok > 0 AND Stok < 10"));
                $stok_habis = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM produk WHERE Stok = 0"));
                $query_aset = mysqli_query($connect, "SELECT SUM(Harga * Stok) as total FROM produk");
                $total_aset = mysqli_fetch_array($query_aset)['total'] ?? 0;
                ?>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-value"><?= $total_produk ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Stok Kritis</div>
                        <div class="stat-value" style="color: #f59e0b;"><?= $stok_kritis ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Stok Habis</div>
                        <div class="stat-value" style="color: #ef4444;"><?= $stok_habis ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Nilai Inventaris</div>
                        <div class="stat-value" style="font-size: 1.375rem; color: #10b981;">
                            Rp <?= number_format($total_aset / 1000000, 1, ',', '.') ?>jt
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table" id="productTable">
                            <thead>
                                <tr>
                                    <th width="8%">No</th>
                                    <th width="42%">Nama Produk</th>
                                    <th width="20%">Harga</th>
                                    <th width="15%" class="text-center">Stok</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $query = mysqli_query($connect, "SELECT * FROM produk ORDER BY NamaProduk ASC");

                                if (mysqli_num_rows($query) == 0) {
                                    echo "<tr><td colspan='5'>
                                            <div class='empty-state'>
                                                <i class='fas fa-box-open'></i>
                                                <h5>Belum Ada Produk</h5>
                                                <p class='text-muted mb-0'>Klik tombol Tambah Produk untuk memulai</p>
                                            </div>
                                        </td></tr>";
                                }

                                while ($d = mysqli_fetch_array($query)) {
                                    if ($d['Stok'] == 0) {
                                        $stok_class = 'stock-danger';
                                    } elseif ($d['Stok'] < 10) {
                                        $stok_class = 'stock-warning';
                                    } else {
                                        $stok_class = 'stock-safe';
                                    }
                                ?>
                                <tr class="product-row">
                                    <td>
                                        <span class="number-box"><?= $no++ ?></span>
                                    </td>
                                    <td class="product-name"><?= htmlspecialchars($d['NamaProduk']) ?></td>
                                    <td style="font-weight: 600;">Rp <?= number_format($d['Harga'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <span class="stock-badge <?= $stok_class ?>">
                                            <?= $d['Stok'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $d['ProdukID'] ?>" class="btn-action edit me-1">
                                            <i class="fas fa-edit" style="color: #f59e0b;"></i>
                                        </a>
                                        <a href="hapus.php?id=<?= $d['ProdukID'] ?>" class="btn-action delete"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash-alt" style="color: #ef4444;"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>

                                <tr id="noResults" style="display: none;">
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-search"></i>
                                            <h5>Produk Tidak Ditemukan</h5>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <?php if (mysqli_num_rows($query) > 0): ?>
                    <div class="table-footer" id="totalFooter">
                        <div class="footer-label">
                            <i class="fas fa-wallet me-2"></i>Total Nilai Inventaris
                        </div>
                        <div class="footer-value">
                            Rp <?= number_format($total_aset, 0, ',', '.') ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#searchInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                var visibleCount = 0;

                $("#productTable tbody .product-row").filter(function () {
                    var match = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(match);
                    if (match) visibleCount++;
                });

                if (visibleCount === 0 && value !== "") {
                    $("#noResults").show();
                    $("#totalFooter").hide();
                } else {
                    $("#noResults").hide();
                    $("#totalFooter").show();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>