<?php 
session_start();
if($_SESSION['status'] != "login"){
    header("location:../../auth/login.php?pesan=belum_login");
}
include '../../main/connect.php';

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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
            --secondary: #8b5cf6;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            
            --bg-main: #ffffff;
            --bg-secondary: #f8fafc;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body { 
            background: linear-gradient(135deg, #cac9c9 0%, #c8dcf7 100%);
            min-height: 100vh;
            color: var(--text-primary); 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            letter-spacing: -0.01em;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(236, 72, 153, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        /* NEW: Compact Header card */
        .header-compact {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .greeting-section h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            letter-spacing: -0.02em;
        }

        .greeting-section p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin: 0;
        }

        .user-info-compact {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--gray-50);
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
        }

        .user-avatar-small {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            border: 2px solid var(--primary);
        }

        .user-details h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .user-badge {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* NEW: Stats in Horizontal Cards */
        .stats-horizontal {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card-horizontal {
            background: white;
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .stat-card-horizontal:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-card-1 .stat-icon-circle {
            background: linear-gradient(135deg, #dcfce7, #86efac);
            color: #059669;
        }

        .stat-card-2 .stat-icon-circle {
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            color: #2563eb;
        }

        .stat-card-3 .stat-icon-circle {
            background: linear-gradient(135deg, #fae8ff, #f0abfc);
            color: #c026d3;
        }

        .stat-content h3 {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        .stat-content .value {
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-content .unit {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* NEW: Side by Side Layout */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Quick Actions Sidebar */
        .quick-actions-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .quick-action-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .quick-action-card:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }

        .quick-action-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .quick-action-card:nth-child(1) .quick-action-icon {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: var(--primary);
        }

        .quick-action-card:nth-child(2) .quick-action-icon {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            color: var(--secondary);
        }

        .quick-action-card:nth-child(3) .quick-action-icon {
            background: linear-gradient(135deg, #cffafe, #a5f3fc);
            color: var(--accent);
        }

        .quick-action-card:nth-child(4) .quick-action-icon {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: var(--warning);
        }

        .quick-action-text h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
        }

        .quick-action-text p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin: 0;
        }

        /* Products Table */
        .products-section {
            background: white;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .section-header-new {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            background: linear-gradient(to bottom, var(--gray-50), white);
        }

        .section-header-new h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .table-modern {
            margin: 0;
        }

        .table-modern thead th {
            background: transparent;
            border: none;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            padding: 1rem 1.5rem;
        }

        .table-modern tbody tr {
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.2s ease;
        }

        .table-modern tbody tr:last-child {
            border-bottom: none;
        }

        .table-modern tbody tr:hover {
            background: var(--gray-50);
        }

        .table-modern tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border: none;
            color: var(--text-primary);
        }

        .rank-badge {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .product-name-text {
            font-weight: 600;
            color: var(--text-primary);
            margin-left: 0.75rem;
        }

        .quantity-badge {
            background: var(--gray-100);
            color: var(--primary);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
            border: 1px solid var(--gray-200);
        }

        .revenue-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: var(--success);
        }

        /* Info Card */
        .info-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            color: white;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .info-card-content {
            position: relative;
            z-index: 1;
        }

        .info-card h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.8);
            margin: 0;
        }

        .time-badge {
            background: rgba(255,255,255,0.15);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            margin-top: 0.75rem;
            display: inline-block;
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

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

        .header-compact {
            animation: fadeInUp 0.5s ease-out;
        }

        .stat-card-horizontal {
            animation: fadeInUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        .stat-card-horizontal:nth-child(1) { animation-delay: 0.1s; }
        .stat-card-horizontal:nth-child(2) { animation-delay: 0.2s; }
        .stat-card-horizontal:nth-child(3) { animation-delay: 0.3s; }

        .products-section {
            animation: slideInLeft 0.6s ease-out 0.3s both;
        }

        .quick-actions-sidebar {
            animation: slideInRight 0.6s ease-out 0.3s both;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }

            .quick-actions-sidebar {
                grid-template-columns: repeat(2, 1fr);
                display: grid;
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
                grid-template-columns: 1fr;
            }

            .greeting-section h1 {
                font-size: 1.5rem;
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
                <p>Kelola sistem kasir dengan mudah dan efisien</p>
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
                        $q_omset = mysqli_query($conn, "SELECT SUM(TotalHarga) as total FROM penjualan WHERE TanggalPenjualan LIKE '$tgl_hari_ini%'");
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
                        $q_pdk = mysqli_query($conn, "SELECT SUM(JumlahProduk) as terjual FROM detailpenjualan 
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
                        $q_plg = mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan");
                    ?>
                    <div class="value"><?= $q_plg ? mysqli_fetch_assoc($q_plg)['total'] : 0; ?> <span class="unit">Member</span></div>
                </div>
            </div>
        </div>

        <!-- NEW: Side by Side Layout -->
        <div class="content-wrapper">
            <!-- Left: Best Selling Products -->
            <div class="products-section">
                <div class="section-header-new">
                    <h2>
                        <div class="section-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        Produk Terlaris Minggu Ini
                    </h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-end">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $best = mysqli_query($conn, "SELECT NamaProduk, SUM(JumlahProduk) as total, SUM(Subtotal) as uang 
                                                        FROM detailpenjualan JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                        GROUP BY detailpenjualan.ProdukID ORDER BY total DESC LIMIT 5");
                            $rank = 1;
                            while($b = mysqli_fetch_assoc($best)): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="rank-badge"><?= $rank++; ?></span>
                                        <span class="product-name-text"><?= $b['NamaProduk']; ?></span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="quantity-badge"><?= number_format($b['total']); ?> Unit</span>
                                </td>
                                <td class="text-end">
                                    <span class="revenue-text">Rp <?= number_format($b['uang']); ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Quick Actions Sidebar -->
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