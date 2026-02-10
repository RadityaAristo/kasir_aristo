<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        /* Header Section */
        .card-header-custom {
            background: linear-gradient(135deg, #1e293b, #334155);
            padding: 40px 35px 35px 35px;
            text-align: center;
            position: relative;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #3d4ea1, #aa1f1f);
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .brand-logo i {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-header-custom h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
            margin-bottom: 6px;
        }

        .card-header-custom p {
            font-size: 0.85rem;
            color: #cbd5e1;
            font-weight: 500;
        }

        /* Body Section */
        .card-body-custom {
            padding: 35px;
        }

        /* Alert */
        .error-message {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-message i {
            color: #dc2626;
        }

        .error-message span {
            color: #991b1b;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Form */
        .input-group-custom {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .input-field-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.3s;
        }

        .input-field-wrapper:focus-within {
            background: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .input-prefix {
            padding-left: 18px;
            color: #9ca3af;
            font-size: 1.1rem;
        }

        .input-field-wrapper:focus-within .input-prefix {
            color: #3b82f6;
        }

        .input-field {
            flex: 1;
            background: transparent;
            border: none;
            padding: 14px 15px;
            font-size: 0.95rem;
            color: #1f2937;
            font-weight: 500;
        }

        .input-field:focus {
            outline: none;
        }

        .input-field::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .input-suffix {
            padding-right: 18px;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .input-suffix:hover {
            color: #3b82f6;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            cursor: pointer;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Footer */
        .card-footer-custom {
            text-align: center;
            padding: 25px 35px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .card-footer-custom p {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card-header-custom,
            .card-body-custom,
            .card-footer-custom {
                padding-left: 25px;
                padding-right: 25px;
            }

            .card-header-custom h2 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Header -->
            <div class="card-header-custom">
                <div class="brand-logo">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h2>Kasir Aristo</h2>
                <p>Sistem Manajemen Kasir Modern</p>
            </div>

            <!-- Body -->
            <div class="card-body-custom">
                
                <!-- Alert Error -->
                <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Username atau password yang Anda masukkan salah</span>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form action="auth.php" method="POST">
                    
                    <!-- Username -->
                    <div class="input-group-custom">
                        <label class="input-label">Username</label>
                        <div class="input-field-wrapper">
                            <i class="fas fa-user-circle input-prefix"></i>
                            <input 
                                type="text" 
                                name="username" 
                                class="input-field" 
                                placeholder="Ketik username Anda" 
                                required 
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="input-group-custom">
                        <label class="input-label">Password</label>
                        <div class="input-field-wrapper">
                            <i class="fas fa-lock input-prefix"></i>
                            <input 
                                type="password" 
                                name="password" 
                                id="passField" 
                                class="input-field" 
                                placeholder="Ketik password Anda" 
                                required
                            >
                            <i class="fas fa-eye input-suffix" id="eyeToggle" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-submit">
                        Login Sekarang
                    </button>
                    
                </form>

            </div>

            <!-- Footer -->
            <div class="card-footer-custom">
                <p>&copy; 2025 Kasir Aristo. All rights reserved.</p>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const field = document.getElementById("passField");
            const eye = document.getElementById("eyeToggle");
            
            if (field.type === "password") {
                field.type = "text";
                eye.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                field.type = "password";
                eye.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

</body>
</html>