<?php
session_start();

function base_url($path = '') {
  return '/toko_online/public/' . ltrim($path, '/');
}

function e($v){ return htmlspecialchars($v, ENT_QUOTES); }

function get_cart(){
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
  return $_SESSION['cart'];
}
function add_to_cart($id_produk, $jumlah=1){
  $id = (int)$id_produk;
  $j = max(1, (int)$jumlah);
  if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 0;
  $_SESSION['cart'][$id] += $j;
}
function update_cart($id_produk, $jumlah){
  $id = (int)$id_produk;
  $j = max(1, (int)$jumlah);
  $_SESSION['cart'][$id] = $j;
}
function remove_from_cart($id_produk){
  $id = (int)$id_produk;
  if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
}
function clear_cart(){ $_SESSION['cart'] = []; }
function cart_count(){ return array_sum(get_cart()); }

function upload_image(array $file, array $allowed_ext = ['jpg','jpeg','png','gif'], int $max_mb = 5) {
  if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) return [false, 'Tidak ada file diupload'];
  $tmp = $file['tmp_name'];
  $name = $file['name'];
  $size = $file['size'];

  $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
  if (!in_array($ext, $allowed_ext)) return [false, 'Ekstensi tidak diizinkan'];

  $max_bytes = $max_mb * 1024 * 1024;
  if ($size > $max_bytes) return [false, 'Ukuran terlalu besar (maks '.$max_mb.'MB)'];

  // Validasi mime ringan
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $tmp);
  finfo_close($finfo);
  if (strpos($mime, 'image/') !== 0) return [false, 'File bukan gambar'];

  // Folder tujuan
  $targetDir = realpath(__DIR__ . '/../public/assets');
  if ($targetDir === false) $targetDir = __DIR__ . '/../public/assets';
  if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

  // Nama unik
  $basename = preg_replace('/[^a-z0-9\-]+/i', '-', pathinfo($name, PATHINFO_FILENAME));
  $unique = $basename . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $ext;
  $dest = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $unique;

  if (!move_uploaded_file($tmp, $dest)) return [false, 'Gagal memindahkan file'];

  return [true, $unique]; // hanya filename, disimpan di kolom 'gambar'
}