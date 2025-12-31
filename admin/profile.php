<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Profile';
include __DIR__ . '/../includes/template_admin.php';
$id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id=:id"); $stmt->execute(['id'=>$id]); $me = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!empty($_POST['password'])) {
        $pw = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE admin SET password=:pw WHERE id=:id")->execute(['pw'=>$pw,'id'=>$id]);
        echo "<div class='notice'>Password diubah.</div>";
    }
}

?>
<p>Nama: <?=htmlspecialchars($me['nama'])?></p>
<p>Username: <?=htmlspecialchars($me['username'])?></p>
<form method="post">
  <input name="password" placeholder="Password baru">
  <button type="submit">Ganti Password</button>
</form>

<?php include __DIR__ . '/../includes/template_admin_end.php'; ?>