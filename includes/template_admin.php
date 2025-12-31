<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - <?=isset($page_title)?htmlspecialchars($page_title):'Dashboard'?></title>
<link rel="stylesheet" href="../assets/style.css">
<style>
/* simple admin menu */
.topbar {display:flex;justify-content:space-between;align-items:center;padding:10px;background:#2c3e50;color:#fff}
.menu-toggle {cursor:pointer;padding:8px;border-radius:4px;background:#34495e}
.sidebar {width:220px;background:#ecf0f1;padding:10px;float:left;height:calc(100vh - 50px)}
.content {margin-left:240px;padding:20px}
a.nav {display:block;padding:8px;color:#333;text-decoration:none;margin-bottom:4px}
a.nav:hover{background:#ddd}
</style>
</head>
<body>
<div class="topbar">
  <div>Absensi - Admin</div>
  <div>
    <span><?=htmlspecialchars($_SESSION['nama'] ?? '')?></span>
    <a href="../logout.php" class="menu-toggle">Logout</a>
  </div>
</div>
<div class="sidebar">
  <a class="nav" href="../admin/dashboard.php">Dashboard</a>
  <a class="nav" href="../admin/profile.php">Profile</a>
  <a class="nav" href="../admin/jam_kerja.php">Jam Kerja</a>
  <a class="nav" href="../admin/divisi.php">Divisi</a>
  <a class="nav" href="../admin/karyawan.php">Karyawan</a>
  <a class="nav" href="../admin/absensi.php">Absensi</a>
  <a class="nav" href="../logout.php">Logout</a>
</div>
<div class="content">
<?php if (isset($page_title)) echo '<h2>'.htmlspecialchars($page_title).'</h2>'; ?>
<?php // page content starts here ?>