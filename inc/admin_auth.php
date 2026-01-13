<?php
// inc/admin_auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

function admin_logged_in(): bool {
  return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function require_admin() {
  if (!admin_logged_in()) {
    header('Location: login.php');
    exit;
  }
}

function admin_login($username, $password): bool {
  // Ganti ke DB user kalau mau serius. Untuk tugas/awal: hardcoded
  if ($username === 'admin' && $password === 'admin123') {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_name'] = 'Administrator';
    return true;
  }
  return false;
}

function admin_logout() {
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
  }
  session_destroy();
}
