    <?php
    // Mengambil nama folder aktif untuk menentukan menu mana yang 'active'
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Sidebar */
        .sidebar-custom {
            background: #1e293b;
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            font-family: 'Inter', sans-serif;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .sidebar-header {
            padding: 25px 20px;
            background: #0f172a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            font-size: 1.5rem;
            color: #3b82f6;
        }

        .logo-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        /* Menu */
        .menu-wrapper {
            padding: 20px 0;
        }

        .nav-pills {
            list-style: none;
        }

        .nav-pills .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 4px 12px;
            border-radius: 8px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .nav-link i {
            font-size: 1.1rem;
            min-width: 24px;
            margin-right: 12px;
        }

        /* Hover */
        .nav-pills .nav-link:hover:not(.active) {
            background: #334155;
            color: #e2e8f0;
        }

        /* Active */
        .nav-pills .nav-link.active {
            background: #3b82f6;
            color: #fff;
        }

        /* Logout */
        .logout-container {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            padding: 0 12px;
        }

        .btn-logout-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            background: #dc2626;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-logout-custom:hover {
            background: #b91c1c;
            color: #fff;
        }

        .btn-logout-custom i {
            font-size: 1rem;
        }

        /* Spacer */
        .sidebar-spacer {
            width: 260px;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .sidebar-spacer {
                display: none !important;
            }
        }
    </style>

    <div class="sidebar-custom">
        <!-- Header -->
        <div class="sidebar-header">
            <div class="logo-wrapper">
                <i class="fas fa-cash-register logo-icon"></i>
                <span class="logo-text">KASIR ARISTO</span>
            </div>
        </div>

        <!-- Menu Navigationn -->
        <div class="menu-wrapper">
            <ul class="nav nav-pills flex-column">
                <li>
                    <a href="../dashboard/index.php" class="nav-link <?= ($current_dir == 'dashboard') ? 'active' : ''; ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../penjualan/index.php" class="nav-link <?= ($current_dir == 'penjualan') ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="../produk/index.php" class="nav-link <?= ($current_dir == 'produk') ? 'active' : ''; ?>">
                        <i class="fas fa-boxes"></i>
                        <span>Data Produk</span>
                    </a>
                </li>
                <li>
                    <a href="../pelanggan/index.php" class="nav-link <?= ($current_dir == 'pelanggan') ? 'active' : ''; ?>">
                        <i class="fas fa-user-tag"></i>
                        <span>Data Pelanggan</span>
                    </a>
                </li>
                <?php if($_SESSION['role'] == 'admin'): ?>
                <li>
                    <a href="../petugas/index.php" class="nav-link <?= ($current_dir == 'petugas') ? 'active' : ''; ?>">
                        <i class="fas fa-users-cog"></i>
                        <span>Petugas</span>
                    </a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="../laporan/index.php" class="nav-link <?= ($current_dir == 'laporan') ? 'active' : ''; ?>">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Button -->
        <div class="logout-container">
            <a href="../../auth/logout.php" class="btn-logout-custom" onclick="return confirm('Yakin ingin keluar?')">
                <i class="fas fa-power-off"></i>
                <span>Keluar</span>
            </a>
        </div>
    </div>

    <!-- Spacer -->
    <div class="sidebar-spacer d-none d-md-block"></div>