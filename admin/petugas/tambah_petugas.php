<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
// Proteksi halaman
if($_SESSION['status'] != "login") header("location:../../auth/login.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Kasir Aristoo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            min-height: 100vh;
            color: #2d3748;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .main-container {
            max-width: 480px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.2s;
        }

        .back-link:hover {
            color: #2d3748;
            transform: translateX(-3px);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            background: white;
        }

        .card-header {
            background: white;
            border: none;
            padding: 2rem 2rem 1rem;
            text-align: center;
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .header-icon i {
            font-size: 1.8rem;
            color: white;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.5rem;
        }

        .card-subtitle {
            font-size: 0.9rem;
            color: #718096;
        }

        .card-body {
            padding: 1.5rem 2rem 2rem;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .form-control,
        .input-with-icon .form-select {
            padding-left: 2.75rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-cancel {
            color: #718096;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            color: #2d3748;
        }

        .security-note {
            background: #f7fafc;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }

        .security-note i {
            color: #667eea;
        }

        .security-note span {
            font-size: 0.85rem;
            color: #718096;
        }

        @media (max-width: 576px) {
            .card-header {
                padding: 1.5rem 1.5rem 1rem;
            }

            .card-body {
                padding: 1rem 1.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="flex-fill">
            <div class="main-container">

                <div class="card">
                    <div class="card-header">
                        <div class="header-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4 class="card-title">Tambah User Baru</h4>
                        <p class="card-subtitle">Buat akun untuk mengakses sistem</p>
                    </div>

                    <div class="card-body">
                        <form action="proses_tambah.php" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="Username" class="form-control" 
                                           placeholder="Masukkan username" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" name="Password" class="form-control" 
                                           placeholder="Masukkan password" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Level Akses</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-id-badge input-icon"></i>
                                    <select name="Role" class="form-select" required>
                                        <option value="" disabled selected>Pilih level akses</option>
                                        <option value="admin">Administrator</option>
                                        <option value="petugas">Petugas Kasir</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check-circle me-2"></i>Tambah User
                            </button>
                            
                            <div class="text-center">
                                <a href="index.php" class="btn-cancel">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                            </div>
                        </form>

                        <div class="security-note">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Data tersimpan dengan aman</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>