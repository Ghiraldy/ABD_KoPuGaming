<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/admin_auth.php';
require_admin();

$msg = '';

// Tambah produk (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_produk'])) {
  $nama = trim($_POST['nama_produk']);
  $idkat = (int)($_POST['id_kategori'] ?? 0);
  $harga = (int)($_POST['harga'] ?? 0);
  $stok  = (int)($_POST['stok'] ?? 0);
  $desk  = trim($_POST['deskripsi'] ?? '');
  $gambar = null;

  if (!empty($_FILES['gambar']['name'])) {
    [$ok, $res] = upload_image($_FILES['gambar']);
    if ($ok) $gambar = $res; else $msg = $res;
  }

  if ($nama && $idkat > 0 && $harga > 0 && $stok >= 0) {
    $stmt = $pdo->prepare("INSERT INTO produk(id_kategori, nama_produk, harga, stok, deskripsi, gambar) VALUES(?,?,?,?,?,?)");
    $stmt->execute([$idkat, $nama, $harga, $stok, $desk, $gambar]);
    $msg = '✅ Produk berhasil ditambahkan';
  } else {
    $msg = $msg ?: '⚠️ Input tidak valid';
  }
}

// Data untuk form
$cats = $pdo->query("SELECT * FROM kategori_produk ORDER BY nama_kategori")->fetchAll(PDO::FETCH_ASSOC);

// List produk
$produk = $pdo->query("SELECT p.*, k.nama_kategori 
                      FROM produk p 
                      JOIN kategori_produk k ON p.id_kategori=k.id_kategori 
                      ORDER BY p.id_produk DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kelola Produk</title>

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

  <h1>Kelola Produk 🛍️</h1>

  <?php if($msg): ?>
    <div class="alert" style="margin: 15px 0; background:#e1f5fe; padding:10px; border-left:4px solid #0a3d62;">
      <?php echo htmlspecialchars($msg); ?>
    </div>
  <?php endif; ?>

  <!-- FORM TAMBAH PRODUK -->
  <form method="post" enctype="multipart/form-data" class="admin-form">

    <label>Nama Produk</label>
    <input type="text" name="nama_produk" required>

    <label>Kategori</label>
    <select name="id_kategori" required>
      <option value="">--Pilih Kategori--</option>
      <?php foreach($cats as $c): ?>
        <option value="<?php echo $c['id_kategori']; ?>"><?php echo htmlspecialchars($c['nama_kategori']); ?></option>
      <?php endforeach; ?>
    </select>

    <label>Harga</label>
    <input type="number" name="harga" min="0" required>

    <label>Stok</label>
    <input type="number" name="stok" min="0" required>

    <label>Deskripsi</label>
    <textarea name="deskripsi"></textarea>

    <label>Gambar Produk</label>
    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.gif">

    <button type="submit">Tambah Produk</button>
  </form>

  <!-- TABEL PRODUK -->
  <table class="admin-table" style="margin-top:30px;">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Gambar</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($produk as $p): ?>
      <tr>
        <td><?php echo $p['id_produk']; ?></td>
        <td><?php echo htmlspecialchars($p['nama_produk']); ?></td>
        <td><?php echo htmlspecialchars($p['nama_kategori']); ?></td>
        <td>Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></td>
        <td><?php echo (int)$p['stok']; ?></td>
        <td><?php echo htmlspecialchars($p['gambar'] ?? '—'); ?></td>
        <td>
          <a class="btn-admin" href="product_edit.php?id=<?php echo $p['id_produk']; ?>">Edit</a>
          <a class="btn-admin btn-danger" href="product_delete.php?id=<?php echo $p['id_produk']; ?>" onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>

</body>
</html>
