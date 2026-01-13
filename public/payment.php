<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { die("ID pesanan tidak valid."); }

$stmt = $pdo->prepare("SELECT p.total_harga, pel.nama_pelanggan, pel.no_hp 
                       FROM pesanan p
                       JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
                       WHERE p.id_pesanan = ?");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) { die("Pesanan tidak ditemukan."); }

require_once __DIR__ . '/../inc/header.php';
?>

<div class="page-content" style="max-width:700px; margin:auto; text-align:center;">

  <div class="form-box" style="padding:32px;">

    <h2 style="color:#0a3d62; margin-bottom:10px;">💳 Pembayaran Pesanan</h2>
    <p style="color:#636e72; margin-bottom:22px;">
      Silahkan lakukan pembayaran untuk menyelesaikan pesanan Anda.
    </p>

    <div style="background:#f0f4f8; padding:18px; border-radius:10px; margin-bottom:28px; text-align:center;">
      <p><strong>Nama Pembeli:</strong> <?= e($order['nama_pelanggan']); ?></p>
      <p><strong>Total Pembayaran:</strong><br>
        <span style="color:#0097e6; font-weight:bold; font-size:19px;">
          Rp <?= number_format($order['total_harga'],0,',','.'); ?>
        </span>
      </p>
    </div>

    <h3 style="color:#0a3d62; margin-bottom:10px;">Metode Pembayaran</h3>

    <div style="background:white; padding:20px; border-radius:10px; border:1px solid #dcdde1; text-align:center;">
      <p style="margin-bottom:12px;">Silahkan pilih metode pembayaran:</p>

      <button class="btn" onclick="openQRIS()" style="width:100%; margin-bottom:18px;">💠 Tampilkan QRIS</button>

      <p><strong>Transfer Bank BCA:</strong><br> 123456789 a.n <strong>Gaming Store</strong></p>
      <p style="margin-top:12px;"><strong>OVO / DANA / GOPAY:</strong><br> <?= e($order['no_hp']); ?></p>
    </div>

    <form action="payment_process.php" method="post" style="margin-top:30px;">
      <input type="hidden" name="id_pesanan" value="<?= $id ?>">
      <button class="btn" type="submit" style="width:100%; font-size:16px;">
        ✅ Saya Sudah Membayar
      </button>
    </form>

    <!-- Tombol Kembali -->
    <a href="checkout_success.php?id=<?= $id ?>" 
       class="btn" 
       style="margin-top:12px; width:200px; display:block; text-align:center;">
       ← Kembali
    </a>

  </div>

</div>

<!-- MODAL QRIS -->
<div id="qrisModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6); backdrop-filter:blur(4px); justify-content:center; align-items:center;">
  <div style="background:#fff; padding:25px; border-radius:12px; text-align:center; max-width:350px;">
    <h3 style="margin-bottom:12px; color:#0a3d62;">Scan QRIS</h3>
    <img src="<?= base_url('assets/qris.jpg'); ?>" alt="QRIS" style="width:100%; border-radius:8px;">
    <button class="btn" onclick="closeQRIS()" style="margin-top:18px; width:100%;">Tutup</button>
  </div>
</div>

<script>
function openQRIS(){ document.getElementById('qrisModal').style.display='flex'; }
function closeQRIS(){ document.getElementById('qrisModal').style.display='none'; }
</script>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
