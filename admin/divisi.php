<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Manajemen Divisi';
include __DIR__ . '/../includes/template_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $nama = $_POST['nama'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO divisi (nama) VALUES (:n)");
        $stmt->execute(['n'=>$nama]);
        header('Location: /admin/divisi.php'); exit;
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id']; $nama = $_POST['nama'] ?? '';
        $stmt = $pdo->prepare("UPDATE divisi SET nama=:n WHERE id=:id");
        $stmt->execute(['n'=>$nama,'id'=>$id]);
        header('Location: /admin/divisi.php'); exit;
    } elseif (isset($_POST['hapus'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM divisi WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        header('Location: /admin/divisi.php'); exit;
    }
}

$divs = $pdo->query("SELECT * FROM divisi ORDER BY id DESC")->fetchAll();
?>
<form method="post">
  <input type="text" name="nama" placeholder="Nama Divisi" required>
  <button type="submit" name="tambah">Tambah</button>
</form>
<table>
<tr><th>ID</th><th>Nama</th><th>Aksi</th></tr>
<?php foreach($divs as $d): ?>
<tr>
<td><?=$d['id']?></td>
<td><?=htmlspecialchars($d['nama'])?></td>
<td>
  <form style="display:inline" method="post">
    <input type="hidden" name="id" value="<?=$d['id']?>">
    <input type="text" name="nama" value="<?=htmlspecialchars($d['nama'])?>">
    <button name="edit" type="submit">Edit</button>
  </form>
  <form style="display:inline" method="post" onsubmit="return confirm('Hapus?')">
    <input type="hidden" name="id" value="<?=$d['id']?>">
    <button name="hapus" type="submit">Hapus</button>
  </form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/template_admin_end.php'; ?>