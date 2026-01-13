<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/admin_auth.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  // Ambil dulu untuk hapus file gambar
  $q = $pdo->prepare("SELECT gambar FROM produk WHERE id_produk=?");
  $q->execute([$id]);
  $row = $q->fetch(PDO::FETCH_ASSOC);

  // Hapus produk
  $stmt = $pdo->prepare("DELETE FROM produk WHERE id_produk=?");
  $stmt->execute([$id]);

  // Hapus file gambar
  if (!empty($row['gambar'])) {
    $path = __DIR__ . '/../public/assets/' . $row['gambar'];
    if (is_file($path)) @unlink($path);
  }
}
header('Location: products.php');
