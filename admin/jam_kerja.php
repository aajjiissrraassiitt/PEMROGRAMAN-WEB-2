<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Manajemen Jam Kerja';
include __DIR__ . '/../includes/template_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $nama = $_POST['nama_shift']; $masuk = $_POST['jam_masuk']; $pulang = $_POST['jam_pulang']; $tol = (int)$_POST['toleransi'];
        $stmt = $pdo->prepare("INSERT INTO jam_kerja (nama_shift,jam_masuk,jam_pulang,toleransi_menit) VALUES (:n,:m,:p,:t)");
        $stmt->execute(['n'=>$nama,'m'=>$masuk,'p'=>$pulang,'t'=>$tol]);
        header('Location: /admin/jam_kerja.php'); exit;
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id']; $nama = $_POST['nama_shift']; $masuk = $_POST['jam_masuk']; $pulang = $_POST['jam_pulang']; $tol = (int)$_POST['toleransi'];
        $stmt = $pdo->prepare("UPDATE jam_kerja SET nama_shift=:n,jam_masuk=:m,jam_pulang=:p,toleransi_menit=:t WHERE id=:id");
        $stmt->execute(['n'=>$nama,'m'=>$masuk,'p'=>$pulang,'t'=>$tol,'id'=>$id]);
        header('Location: /admin/jam_kerja.php'); exit;
    } elseif (isset($_POST['hapus'])) {
        $id = $_POST['id']; $stmt = $pdo->prepare("DELETE FROM jam_kerja WHERE id=:id"); $stmt->execute(['id'=>$id]);
        header('Location: /admin/jam_kerja.php'); exit;
    }
}

$rows = $pdo->query("SELECT * FROM jam_kerja ORDER BY id DESC")->fetchAll();
?>
<form method="post" class="form-inline">
  <input name="nama_shift" placeholder="Nama Shift" required>
  <input name="jam_masuk" type="time" required>
  <input name="jam_pulang" type="time" required>
  <input name="toleransi" type="number" value="0" min="0">
  <button name="tambah" type="submit">Tambah</button>
</form>
<table>
<tr><th>ID</th><th>Shift</th><th>Masuk</th><th>Pulang</th><th>Toleransi</th><th>Aksi</th></tr>
<?php foreach($rows as $r): ?>
<tr>
<td><?=$r['id']?></td>
<td><?=htmlspecialchars($r['nama_shift'])?></td>
<td><?=$r['jam_masuk']?></td>
<td><?=$r['jam_pulang']?></td>
<td><?=$r['toleransi_menit']?> m</td>
<td>
<form method="post" style="display:inline">
<input type="hidden" name="id" value="<?=$r['id']?>">
<input name="nama_shift" value="<?=htmlspecialchars($r['nama_shift'])?>">
<input name="jam_masuk" type="time" value="<?=$r['jam_masuk']?>">
<input name="jam_pulang" type="time" value="<?=$r['jam_pulang']?>">
<input name="toleransi" type="number" value="<?=$r['toleransi_menit']?>" min="0">
<button name="edit" type="submit">Edit</button>
</form>
<form method="post" style="display:inline" onsubmit="return confirm('Hapus?')">
<input type="hidden" name="id" value="<?=$r['id']?>">
<button name="hapus" type="submit">Hapus</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/template_admin_end.php'; ?>