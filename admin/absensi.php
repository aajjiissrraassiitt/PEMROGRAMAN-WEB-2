<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Rekap Absensi';
include __DIR__ . '/../includes/template_admin.php';

$where = [];
$params = [];
if (!empty($_GET['tanggal'])) { $where[] = 'a.tanggal = :t'; $params['t'] = $_GET['tanggal']; }
if (!empty($_GET['divisi'])) { $where[] = 'k.id_divisi = :div'; $params['div'] = $_GET['divisi']; }
if (!empty($_GET['nama'])) { $where[] = 'k.nama LIKE :nama'; $params['nama'] = '%'.$_GET['nama'].'%'; }

$sql = "SELECT a.*, k.nama as nama_karyawan, d.nama as divisi FROM absensi a JOIN karyawan k ON a.id_karyawan=k.id LEFT JOIN divisi d ON k.id_divisi=d.id";
if ($where) $sql .= ' WHERE '.implode(' AND ',$where);
$sql .= ' ORDER BY a.tanggal DESC, a.jam_masuk DESC';
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$rows = $stmt->fetchAll();
$divs = $pdo->query("SELECT * FROM divisi")->fetchAll();
?>
<form method="get">
  <input type="date" name="tanggal" value="<?=htmlspecialchars($_GET['tanggal'] ?? '')?>">
  <select name="divisi">
    <option value="">Semua Divisi</option>
    <?php foreach($divs as $d): ?><option value="<?=$d['id']?>" <?=isset($_GET['divisi']) && $_GET['divisi']==$d['id']?'selected':''?>><?=$d['nama']?></option><?php endforeach; ?>
  </select>
  <input name="nama" placeholder="Nama" value="<?=htmlspecialchars($_GET['nama'] ?? '')?>">
  <button type="submit">Filter</button>
</form>

<table>
<tr><th>Tanggal</th><th>Nama</th><th>Divisi</th><th>Jam Masuk</th><th>Foto Masuk</th><th>Jam Pulang</th><th>Foto Pulang</th><th>Status</th></tr>
<?php foreach($rows as $r): ?>
<tr>
<td><?=$r['tanggal']?></td>
<td><?=htmlspecialchars($r['nama_karyawan'])?></td>
<td><?=htmlspecialchars($r['divisi'])?></td>
<td><?=$r['jam_masuk']?></td>
<td><?php if($r['foto_masuk']): ?><a href="/<?=$r['foto_masuk']?>" target="_blank">Lihat</a><?php endif;?></td>
<td><?=$r['jam_pulang']?></td>
<td><?php if($r['foto_pulang']): ?><a href="/<?=$r['foto_pulang']?>" target="_blank">Lihat</a><?php endif;?></td>
<td><?=$r['status']?></td>
</tr>
<?php endforeach; ?>
</table>

<?php include __DIR__ . '/../includes/template_admin_end.php'; ?>