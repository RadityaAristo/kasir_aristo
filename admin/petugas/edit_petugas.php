<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

$id = $_GET['id'];
$data = mysqli_query($connect, "SELECT * FROM user WHERE UserID='$id'");
$d = mysqli_fetch_array($data);

if (!$d) {
    header("location:index.php");
    exit;
}

// Proses Update
if(isset($_POST['update'])){
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if(empty($password)){
        mysqli_query($connect, "UPDATE user SET Username='$username', Role='$role' WHERE UserID='$id'");
    } else {
        mysqli_query($connect, "UPDATE user SET Username='$username', Role='$role', Password='$password' WHERE UserID='$id'");
    }
    header("location:index.php?pesan=sukses");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Kasir Aristoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content-wrapper {
            padding: 40px 20px;
            max-width: 650px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 24px;
            padding: 8px 16px;
            border-radius: 6px;
            background-color: white;
            border: 1px solid #dee2e6;
            transition: all 0.2s;
        }

        .back-link:hover {
            background-color: #f8f9fa;
            color: #212529;
            border-color: #adb5bd;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 28px 30px;
            border: none;
        }

        .card-title {
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        .card-body {
            padding: 30px;
            background-color: white;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            color: #6366f1;
            font-size: 13px;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #d1d5db;
            border-radius: 6px;
            padding: 11px 14px;
            font-size: 14px;
            transition: all 0.2s;
            background-color: #fafafa;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background-color: white;
            outline: none;
        }

        .help-text {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .help-text i {
            font-size: 12px;
        }

        .role-info {
            background-color: #fef3c7;
            border-left: 3px solid #f59e0b;
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 13px;
            color: #92400e;
            margin-top: 10px;
        }

        .section-divider {
            border-top: 1px solid #e5e7eb;
            margin: 24px 0;
        }

        .btn-update {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .btn-update:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            color: white;
        }

        .btn-cancel {
            background-color: white;
            color: #6b7280;
            border: 1.5px solid #d1d5db;
            padding: 12px 28px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background-color: #f9fafb;
            color: #374151;
            border-color: #9ca3af;
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 24px 15px;
            }

            .card-header {
                padding: 22px 20px;
            }

            .card-body {
                padding: 24px 20px;
            }

            .card-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="flex-grow-1">
            <div class="content-wrapper">

                <div class="card">
                    <div class="card-header">
                        <h1 class="card-title">
                            <i class="fas fa-user-edit"></i>
                            Edit User
                        </h1>
                        <p class="card-subtitle">Perbarui informasi pengguna</p>
                    </div>

                    <div class="card-body">
                        <form method="POST">
                            
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Username
                                </label>
                                <input 
                                    type="text" 
                                    name="username" 
                                    class="form-control" 
                                    value="<?= htmlspecialchars($d['Username']); ?>" 
                                    required
                                    placeholder="Masukkan username"
                                >
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-user-shield"></i>
                                    Hak Akses
                                </label>
                                <select name="role" class="form-select">
                                    <option value="admin" <?= $d['Role'] == 'admin' ? 'selected' : ''; ?>>
                                        Admin - Akses Penuh
                                    </option>
                                    <option value="petugas" <?= $d['Role'] == 'petugas' ? 'selected' : ''; ?>>
                                        Petugas - Kasir
                                    </option>
                                </select>
                                <div class="role-info">
                                    <i class="fas fa-info-circle"></i>
                                    Role menentukan hak akses menu dan fitur
                                </div>
                            </div>

                            <div class="section-divider"></div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-key"></i>
                                    Password Baru
                                </label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="Masukkan password baru (opsional)"
                                >
                                <div class="help-text">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Kosongkan jika tidak ingin mengubah password
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="update" class="btn-update">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Perubahan
                                </button>
                                <a href="index.php" class="btn-cancel">
                                    <i class="fas fa-times me-2"></i>
                                    Batal
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>