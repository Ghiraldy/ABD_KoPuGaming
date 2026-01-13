<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { die("ID pesanan tidak valid."); }

$stmt = $pdo->prepare("
  SELECT p.id_pesanan, p.total_harga, p.status AS status_pesanan, 
         pel.nama_pelanggan,
         pm.status_pembayaran
  FROM pesanan p
  JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
  LEFT JOIN pembayaran pm ON p.id_pesanan = pm.id_pesanan
  WHERE p.id_pesanan = ?
");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) { die("Pesanan tidak ditemukan."); }

// Tentukan status pembayaran
if ($order['status_pembayaran'] === 'Lunas') {
    $statusText = "✅ Pembayaran Berhasil";
    $statusColor = "green";
} else {
    $statusText = "⏳ Menunggu Pembayaran";
    $statusColor = "#d35400";
}

require_once __DIR__ . '/../inc/header.php';
?>

<!-- PAGE CONTENT CENTERED -->
<div class="page-content" style="display:flex; justify-content:center; align-items:center; flex-direction:column;">

  <!-- CARD -->
  <div class="form-box" style="padding:28px; max-width:500px; width:100%; text-align:center;">

    <h2 style="color:#0a3d62;">🎉 Pesanan Berhasil Dibuat</h2>
    <p style="margin-top:10px; color:#535c68;">
      Terima kasih, <strong><?= e($order['nama_pelanggan']); ?></strong>.
    </p>

    <!-- INFO BOX -->
    <div style="background:#f0f4f8; padding:18px; border-radius:10px; margin:26px 0; text-align:left;">
      <p><strong>ID Pesanan:</strong> <?= $order['id_pesanan']; ?></p>
      <p><strong>Total Pembayaran:</strong> 
        <span style="color:#0097e6; font-weight:bold;">
          Rp <?= number_format($order['total_harga'], 0, ',', '.'); ?>
        </span>
      </p>
      <p><strong>Status Pembayaran:</strong> 
        <span style="font-weight:bold; color:<?= $statusColor; ?>;">
          <?= $statusText; ?>
        </span>
      </p>
      <p><strong>Status Pesanan:</strong> <?= $order['status_pesanan']; ?></p>
    </div>

    <!-- BUTTON -->
    <?php if ($order['status_pembayaran'] !== 'Lunas'): ?>
      <a class="btn" style="width:100%;" href="payment.php?id=<?= $order['id_pesanan']; ?>">
        💳 Lanjutkan Pembayaran
      </a>
    <?php else: ?>
      <p style="color:green;font-weight:bold;">
        ✅ Pembayaran telah dikonfirmasi.
      </p>
    <?php endif; ?>

  </div>

  <!-- RETURN BUTTON -->
  <a class="btn btn-center" style="width:100%; max-width:260px; margin-top:25px;" href="<?= base_url(); ?>">
  Kembali ke Beranda
  </a>


</div>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
