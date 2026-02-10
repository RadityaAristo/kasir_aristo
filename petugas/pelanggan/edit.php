<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

if($_SESSION['status'] != "login") header("location:../../auth/login.php");

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
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 1rem;
        }
        
        .edit-card {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 2rem;
            border: none;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.25rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .form-control {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
            font-size: 0.9375rem;
        }
        
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
            outline: none;
        }
        
        .form-control:hover {
            border-color: #d1d5db;
        }
        
        textarea.form-control {
            resize: none;
        }
        
        .btn-group-custom {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9375rem;
            flex: 1;
            transition: all 0.2s;
        }
        
        .btn-cancel {
            background: #f3f4f6;
            color: #6b7280;
            border: none;
        }
        
        .btn-cancel:hover {
            background: #e5e7eb;
            color: #374151;
        }
        
        .btn-save {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
        }
        
        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
        
        .mb-custom {
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="edit-card">
            <div class="card-header">
                <h5><i class="fas fa-user-edit me-2"></i>Edit Data Pelanggan</h5>
            </div>
            
            <div class="card-body">
                <form method="POST">
                    <div class="mb-custom">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" name="NamaPelanggan" class="form-control" value="<?= $data['NamaPelanggan']; ?>" required>
                    </div>
                    
                    <div class="mb-custom">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="NomorTelepon" class="form-control" value="<?= $data['NomorTelepon']; ?>" required>
                    </div>
                    
                    <div class="mb-custom">
                        <label class="form-label">Alamat</label>
                        <textarea name="Alamat" class="form-control" rows="3" required><?= $data['Alamat']; ?></textarea>
                    </div>
                    
                    <div class="btn-group-custom">
                        <a href="index.php" class="btn btn-cancel">Batal</a>
                        <button type="submit" name="update" class="btn btn-save">Simpan</button>
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
        }).then(function() {
            window.location.href = 'index.php';
        });
    </script>
    <?php endif; ?>
</body>
</html>