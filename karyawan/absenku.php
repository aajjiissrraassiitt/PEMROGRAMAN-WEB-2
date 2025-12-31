<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Absenku';
include __DIR__ . '/../includes/template_karyawan.php';
$id = $_SESSION['user_id'];
$rows = $pdo->prepare("SELECT * FROM absensi WHERE id_karyawan=:id ORDER BY tanggal DESC");
$rows->execute(['id'=>$id]); $data = $rows->fetchAll();
?>
<table>
<tr><th>Tanggal</th><th>Jam Masuk</th><th>Foto Masuk</th><th>Jam Pulang</th><th>Foto Pulang</th><th>Status</th></tr>
<?php foreach($data as $r): ?>
<tr>
<td><?=$r['tanggal']?></td>
<td><?=$r['jam_masuk']?></td>
<td><?php if($r['foto_masuk']): ?><a href="/<?=$r['foto_masuk']?>" target="_blank">Lihat</a><?php endif;?></td>
<td><?=$r['jam_pulang']?></td>
<td><?php if($r['foto_pulang']): ?><a href="/<?=$r['foto_pulang']?>" target="_blank">Lihat</a><?php endif;?></td>
<td><?=$r['status']?></td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/template_karyawan_end.php'; ?>