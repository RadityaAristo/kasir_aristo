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
    <title>Manajemen User - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            font-family: 'Inter', sans-serif;
            color: var(--gray-800);
            line-height: 1.6;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--gray-600);
            font-size: 0.938rem;
        }

        /* Button */
        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add:hover {
            background: var(--primary-light);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Table */
        .table {
            margin: 0;
        }

        .table thead {
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
        }

        .table thead th {
            padding: 1rem 1.5rem;
            font-weight: 600;
            font-size: 0.813rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            border: none;
        }

        .table tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr {
            transition: background 0.2s;
        }

        .table tbody tr:hover {
            background: var(--gray-50);
        }

        /* Number */
        .row-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: var(--primary);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #eff6ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1rem;
        }

        .username {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.938rem;
        }

        /* Role Badges */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .role-admin {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .role-petugas {
            background: #ecfeff;
            color: #155e75;
            border: 1px solid #a5f3fc;
        }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border: 1px solid var(--gray-300);
            background: white;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-action.edit:hover {
            background: #eff6ff;
            border-color: var(--primary);
        }

        .btn-action.delete:hover {
            background: #fef2f2;
            border-color: var(--danger);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 3.5rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
        }

        .empty-title {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: var(--gray-500);
            font-size: 0.938rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .btn-add {
                width: 100%;
            }

            .table thead th,
            .table tbody td {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>

        <div class="flex-grow-1">
            <div class="main-container">
                
                <!-- Header -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-users-cog me-2" style="color: var(--primary);"></i>Manajemen User
                        </h1>
                        <p class="page-subtitle">Kelola hak akses petugas dan administrator</p>
                    </div>
                    <a href="tambah_petugas.php" class="btn-add">
                        <i class="fas fa-plus me-2"></i>Tambah User
                    </a>
                </div>

                <!-- Table -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Hak Akses</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                $query = mysqli_query($connect, "SELECT * FROM user");
                                
                                if(mysqli_num_rows($query) == 0) {
                                    echo "<tr><td colspan='4' class='p-0'>
                                            <div class='empty-state'>
                                                <div class='empty-icon'><i class='fas fa-users'></i></div>
                                                <div class='empty-title'>Belum Ada User</div>
                                                <div class='empty-text'>Klik tombol Tambah User untuk memulai</div>
                                            </div>
                                          </td></tr>";
                                }
                                
                                while($d = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td>
                                        <span class="row-num"><?= str_pad($no++, 1, "0", STR_PAD_LEFT); ?></span>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="username"><?= $d['Username']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($d['Role'] == 'admin'): ?>
                                            <span class="role-badge role-admin">
                                                <i class="fas fa-shield-alt"></i>
                                                Admin
                                            </span>
                                        <?php else: ?>
                                            <span class="role-badge role-petugas">
                                                <i class="fas fa-user-tag"></i>
                                                Petugas
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="edit_petugas.php?id=<?= $d['UserID']; ?>" 
                                               class="btn-action edit" 
                                               title="Edit">
                                                <i class="fas fa-edit" style="color: var(--primary);"></i>
                                            </a>
                                            <button onclick="confirmDelete(<?= $d['UserID']; ?>)" 
                                                    class="btn-action delete" 
                                                    title="Hapus">
                                                <i class="fas fa-trash-alt" style="color: var(--danger);"></i>
                                            </button>
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
    </div>

    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus User?',
            text: "User ini akan kehilangan akses ke sistem!",
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

    <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data user telah diperbarui.',
            icon: 'success',
            confirmButtonColor: '#4f46e5'
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>