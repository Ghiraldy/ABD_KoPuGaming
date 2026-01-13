<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

$cart = $_SESSION['cart'] ?? [];

if (isset($_POST['id_produk'])) {
  $id = (int)$_POST['id_produk'];
  $qty = (int)($_POST['qty'] ?? 1);
  $cart[$id] = ($cart[$id] ?? 0) + $qty;
  $_SESSION['cart'] = $cart;
  header("Location: cart.php"); exit;
}

if (isset($_GET['remove'])) {
  unset($cart[(int)$_GET['remove']]);
  $_SESSION['cart'] = $cart;
}

if ($cart) {
  $ids = implode(",", array_keys($cart));
  $produk = $pdo->query("SELECT * FROM produk WHERE id_produk IN ($ids)")->fetchAll(PDO::FETCH_UNIQUE);
}

require_once __DIR__ . '/../inc/header.php';
?>

<div class="page-content">

  <h2>🛒 Keranjang Belanja</h2>

  <?php if (!$cart): ?>
      <p>Keranjang masih kosong.</p>
      <a class="btn" href="<?php echo base_url(); ?>">Belanja Sekarang</a>

  <?php else: ?>

  <div class="table-box">
    <table class="cart-table">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Total</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>

      <?php $grand = 0; foreach ($cart as $id => $qty): 
        $p = $produk[$id];
        $sub = $p['harga'] * $qty;
        $grand += $sub;
      ?>
      <tr>
        <td><?php echo htmlspecialchars($p['nama_produk']); ?></td>

        <td>Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></td>

        <td><?php echo $qty; ?></td>

        <td>Rp <?php echo number_format($sub, 0, ',', '.'); ?></td>

        <td>
          <a href="?remove=<?php echo $id; ?>" class="btn-outline-danger"
             onclick="return confirm('Hapus produk ini dari keranjang?');">
            Hapus
          </a>
        </td>
      </tr>
      <?php endforeach; ?>

      <tr>
        <th colspan="3">Grand Total</th>
        <th colspan="2">Rp <?php echo number_format($grand, 0, ',', '.'); ?></th>
      </tr>

      </tbody>
    </table>

    <div style="margin-top:20px;">
      <a class="btn" href="checkout.php">Lanjutkan Checkout</a>
    </div>
  </div>

  <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
