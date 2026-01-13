<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/header.php';

// Ambil data produk dari database
try {
    $stmt = $pdo->query("
        SELECT p.*, k.nama_kategori 
        FROM produk p
        JOIN kategori_produk k ON p.id_kategori = k.id_kategori
        ORDER BY p.id_produk DESC
    ");
    $produkList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $produkList = [];
}
?>

<div class="container">

  <h2 style="margin-bottom:20px;">🛍️ Produk Tersedia</h2>

  <?php if (empty($produkList)): ?>
      <p>Belum ada produk untuk saat ini.</p>
  <?php else: ?>

  <div class="products">

    <?php foreach ($produkList as $p): ?>
      <div class="product-card">

        <div class="product-image">
          <img src="assets/<?php echo $p['gambar'] ?: 'no-image.png'; ?>" alt="">
        </div>

        <div class="product-info">
          <h3><?php echo htmlspecialchars($p['nama_produk']); ?></h3>
          <p>Kategori: <?php echo htmlspecialchars($p['nama_kategori']); ?></p>
          <p class="price">Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></p>

          <a class="btn" href="<?php echo base_url('product.php?id=' . $p['id_produk']); ?>">
            Lihat Detail
          </a>
        </div>

      </div>
    <?php endforeach; ?>

  </div>

  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
