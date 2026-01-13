<?php
require_once __DIR__ . '/../inc/db.php';
require_once __DIR__ . '/../inc/admin_auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username'] ?? '');
  $p = trim($_POST['password'] ?? '');
  if (admin_login($u, $p)) {
    header('Location: dashboard.php'); exit;
  } else {
    $error = 'Username atau Password salah!';
  }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login Admin - Toko Online</title>
  <link rel="stylesheet" href="<?php echo '/toko_online/public/css/admin.css'; ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f1f4f9;">

<div class="admin-form" style="max-width:420px;width:100%;">

  <h2 style="text-align:center;color:#0a3d62;margin-bottom:20px;">
    <i class="fa-solid fa-user-shield"></i> Login Admin
  </h2>

  <?php if($error): ?>
  <div class="alert" style="background:#ffecec;border-left:4px solid #c0392b;padding:10px;margin-bottom:15px;">
    <?php echo $error; ?>
  </div>
  <?php endif; ?>

  <form method="post">
    <label>Username</label>
    <input type="text" name="username" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login Sekarang</button>
  </form>

</div>

</body>
</html>
