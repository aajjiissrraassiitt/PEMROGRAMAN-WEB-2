<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') header('Location: admin/dashboard.php');
    else header('Location: karyawan/dashboard.php');
}
$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login - Absensi</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="login-box">
  <h2>Login Absensi</h2>
  <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form action="proses_login.php" method="post">
    <label>Email atau Username</label>
    <input type="text" name="identity" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>