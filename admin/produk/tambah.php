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
    <title>Tambah Produk - Kasir Aristo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-500: #64748b;
            --gray-600: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark);
            line-height: 1.6;
        }

        .main-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* Back Button */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-600);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            background: white;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }

        .back-link:hover {
            color: var(--primary);
            background: var(--gray-50);
            transform: translateX(-4px);
        }

        /* Card Container */
        .form-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06);
            animation: fadeInUp 0.4s ease-out;
        }

        /* Accent Bar */
        .accent-bar {
            height: 4px;
            background: linear-gradient(90deg, var(--primary), #0ea5e9);
        }

        /* Card Header */
        .card-header-custom {
            padding: 2.5rem 2rem 2rem 2rem;
            text-align: center;
            border-bottom: 1px solid var(--gray-200);
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .header-icon i {
            font-size: 1.75rem;
            color: var(--primary);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin: 0;
        }

        /* Form Body */
        .card-body-custom {
            padding: 2rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-label-required::after {
            content: "*";
            color: var(--danger);
            margin-left: 0.25rem;
        }

        /* Input Styling */
        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-500);
            font-size: 0.875rem;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            background: white;
        }

        .form-input.with-icon {
            padding-left: 2.75rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input::placeholder {
            color: var(--gray-400);
        }

        /* Input Group for Currency */
        .input-group-custom {
            display: flex;
            gap: 0;
        }

        .input-prefix {
            display: flex;
            align-items: center;
            padding: 0 1rem;
            background: var(--gray-100);
            border: 1px solid var(--gray-300);
            border-right: none;
            border-radius: 10px 0 0 10px;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .input-group-custom .form-input {
            border-radius: 0 10px 10px 0;
        }

        /* Help Text */
        .form-help {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.375rem;
        }

        /* Buttons */
        .btn-submit {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.938rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-cancel {
            width: 100%;
            padding: 0.75rem;
            background: transparent;
            color: var(--gray-600);
            border: 1px solid var(--gray-300);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.75rem;
        }

        .btn-cancel:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
            color: var(--gray-700);
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-left: 3px solid var(--primary);
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-top: 1.5rem;
        }

        .info-box-content {
            display: flex;
            gap: 0.75rem;
            align-items: start;
        }

        .info-icon {
            color: var(--primary);
            font-size: 1rem;
            margin-top: 0.125rem;
        }

        .info-text {
            font-size: 0.813rem;
            color: var(--gray-600);
            line-height: 1.5;
            margin: 0;
        }

        /* Quick Tips */
        .tips-card {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-top: 1.5rem;
        }

        .tips-title {
            font-size: 0.813rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tips-list li {
            font-size: 0.75rem;
            color: var(--gray-600);
            padding: 0.375rem 0;
            padding-left: 1.5rem;
            position: relative;
        }

        .tips-list li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--success);
            font-weight: 700;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-wrapper {
                padding: 1.5rem 1rem;
            }

            .card-header-custom,
            .card-body-custom {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .header-icon {
                width: 56px;
                height: 56px;
            }

            .header-icon i {
                font-size: 1.5rem;
            }
        }

        /* Custom SweetAlert2 Styling */
        .swal2-popup {
            border-radius: 16px !important;
            font-family: 'Inter', sans-serif !important;
        }

        .swal2-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: var(--dark) !important;
        }

        .swal2-html-container {
            font-size: 0.938rem !important;
            color: var(--gray-600) !important;
        }

        .swal2-confirm {
            background: var(--primary) !important;
            border-radius: 8px !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            background: var(--gray-200) !important;
            color: var(--gray-700) !important;
            border-radius: 8px !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>
    
    <div class="flex-grow-1">
        <div class="main-wrapper">
            
            <!-- Back Link -->
            <a href="index.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Produk</span>
            </a>

            <!-- Form Card -->
            <div class="form-card">
                
                <!-- Accent Bar -->
                <div class="accent-bar"></div>
                
                <!-- Header -->
                <div class="card-header-custom">
                    <div class="header-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h1 class="page-title">Tambah Produk Baru</h1>
                    <p class="page-subtitle">Lengkapi informasi produk untuk ditambahkan ke inventaris</p>
                </div>

                <!-- Form -->
                <div class="card-body-custom">
                    <form id="formTambah" action="proses_tambah.php" method="POST">
                        
                        <!-- Nama Produk -->
                        <div class="form-group">
                            <label class="form-label form-label-required">Nama Produk</label>
                            <div class="input-wrapper">
                                <i class="fas fa-box input-icon"></i>
                                <input type="text" 
                                       name="NamaProduk" 
                                       class="form-input with-icon" 
                                       placeholder=". . ." 
                                       required>
                            </div>
                            <div class="form-help">Masukkan nama produk yang jelas dan deskriptif</div>
                        </div>

                        <div class="row">
                            <!-- Harga -->
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="form-label form-label-required">Harga Jual</label>
                                    <div class="input-group-custom">
                                        <span class="input-prefix">Rp</span>
                                        <input type="number" 
                                               name="Harga" 
                                               class="form-input" 
                                               placeholder="0" 
                                               min="0"
                                               step="100"
                                               required>
                                    </div>
                                    <div class="form-help">Harga jual per unit produk</div>
                                </div>
                            </div>

                            <!-- Stok -->
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="form-label form-label-required">Stok Awal</label>
                                    <div class="input-wrapper">
                                        <i class="fas fa-cubes input-icon"></i>
                                        <input type="number" 
                                               name="Stok" 
                                               class="form-input with-icon" 
                                               placeholder="0" 
                                               min="0"
                                               required>
                                    </div>
                                    <div class="form-help">Jumlah unit tersedia</div>
                                </div>
                            </div>
                        </div>

                        <!-- Tips Card -->
                        <div class="tips-card">
                            <div class="tips-title">
                                <i class="fas fa-lightbulb" style="color: var(--warning);"></i>
                                <span>Tips Pengisian</span>
                            </div>
                            <ul class="tips-list">
                                <li>Gunakan nama produk yang mudah dicari dan dipahami</li>
                                <li>Pastikan harga sudah termasuk markup/keuntungan yang diinginkan</li>
                                <li>Stok awal dapat diperbarui kemudian melalui menu edit</li>
                            </ul>
                        </div>

                        <!-- Info Box -->
                        <div class="info-box">
                            <div class="info-box-content">
                                <i class="fas fa-info-circle info-icon"></i>
                                <p class="info-text">
                                    <strong>Perhatian:</strong> Data produk yang ditambahkan akan langsung tersedia dalam sistem 
                                    dan dapat digunakan untuk transaksi penjualan.
                                </p>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div style="margin-top: 2rem;">
                            <button type="button" onclick="confirmAdd()" class="btn-submit">
                                <i class="fas fa-check-circle"></i>
                                <span>Simpan Produk</span>
                            </button>
                            <a href="index.php" class="btn-cancel">Batal</a>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function confirmAdd() {
    const form = document.getElementById('formTambah');
    
    // Validasi form
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const nama = form.NamaProduk.value.trim();
    const harga = parseInt(form.Harga.value);
    const stok = parseInt(form.Stok.value);

    // Validasi nilai
    if (harga < 0 || stok < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Input Tidak Valid',
            text: 'Harga dan stok tidak boleh bernilai negatif.',
            confirmButtonColor: '#2563eb'
        });
        return;
    }

    // Konfirmasi dengan preview data
    Swal.fire({
        title: 'Konfirmasi Data Produk',
        html: `
            <div style="text-align: left; padding: 0 1rem;">
                <p style="margin-bottom: 1rem; color: #64748b;">Pastikan data berikut sudah benar:</p>
                <table style="width: 100%; font-size: 0.875rem;">
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.5rem 0; color: #64748b;">Nama Produk</td>
                        <td style="padding: 0.5rem 0; font-weight: 600; text-align: right;">${nama}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.5rem 0; color: #64748b;">Harga Jual</td>
                        <td style="padding: 0.5rem 0; font-weight: 600; text-align: right;">Rp ${harga.toLocaleString('id-ID')}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0.5rem 0; color: #64748b;">Stok Awal</td>
                        <td style="padding: 0.5rem 0; font-weight: 600; text-align: right;">${stok} unit</td>
                    </tr>
                </table>
                <p style="margin-top: 1rem; font-size: 0.813rem; color: #64748b;">
                    <i class="fas fa-info-circle" style="color: #2563eb;"></i> 
                    Data ini akan langsung ditambahkan ke sistem inventaris.
                </p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#e2e8f0',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Ya, Tambahkan',
        cancelButtonText: 'Periksa Kembali',
        reverseButtons: true,
        width: '550px',
        customClass: {
            confirmButton: 'swal2-confirm',
            cancelButton: 'swal2-cancel'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Menyimpan Data...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            form.submit();
        }
    });
}

// Auto format number inputs
document.querySelector('input[name="Harga"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^\d]/g, '');
});

document.querySelector('input[name="Stok"]').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^\d]/g, '');
});

// Prevent negative values on paste
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('paste', function(e) {
        setTimeout(() => {
            if (this.value < 0) this.value = 0;
        }, 10);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>