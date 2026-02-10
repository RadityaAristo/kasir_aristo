<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

$id = $_GET['id'];
$data = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM pelanggan WHERE PelangganID='$id'"));

$update_success = false;

if(isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($connect, $_POST['NamaPelanggan']);
    $alamat = mysqli_real_escape_string($connect, $_POST['Alamat']);
    $telp = mysqli_real_escape_string($connect, $_POST['NomorTelepon']);

    $query = mysqli_query($connect, "UPDATE pelanggan SET NamaPelanggan='$nama', Alamat='$alamat', NomorTelepon='$telp' WHERE PelangganID='$id'");
    
    if($query) {
        $update_success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan - Kasir Aristoo</title>
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
            padding: 2rem 1rem;
        }

        .edit-container {
            max-width: 500px;
            margin: 0 auto;
        }

        .edit-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            background: #6366f1;
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: none;
        }

        .card-header h5 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .card-body {
            padding: 2rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
            outline: none;
        }

        textarea.form-control {
            resize: none;
            min-height: 100px;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s;
        }

        .btn-light {
            background: #f3f4f6;
            color: #374151;
            border: none;
        }

        .btn-light:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .btn-primary {
            background: #6366f1;
            border: none;
        }

        .btn-primary:hover {
            background: #5558e3;
            transform: translateY(-1px);
        }

        .mb-custom {
            margin-bottom: 1.25rem;
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem 0.5rem;
            }
            
            .card-header {
                padding: 1.25rem 1.5rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-card">
            <!-- Header -->
            <div class="card-header">
                <h5><i class="fas fa-user-edit me-2"></i>Edit Data Pelanggan</h5>
            </div>

            <!-- Body -->
            <div class="card-body">
                <form method="POST">
                    <div class="mb-custom">
                        <label class="form-label">Nama Pelanggan</label>
                        <input 
                            type="text" 
                            name="NamaPelanggan" 
                            class="form-control" 
                            value="<?= $data['NamaPelanggan']; ?>" 
                            required
                        >
                    </div>

                    <div class="mb-custom">
                        <label class="form-label">Nomor Telepon</label>
                        <input 
                            type="text" 
                            name="NomorTelepon" 
                            class="form-control" 
                            value="<?= $data['NomorTelepon']; ?>" 
                            required
                        >
                    </div>

                    <div class="mb-custom">
                        <label class="form-label">Alamat</label>
                        <textarea 
                            name="Alamat" 
                            class="form-control" 
                            required
                        ><?= $data['Alamat']; ?></textarea>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="index.php" class="btn btn-light flex-fill">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" name="update" class="btn btn-primary flex-fill">
                            <i class="fas fa-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if($update_success): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pelanggan telah diperbarui',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>
    <?php endif; ?>
</body>
</html>