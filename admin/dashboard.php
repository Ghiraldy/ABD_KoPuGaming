<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/admin_auth.php';
require_admin();

// Ambil ringkasan
$totalProduk = (int)$pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$totalKategori = (int)$pdo->query("SELECT COUNT(*) FROM kategori_produk")->fetchColumn();
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Dashboard Admin</title>

  <!-- CSS Admin -->
  <link rel="stylesheet" href="<?php echo '/toko_online/public/css/admin.css'; ?>">
</head>
<body>

<!-- HEADER ADMIN -->
<div class="admin-header">
  <div class="container">
    <h2>Admin Panel</h2>
    <div class="admin-nav">
      <a href="dashboard.php">Dashboard</a>
      <a href="categories.php">Kategori</a>
      <a href="products.php">Produk</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</div>

<!-- MAIN CONTAINER -->
<div class="admin-container">

  <h1>Selamat Datang, Admin 👋</h1>

  <div class="dashboard-cards">
    <div class="dashboard-card">
      <h3>Total Produk</h3>
      <p><?php echo $totalProduk; ?></p>
    </div>

    <div class="dashboard-card">
      <h3>Total Kategori</h3>
      <p><?php echo $totalKategori; ?></p>
    </div>
  </div>

</div>

</body>
</html>
