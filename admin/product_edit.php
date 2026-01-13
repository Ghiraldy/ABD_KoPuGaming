<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/admin_auth.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk=?");
$stmt->execute([$id]);
$prod = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$prod) { die('Produk tidak ditemukan'); }

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama_produk']);
  $idkat = (int)($_POST['id_kategori'] ?? 0);
  $harga = (int)($_POST['harga'] ?? 0);
  $stok  = (int)($_POST['stok'] ?? 0);
  $desk  = trim($_POST['deskripsi'] ?? '');
  $gambar = $prod['gambar']; // tetap gambar lama jika tidak diganti

  // Upload gambar baru jika ada
  if (!empty($_FILES['gambar']['name'])) {
    [$ok, $res] = upload_image($_FILES['gambar']);
    if ($ok) {
      // Hapus gambar lama jika ada dan file-nya masih ada
      if (!empty($prod['gambar'])) {
        $old = __DIR__ . '/../public/assets/' . $prod['gambar'];
        if (is_file($old)) @unlink($old);
      }
      $gambar = $res;
    } else {
      $msg = $res;
    }
  }

  if ($nama && $idkat > 0 && $harga > 0 && $stok >= 0) {
    $q = $pdo->prepare("UPDATE produk SET id_kategori=?, nama_produk=?, harga=?, stok=?, deskripsi=?, gambar=? WHERE id_produk=?");
    $q->execute([$idkat, $nama, $harga, $stok, $desk, $gambar, $id]);
    $msg = '✅ Produk berhasil diperbarui';

    // Refresh data
    $stmt->execute([$id]);
    $prod = $stmt->fetch(PDO::FETCH_ASSOC);

  } else {
    $msg = $msg ?: '⚠️ Input tidak valid';
  }
}

$cats = $pdo->query("SELECT * FROM kategori_produk ORDER BY nama_kategori")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Produk</title>

  <!-- CSS Admin Modern -->
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

<div class="admin-container">

  <h1>Edit Produk ✏️</h1>

  <?php if($msg): ?>
    <div class="alert" style="margin:15px 0;background:#e1f5fe;padding:10px;border-left:4px solid #0a3d62;">
      <?php echo htmlspecialchars($msg); ?>
    </div>
  <?php endif; ?>

  <!-- FORM EDIT PRODUK -->
  <form method="post" enctype="multipart/form-data" class="admin-form">

    <label>Nama Produk</label>
    <input type="text" name="nama_produk" value="<?php echo htmlspecialchars($prod['nama_produk']); ?>" required>

    <label>Kategori</label>
    <select name="id_kategori" required>
      <?php foreach($cats as $c): ?>
        <option value="<?php echo $c['id_kategori']; ?>" <?php echo ($c['id_kategori']==$prod['id_kategori']?'selected':''); ?>>
          <?php echo htmlspecialchars($c['nama_kategori']); ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Harga</label>
    <input type="number" name="harga" min="0" value="<?php echo (int)$prod['harga']; ?>" required>

    <label>Stok</label>
    <input type="number" name="stok" min="0" value="<?php echo (int)$prod['stok']; ?>" required>

    <label>Deskripsi</label>
    <textarea name="deskripsi"><?php echo htmlspecialchars($prod['deskripsi']); ?></textarea>

    <label>Gambar Saat Ini</label>
    <div style="padding:8px;background:#fff;border-radius:6px;border:1px solid #ddd;margin-bottom:10px;">
      <?php echo htmlspecialchars($prod['gambar'] ?: '—'); ?>
    </div>

    <label>Ganti Gambar (opsional)</label>
    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif">

    <button type="submit">Simpan Perubahan ✅</button>
  </form>

</div>

</body>
</html>
