<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Dashboard';
include __DIR__ . '/../includes/template_admin.php';

// stats
$total_k = $pdo->query("SELECT COUNT(*) FROM karyawan")->fetchColumn();
$total_div = $pdo->query("SELECT COUNT(*) FROM divisi")->fetchColumn();
$today = date('Y-m-d');
$hadir = $pdo->prepare("SELECT COUNT(*) FROM absensi WHERE tanggal = :t AND jam_masuk IS NOT NULL");
$hadir->execute(['t'=>$today]); $hadir_count = $hadir->fetchColumn();
$telat = $pdo->prepare("SELECT COUNT(a.id) FROM absensi a JOIN karyawan k ON a.id_karyawan=k.id JOIN jam_kerja j ON k.id_jam_kerja=j.id WHERE a.tanggal=:t AND a.jam_masuk IS NOT NULL AND TIME(a.jam_masuk) > ADDTIME(j.jam_masuk, SEC_TO_TIME(j.toleransi_menit*60))");
$telat->execute(['t'=>$today]); $telat_count = $telat->fetchColumn();
?>
<div class="card">
  <p>Jumlah Karyawan: <strong><?=$total_k?></strong></p>
  <p>Jumlah Divisi: <strong><?=$total_div?></strong></p>
  <p>Hadir Hari Ini: <strong><?=$hadir_count?></strong></p>
  <p>Terlambat Hari Ini: <strong><?=$telat_count?></strong></p>
</div>
<?php include __DIR__ . '/../includes/template_admin_end.php'; ?>