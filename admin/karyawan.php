<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Manajemen Karyawan';
include __DIR__ . '/../includes/template_admin.php';

// handle create/edit/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // tambah karyawan
    if (isset($_POST['tambah'])) {
        $nip = $_POST['nip']; $nama = $_POST['nama']; $email = $_POST['email']; $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $id_div = $_POST['id_divisi'] ?: null; $id_jam = $_POST['id_jam_kerja'] ?: null;
        $foto = null;
        if (!empty($_FILES['foto']['tmp_name'])) {
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $file = uniqid('p_').'.'.$ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__.'/../uploads/profil/'.$file);
            $foto = 'uploads/profil/'.$file;
        }
        $stmt = $pdo->prepare("INSERT INTO karyawan (nip,nama,email,password,id_divisi,id_jam_kerja,foto_profil) VALUES (:nip,:nama,:email,:pw,:div,:jam,:foto)");
        $stmt->execute(['nip'=>$nip,'nama'=>$nama,'email'=>$email,'pw'=>$pw,'div'=>$id_div,'jam'=>$id_jam,'foto'=>$foto]);
        header('Location: /admin/karyawan.php'); exit;
    }
    // edit
    if (isset($_POST['edit'])) {
        $id=$_POST['id']; $nama=$_POST['nama']; $email=$_POST['email']; $id_div=$_POST['id_divisi']?:null; $id_jam=$_POST['id_jam_kerja']?:null;
        $stmt = $pdo->prepare("UPDATE karyawan SET nama=:nama,email=:email,id_divisi=:div,id_jam_kerja=:jam WHERE id=:id");
        $stmt->execute(['nama'=>$nama,'email'=>$email,'div'=>$id_div,'jam'=>$id_jam,'id'=>$id]);
        // reset password
        if (!empty($_POST['reset_password'])) {
            $pw = password_hash($_POST['reset_password'], PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE karyawan SET password=:pw WHERE id=:id")->execute(['pw'=>$pw,'id'=>$id]);
        }
        header('Location: /admin/karyawan.php'); exit;
    }
    // hapus
    if (isset($_POST['hapus'])) {
        $id=$_POST['id']; $pdo->prepare("DELETE FROM karyawan WHERE id=:id")->execute(['id'=>$id]);
        header('Location: /admin/karyawan.php'); exit;
    }
}

$karyawans = $pdo->query("SELECT k.*, d.nama as divisi, j.nama_shift FROM karyawan k LEFT JOIN divisi d ON k.id_divisi=d.id LEFT JOIN jam_kerja j ON k.id_jam_kerja=j.id ORDER BY k.id DESC")->fetchAll();
$divs = $pdo->query("SELECT * FROM divisi")->fetchAll();
$jams = $pdo->query("SELECT * FROM jam_kerja")->fetchAll();
?>
<h3>Tambah Karyawan</h3>
<form method="post" enctype="multipart/form-data">
<input name="nip" placeholder="NIP" required>
<input name="nama" placeholder="Nama" required>
<input name="email" placeholder="Email" required>
<input name="password" placeholder="Password" required>
<select name="id_divisi">
  <option value="">-- Divisi --</option>
  <?php foreach($divs as $d): ?><option value="<?=$d['id']?>"><?=htmlspecialchars($d['nama'])?></option><?php endforeach; ?>
</select>
<select name="id_jam_kerja">
  <option value="">-- Jam Kerja --</option>
  <?php foreach($jams as $j): ?><option value="<?=$j['id']?>"><?=htmlspecialchars($j['nama_shift'])?></option><?php endforeach; ?>
</select>
<input type="file" name="foto" accept="image/*">
<button name="tambah" type="submit">Tambah</button>
</form>

<h3>Daftar Karyawan</h3>
<table>
<tr><th>ID</th><th>NIP</th><th>Nama</th><th>Email</th><th>Divisi</th><th>Shift</th><th>Aksi</th></tr>
<?php foreach($karyawans as $k): ?>
<tr>
<td><?=$k['id']?></td>
<td><?=$k['nip']?></td>
<td><?=htmlspecialchars($k['nama'])?></td>
<td><?=htmlspecialchars($k['email'])?></td>
<td><?=htmlspecialchars($k['divisi'])?></td>
<td><?=htmlspecialchars($k['nama_shift'])?></td>
<td>
<form method="post" style="display:inline">
<input type="hidden" name="id" value="<?=$k['id']?>">
<input name="nama" value="<?=htmlspecialchars($k['nama'])?>">
<input name="email" value="<?=htmlspecialchars($k['email'])?>">
<select name="id_divisi">
  <option value="">--</option>
  <?php foreach($divs as $d): ?><option value="<?=$d['id']?>" <?=$d['id']==$k['id_divisi']?'selected':''?>><?=$d['nama']?></option><?php endforeach; ?>
</select>
<select name="id_jam_kerja">
  <option value="">--</option>
  <?php foreach($jams as $j): ?><option value="<?=$j['id']?>" <?=$j['id']==$k['id_jam_kerja']?'selected':''?>><?=$j['nama_shift']?></option><?php endforeach; ?>
</select>
<input name="reset_password" placeholder="Reset password (kosong = no)">
<button name="edit" type="submit">Edit</button>
</form>
<form method="post" style="display:inline" onsubmit="return confirm('Hapus?')">
<input type="hidden" name="id" value="<?=$k['id']?>">
<button name="hapus" type="submit">Hapus</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php include __DIR__ . '/../includes/template_admin_end.php'; ?>