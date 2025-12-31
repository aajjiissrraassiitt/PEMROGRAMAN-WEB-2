<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Dashboard';
include __DIR__ . '/../includes/template_karyawan.php';

$id = $_SESSION['user_id'];
$k = $pdo->prepare("SELECT k.*, j.nama_shift, j.jam_masuk, j.jam_pulang FROM karyawan k LEFT JOIN jam_kerja j ON k.id_jam_kerja=j.id WHERE k.id=:id");
$k->execute(['id'=>$id]); $me = $k->fetch();
$today = date('Y-m-d');
$abs = $pdo->prepare("SELECT * FROM absensi WHERE id_karyawan=:id AND tanggal=:t LIMIT 1");
$abs->execute(['id'=>$id,'t'=>$today]); $a = $abs->fetch();
?>
<p>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?></p>

<p>
    Jadwal kerja Anda:
    <?= htmlspecialchars(isset($me['nama_shift']) ? $me['nama_shift'] : '-') ?>
    (<?= htmlspecialchars(isset($me['jam_masuk']) ? $me['jam_masuk'] : '-') ?> -
    <?= htmlspecialchars(isset($me['jam_pulang']) ? $me['jam_pulang'] : '-') ?>)
</p>

<p>
    Status hari ini:
    <?= $a ? ((isset($a['jam_masuk']) && $a['jam_masuk']) ? 'Sudah Masuk' : 'Belum Absen') : 'Belum Absen' ?>
</p>

<?php include __DIR__ . '/../includes/template_karyawan_end.php'; ?>