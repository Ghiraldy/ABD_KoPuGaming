<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header("Location: index.php"); exit; }

$ids = implode(",", array_keys($cart));
$produk = $pdo->query("SELECT * FROM produk WHERE id_produk IN ($ids)")->fetchAll(PDO::FETCH_UNIQUE);
?>

<?php require_once __DIR__ . '/../inc/header.php'; ?>

<div class="page-content">

  <h2>🧾 Konfirmasi Pesanan</h2>

  <div class="table-box">
    <table class="cart-table">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>

      <?php $grand = 0; foreach($cart as $id => $qty):
          $p = $produk[$id];
          $sub = $p['harga'] * $qty;
          $grand += $sub;
      ?>
      <tr>
        <td><?php echo htmlspecialchars($p['nama_produk']); ?></td>
        <td><?php echo $qty; ?></td>
        <td>Rp <?php echo number_format($sub, 0, ',', '.'); ?></td>
      </tr>
      <?php endforeach; ?>

      <tr>
        <th colspan="2">Total</th>
        <th>Rp <?php echo number_format($grand, 0, ',', '.'); ?></th>
      </tr>

      </tbody>
    </table>
  </div>

  <br><br>

  <h3>📝 Informasi Pembeli</h3>

  <form action="<?php echo base_url('process_order.php'); ?>" method="post" class="form-box">

    <label>Nama Lengkap</label>
    <input type="text" name="nama" required>

    <label>Alamat Lengkap</label>
    <textarea name="alamat" required style="width:100%;padding:12px;border-radius:8px;border:1px solid #ccc;min-height:100px;"></textarea>

    <label>Nomor Telepon / WhatsApp</label>
    <input type="text" name="telepon" required>

    <br>
    <button class="btn" type="submit">Selesaikan Pesanan</button>
  </form>

</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
