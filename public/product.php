<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, k.nama_kategori 
                       FROM produk p 
                       JOIN kategori_produk k ON p.id_kategori = k.id_kategori
                       WHERE id_produk=?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) { die("Produk tidak ditemukan"); }
?>

<?php require_once __DIR__ . '/../inc/header.php'; ?>

<div class="container">

  <div class="product-card" style="flex-direction:row; border-left-width:0;">

    <div class="product-image" style="flex:0 0 260px; height:260px;">
      <img src="assets/<?php echo $p['gambar'] ?: 'no-image.png'; ?>" alt="">
    </div>

    <div class="product-info">
      <h3><?php echo htmlspecialchars($p['nama_produk']); ?></h3>
      <p>Kategori: <strong><?php echo htmlspecialchars($p['nama_kategori']); ?></strong></p>
      <p class="price">Rp <?php echo number_format($p['harga'],0,',','.'); ?></p>
      <p><?php echo nl2br(htmlspecialchars($p['deskripsi'])); ?></p>

      <form action="<?php echo base_url('cart.php'); ?>" method="post" style="margin-top:18px;">
        <input type="hidden" name="id_produk" value="<?php echo $p['id_produk']; ?>">

        <label>Jumlah:</label>

        <div class="qty-box">
          <button type="button" class="qty-btn" onclick="decreaseQty()">−</button>
          <input type="text" id="qtyInput" name="qty" value="1" readonly>
          <button type="button" class="qty-btn" onclick="increaseQty()">+</button>
        </div>

        <button class="btn" type="submit" style="margin-top:15px;">Tambah ke Keranjang</button>
      </form>

    </div>
  </div>
</div>

<script>
function decreaseQty() {
  let qty = document.getElementById('qtyInput');
  if (parseInt(qty.value) > 1) qty.value = parseInt(qty.value) - 1;
}

function increaseQty() {
  let qty = document.getElementById('qtyInput');
  let max = <?php echo $p['stok']; ?>;
  if (parseInt(qty.value) < max) qty.value = parseInt(qty.value) + 1;
}
</script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
