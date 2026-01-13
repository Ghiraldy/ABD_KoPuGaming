<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/admin_auth.php';
require_admin();

// Tambah kategori
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama_kategori']);
  $desk = trim($_POST['deskripsi'] ?? '');
  if ($nama !== '') {
    $stmt = $pdo->prepare("INSERT INTO kategori_produk(nama_kategori, deskripsi) VALUES(?, ?)");
    $stmt->execute([$nama, $desk]);
    $msg = '✅ Kategori berhasil ditambahkan';
  } else {
    $msg = '⚠️ Nama kategori tidak boleh kosong';
  }
}

// Hapus kategori
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $used = $pdo->prepare("SELECT COUNT(*) FROM produk WHERE id_kategori=?");
  $used->execute([$id]);
  
  if ($used->fetchColumn() > 0) {
    $msg = '⚠️ Kategori sedang digunakan di produk';
  } else {
    $pdo->prepare("DELETE FROM kategori_produk WHERE id_kategori=?")->execute([$id]);
    $msg = '✅ Kategori dihapus';
  }
}

// Ambil kategori
$cats = $pdo->query("SELECT * FROM kategori_produk ORDER BY id_kategori DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kelola Kategori</title>

<link rel="stylesheet" href="<?php echo '/toko_online/public/css/admin.css'; ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

<h1>Kelola Kategori 📂</h1>

<?php if($msg): ?>
<div class="alert" style="margin:15px 0;background:#e1f5fe;padding:10px;border-left:4px solid #0a3d62;">
  <?php echo $msg; ?>
</div>
<?php endif; ?>

<!-- FORM -->
<form method="post" class="admin-form">

  <label>Nama Kategori</label>
  <input type="text" name="nama_kategori" required>

  <label>Deskripsi</label>
  <textarea name="deskripsi"></textarea>

  <button type="submit">Tambah Kategori</button>
</form>

<!-- TABLE -->
<table class="admin-table" style="margin-top:30px;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama Kategori</th>
      <th>Deskripsi</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($cats as $c): ?>
    <tr>
      <td><?php echo $c['id_kategori']; ?></td>
      <td><?php echo htmlspecialchars($c['nama_kategori']); ?></td>
      <td><?php echo htmlspecialchars($c['deskripsi']); ?></td>
      <td>
        <a class="btn-admin btn-danger" href="?delete=<?php echo $c['id_kategori']; ?>" onclick="return confirm('Yakin ingin menghapus?')">
          Hapus
        </a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

</div>

</body>
</html>
