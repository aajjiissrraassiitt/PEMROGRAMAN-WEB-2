<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Karyawan - <?=isset($page_title)?htmlspecialchars($page_title):'Dashboard'?></title>
<link rel="stylesheet" href="/assets/style.css">
<style>
.topbar {display:flex;justify-content:space-between;align-items:center;padding:10px;background:#2980b9;color:#fff}
.content {padding:20px}
.menu-toggle {cursor:pointer;padding:8px;border-radius:4px;background:#2c3e50}
.navbar{display:flex;gap:10px}
</style>
</head>
<body>
<div class="topbar">
  <div>Absensi - Karyawan</div>
  <div>
    <span><?=htmlspecialchars($_SESSION['nama'] ?? '')?></span>
    <a href="../logout.php" class="menu-toggle">Logout</a>
  </div>
</div>
<div class="content">
<nav class="navbar">
  <a href="../karyawan/dashboard.php">Dashboard</a> |
  <a href="../karyawan/profile.php">Profile</a> |
  <a href="../karyawan/absen.php">Absen</a> |
  <a href="../karyawan/absenku.php">Absenku</a> |
  <a href="../logout.php">Logout</a>
</nav>
<hr>
<?php if (isset($page_title)) echo '<h2>'.htmlspecialchars($page_title).'</h2>'; ?>