<?php
session_start();
require __DIR__ . '/config/config.php';

$identity = $_POST['identity'] ?? '';
$password = $_POST['password'] ?? '';

if (!$identity || !$password) {
    $_SESSION['login_error'] = 'Isi semua kolom.';
    header('Location: login.php'); exit;
}

// Fungsi untuk memverifikasi password dengan dua metode
function verifyPassword($inputPassword, $storedPassword) {
    // Metode 1: Cek sebagai hash password_hash()
    if (password_verify($inputPassword, $storedPassword)) {
        return true;
    }
    
    // Metode 2: Cek sebagai plain text (jika password disimpan tanpa hash)
    if ($inputPassword === $storedPassword) {
        return true;
    }
    
    return false;
}

// Check admin
$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :identity OR email = :identity");
$stmt->execute(['identity' => $identity]);
$admin = $stmt->fetch();

if ($admin && verifyPassword($password, $admin['password'])) {
    $_SESSION['user_id'] = $admin['id'];
    $_SESSION['nama'] = $admin['nama'];
    $_SESSION['role'] = 'admin';
    
    // Opsional: Auto-upgrade password ke hash jika masih plain text
    if (!password_needs_rehash($admin['password'], PASSWORD_DEFAULT) && $password === $admin['password']) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE admin SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $admin['id']]);
    }
    
    header('Location: admin/dashboard.php'); exit;
}

// Check karyawan
$stmt = $pdo->prepare("SELECT * FROM karyawan WHERE email = :identity OR nip = :identity LIMIT 1");
$stmt->execute(['identity' => $identity]);
$k = $stmt->fetch();

if ($k && verifyPassword($password, $k['password'])) {
    $_SESSION['user_id'] = $k['id'];
    $_SESSION['nama'] = $k['nama'];
    $_SESSION['role'] = 'karyawan';
    
    // Opsional: Auto-upgrade password ke hash jika masih plain text
    if (!password_needs_rehash($k['password'], PASSWORD_DEFAULT) && $password === $k['password']) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE karyawan SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $k['id']]);
    }
    
    header('Location: karyawan/dashboard.php'); exit;
}

$_SESSION['login_error'] = 'Login gagal. Periksa kredensial.';
header('Location: login.php'); exit;
?>