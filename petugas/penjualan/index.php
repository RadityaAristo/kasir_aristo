<?php 
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/kasir_aristo/main/connect.php';
if($_SESSION['status'] != "login") header("location:../../auth/login.php");

// Variabel aktif untuk sidebar
$current_dir = 'transaksi'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Penjualan - Kasir Aristo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'DM Sans', sans-serif; 
            background: linear-gradient(135deg, #f2f0f0 0%, #c8dcf7 100%);
            color: #212529;
        }
        
        .container-fluid {
            padding: 0;
        }
        
        .main-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .content-wrapper {
            flex: 1;
            padding: 60px;
        }
        
        /* Top Section */
        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .page-info h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 0.25rem;
        }
        
        .page-info span {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .search-wrapper {
            position: relative;
            width: 350px;
        }
        
        .search-wrapper input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .search-wrapper input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }
        
        /* Main Content Grid */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }
        
        /* Products Grid */
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.25rem;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            padding: 0.5rem;
        }
        
        .product-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .product-item:hover {
            border-color: #4361ee;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
            transform: translateY(-2px);
        }
        
        .product-code {
            font-size: 0.7rem;
            font-weight: 700;
            color: #4361ee;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .product-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
            line-height: 1.3;
            min-height: 38px;
        }
        
        .product-cost {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1rem;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stock-count {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .plus-icon {
            width: 32px;
            height: 32px;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .product-item:hover .plus-icon {
            background: #3651d4;
            transform: rotate(90deg);
        }
        
        /* Cart Sidebar */
        .cart-sidebar {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            position: sticky;
            top: 2rem;
            max-height: calc(100vh - 4rem);
            display: flex;
            flex-direction: column;
        }
        
        .cart-header {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .cart-header h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }
        
        .cart-content {
            padding: 1.75rem;
            flex: 1;
            overflow-y: auto;
        }
        
        .customer-details {
            margin-bottom: 1.5rem;
        }
        
        .input-box {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
        }
        
        .input-box:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .items-wrapper {
            margin-bottom: 1.5rem;
        }
        
        .cart-item-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 0.65rem;
        }
        
        .item-data {
            flex: 1;
        }
        
        .item-data h6 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #212529;
            margin: 0 0 0.15rem 0;
        }
        
        .item-data small {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        .qty-input {
            width: 45px;
            padding: 0.4rem;
            text-align: center;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .item-amount {
            font-weight: 700;
            font-size: 0.9rem;
            color: #212529;
            min-width: 85px;
            text-align: right;
        }
        
        .remove-icon {
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0.3rem;
            transition: all 0.2s;
        }
        
        .remove-icon:hover {
            color: #dc3545;
        }
        
        .no-items {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #adb5bd;
        }
        
        .no-items i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
        
        .no-items p {
            margin: 0;
            font-size: 0.9rem;
        }
        
        .separator {
            height: 1px;
            background: #dee2e6;
            margin: 1.5rem 0;
        }
        
        .payment-area {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
        
        .total-text {
            font-size: 0.8rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .total-price {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
            margin-bottom: 1.25rem;
        }
        
        .pay-input {
            width: 100%;
            padding: 0.85rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            font-family: 'DM Sans', sans-serif;
        }
        
        .pay-input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .change-row {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }
        
        .change-text {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .change-number {
            font-size: 1.1rem;
            font-weight: 700;
            color: #4361ee;
        }
        
        .submit-button {
            width: 100%;
            background: #4361ee;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .submit-button:hover:not(:disabled) {
            background: #3651d4;
        }
        
        .submit-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #dee2e6;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #ced4da;
        }
    </style>
</head>
<body>
    <div class="main-layout">
        <?php include '../../template/sidebar.php'; ?>
        
        <div class="content-wrapper">
            <!-- Top Bar -->
            <div class="top-section">
                <div class="page-info">
                    <h1>Transaksi Penjualan</h1>
                    <span>Kelola penjualan produk dengan cepat</span>
                </div>
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Cari produk..." onkeyup="cariProduk()">
                </div>
            </div>

            <!-- Main Grid -->
            <div class="main-content">
                <!-- Products -->
                <div class="products-container">
                    <?php 
                    $sql = mysqli_query($connect, "SELECT * FROM produk WHERE Stok > 0 ORDER BY ProdukID ASC");
                    while($p = mysqli_fetch_array($sql)){
                    ?>
                    <div class="product-item" onclick="tambahItem('<?= $p['ProdukID'] ?>', '<?= $p['NamaProduk'] ?>', '<?= $p['Harga'] ?>', '<?= $p['Stok'] ?>')">
                        <div class="product-code"># <?= $p['ProdukID'] ?></div>
                        <div class="product-title"><?= $p['NamaProduk'] ?></div>
                        <div class="product-cost">Rp <?= number_format($p['Harga'], 0, ',', '.') ?></div>
                        <div class="product-meta">
                            <span class="stock-count">Stok: <?= $p['Stok'] ?></span>
                            <button class="plus-icon" type="button">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Cart -->
                <div class="cart-sidebar">
                    <div class="cart-header">
                        <h3>Keranjang Belanja</h3>
                    </div>
                    
                    <div class="cart-content">
                        <form action="proses_simpan.php" method="POST" id="formTransaksi">
                            <!-- Customer -->
                            <div class="customer-details">
                                <input type="text" name="NamaPelanggan" class="input-box" placeholder="Nama Pelanggan" required>
                                <input type="text" name="NomorTelepon" class="input-box" placeholder="Nomor Telepon" required>
                                <textarea name="Alamat" class="input-box" rows="2" placeholder="Alamat" required></textarea>
                            </div>

                            <div class="separator"></div>

                            <!-- Items -->
                            <div class="items-wrapper" id="itemsArea">
                                <div class="no-items">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada item</p>
                                </div>
                            </div>

                            <div class="separator"></div>

                            <!-- Payment -->
                            <div class="payment-area">
                                <div class="total-text">Total Pembayaran</div>
                                <div class="total-price" id="totalHarga">Rp 0</div>
                                <input type="number" id="uangBayar" name="Bayar" class="pay-input" placeholder="Masukkan jumlah bayar" oninput="hitungKembalian()">
                                <div class="change-row">
                                    <span class="change-text">Kembalian</span>
                                    <span class="change-number" id="textKembalian">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="submit-button" id="btnBayar" disabled>
                                <i class="fas fa-check"></i>
                                PROSES TRANSAKSI
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let items = [];

        function tambahItem(id, nama, harga, stokMax) {
            let index = items.findIndex(i => i.id === id);
            if(index !== -1) {
                if(items[index].qty < stokMax) {
                    items[index].qty++;
                } else {
                    Swal.fire({
                        icon: 'warning', 
                        title: 'Stok Habis', 
                        text: 'Stok tersedia: ' + stokMax + ' unit',
                        confirmButtonColor: '#4361ee'
                    });
                    return;
                }
            } else {
                items.push({ id, nama, harga: parseInt(harga), qty: 1 });
            }
            renderTabel();
        }

        function hapusItem(index) {
            items.splice(index, 1);
            renderTabel();
        }

        function hitungKembalian() {
            let total = items.reduce((sum, item) => sum + (item.qty * item.harga), 0);
            let bayar = parseInt(document.getElementById('uangBayar').value) || 0;
            let kembalian = bayar - total;
            
            document.getElementById('textKembalian').innerText = 'Rp ' + (kembalian >= 0 ? kembalian.toLocaleString('id-ID') : 0);
            document.getElementById('btnBayar').disabled = (items.length === 0 || kembalian < 0 || bayar === 0);
        }

        function renderTabel() {
            let html = '';
            let grandTotal = 0;
            
            if(items.length === 0) {
                html = `
                    <div class="no-items">
                        <i class="fas fa-inbox"></i>
                        <p>Belum ada item</p>
                    </div>
                `;
            } else {
                items.forEach((item, i) => {
                    let subtotal = item.qty * item.harga;
                    grandTotal += subtotal;
                    html += `
                    <div class="cart-item-row">
                        <div class="item-data">
                            <h6>${item.nama}</h6>
                            <small>@ Rp ${item.harga.toLocaleString('id-ID')}</small>
                            <input type="hidden" name="ProdukID[]" value="${item.id}">
                        </div>
                        <input type="number" name="Jumlah[]" class="qty-input" value="${item.qty}" readonly>
                        <div class="item-amount">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        <button type="button" class="remove-icon" onclick="hapusItem(${i})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
                });
            }

            document.getElementById('itemsArea').innerHTML = html;
            document.getElementById('totalHarga').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            hitungKembalian();
        }

        function cariProduk() {
            let input = document.getElementById('searchInput').value.toLowerCase();
            let items = document.querySelectorAll('.product-item');

            items.forEach(item => {
                let title = item.querySelector('.product-title').innerText.toLowerCase();
                item.style.display = title.includes(input) ? '' : 'none';
            });
        }

        renderTabel();
    </script>
</body>
</html>