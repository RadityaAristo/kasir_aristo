<?php 
session_start();
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';

$tgl_hari_ini = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap');

        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg: #f5f7fa;
            --card-bg: #ffffff;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            min-height: 100vh;
            color: var(--text-primary);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }

        .header-compact {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .greeting-section h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .greeting-section p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin: 0;
        }

        .user-info-compact {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 2px solid var(--primary);
        }

        .user-details h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .stats-horizontal {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card-horizontal {
            background: var(--card-bg);
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .stat-card-horizontal:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-card-1 .stat-icon-circle {
            background: #dcfce7;
            color: #059669;
        }

        .stat-card-2 .stat-icon-circle {
            background: #dbeafe;
            color: #0284c7;
        }

        .stat-card-3 .stat-icon-circle {
            background: #f5e0ff;
            color: #7c3aed;
        }

        .stat-content h3 {
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            letter-spacing: 0.3px;
        }

        .stat-content .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-content .unit {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .quick-actions-header {
            margin-bottom: 1rem;
        }

        .quick-actions-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-actions-sidebar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .quick-actions-sidebar .info-card {
            grid-column: 1 / -1;
        }

        .quick-action-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            color: inherit;
        }

        .quick-action-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .quick-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .quick-action-card:nth-child(1) .quick-action-icon {
            background: #dbeafe;
            color: var(--primary);
        }

        .quick-action-card:nth-child(2) .quick-action-icon {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .quick-action-card:nth-child(3) .quick-action-icon {
            background: #cffafe;
            color: #0891b2;
        }

        .quick-action-card:nth-child(4) .quick-action-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .quick-action-text h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .quick-action-text p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .info-card {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border-radius: 8px;
            padding: 1rem;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .info-card-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .info-card h4 {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        .time-badge {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @media (max-width: 1200px) {
            .quick-actions-sidebar {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .header-compact {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-horizontal {
                grid-template-columns: 1fr;
            }

            .quick-actions-sidebar {
                grid-template-columns: repeat(2, 1fr);
            }

            .greeting-section h1 {
                font-size: 1.25rem;
            }

            .quick-actions-sidebar .info-card {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 480px) {
            .quick-actions-sidebar {
                grid-template-columns: 1fr;
            }

            .info-card-content {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include '../../template/sidebar.php'; ?>

    <div class="container-fluid p-4 p-lg-5" style="position: relative; z-index: 1;">
        
        <!-- NEW: Compact Header -->
        <div class="header-compact">
            <div class="greeting-section">
                <h1>Dashboard Admin 👋</h1>
                <p>Kelola sistem kasir dengan aman,mudah dan efisien</p>
            </div>
            <div class="user-info-compact">
                <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username']; ?>&background=2563eb&color=fff&size=84&bold=true" 
                     class="user-avatar-small" alt="Avatar">
                <div class="user-details">
                    <h4><?= $_SESSION['username']; ?></h4>
                    </div>
            </div>
        </div>

        <!-- NEW: Horizontal Stats Cards -->
        <div class="stats-horizontal">
            <div class="stat-card-horizontal stat-card-1">
                <div class="stat-icon-circle">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>Omzet Hari Ini</h3>
                    <?php 
                        $q_omset = mysqli_query($connect, "SELECT SUM(TotalHarga) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                        $d_omset = mysqli_fetch_assoc($q_omset);
                    ?>
                    <div class="value">Rp <?= number_format($d_omset['total'] ?? 0); ?></div>
                </div>
            </div>

            <div class="stat-card-horizontal stat-card-2">
                <div class="stat-icon-circle">
                    <i class="fas fa-box-open"></i>
                </div>
                <div class="stat-content">
                    <h3>Barang Terjual</h3>
                    <?php 
                        $q_pdk = mysqli_query($connect, "SELECT SUM(JumlahProduk) as terjual FROM detailpenjualan 
                                                      JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID 
                                                      WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
                        $d_pdk = mysqli_fetch_assoc($q_pdk);
                    ?>
                    <div class="value"><?= number_format($d_pdk['terjual'] ?? 0); ?> <span class="unit">Unit</span></div>
                </div>
            </div>

            <div class="stat-card-horizontal stat-card-3">
                <div class="stat-icon-circle">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Pelanggan</h3>
                    <?php 
                        $q_plg = mysqli_query($connect, "SELECT COUNT(*) as total FROM pelanggan");
                    ?>
                    <div class="value"><?= $q_plg ? mysqli_fetch_assoc($q_plg)['total'] : 0; ?> <span class="unit">Member</span></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section Header -->
        <div class="quick-actions-header section-header-new">
            <h2>
                <div class="section-icon">
                    <i class="fas fa-lightning-bolt"></i>
                </div>
                Akses Cepat
            </h2>
        </div>

        <!-- Quick Actions Grid -->
        <div class="quick-actions-sidebar">
            <a href="../penjualan/index.php" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div class="quick-action-text">
                    <h4>Penjualan</h4>
                    <p>Kelola transaksi</p>
                </div>
            </a>

            <a href="../produk/index.php" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="quick-action-text">
                    <h4>Produk</h4>
                    <p>Manajemen stok</p>
                </div>
            </a>

            <a href="../laporan/index.php" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="quick-action-text">
                    <h4>Laporan</h4>
                    <p>Analisis data</p>
                </div>
            </a>

            <a href="../petugas/index.php" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="quick-action-text">
                    <h4>Petugas</h4>
                    <p>Kelola akun</p>
                </div>
            </a>

            <!-- Info Card -->
            <div class="info-card">
                <div class="info-card-content">
                    <h4>
                        <i class="far fa-calendar-alt"></i>
                        Waktu Saat Ini
                    </h4>
                    <p><?= date('l, d F Y'); ?></p>
                    <div class="time-badge">
                        <i class="far fa-clock me-2"></i><?= date('H:i'); ?> WIB
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Counter animation
function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= end) {
            element.textContent = end.toLocaleString('id-ID');
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current).toLocaleString('id-ID');
        }
    }, 16);
}

window.addEventListener('load', () => {
    document.querySelectorAll('.stat-content .value').forEach(stat => {
        const text = stat.textContent.trim();
        const match = text.match(/[\d.,]+/);
        if (match) {
            const value = parseInt(match[0].replace(/\D/g, ''));
            if (!isNaN(value) && value > 0) {
                const originalHTML = stat.innerHTML;
                stat.textContent = '0';
                setTimeout(() => {
                    animateValue(stat, 0, value, 1200);
                    setTimeout(() => {
                        stat.innerHTML = originalHTML.replace(/[\d.,]+/, value.toLocaleString('id-ID'));
                    }, 1200);
                }, 300);
            }
        }
    });
});

// Update time
function updateTime() {
    const timeElement = document.querySelector('.time-badge');
    if (timeElement) {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        timeElement.innerHTML = `<i class="far fa-clock me-2"></i>${timeStr} WIB`;
    }
}

setInterval(updateTime, 60000);
</script>

</body>
</html>