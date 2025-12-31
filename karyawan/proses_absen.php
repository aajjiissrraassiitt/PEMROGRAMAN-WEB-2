<?php
require_once __DIR__ . '/../../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data) { echo json_encode(['success'=>false,'message'=>'Invalid payload']); exit; }
$id_k = $_SESSION['user_id'] ?? null;
if (!$id_k) { echo json_encode(['success'=>false,'message'=>'Not authenticated']); exit; }

$foto = $data['foto'] ?? null;
$lat = $data['lat'] ?? null;
$lon = $data['lon'] ?? null;
$today = date('Y-m-d');

try {
    // check existing today
    $stmt = $pdo->prepare("SELECT * FROM absensi WHERE id_karyawan=:id AND tanggal=:t LIMIT 1");
    $stmt->execute(['id'=>$id_k,'t'=>$today]);
    $row = $stmt->fetch();

    if (!$foto) throw new Exception('Foto tidak ditemukan');

    // save foto file
    $parts = explode(',', $foto);
    $bin = base64_decode($parts[1]);
    $fname = 'a_'.time().'_'.uniqid().'.jpg';
    $path = $cfg['uploads_absensi'] . $fname;
    if (!is_dir(dirname($path))) mkdir(dirname($path), 0755, true);
    file_put_contents($path, $bin);
    $webpath = 'uploads/absensi/'.$fname;

    if (!$row) {
        // insert clock in
        $stmt = $pdo->prepare("INSERT INTO absensi (id_karyawan,tanggal,jam_masuk,foto_masuk,lat_masuk,lon_masuk,status) VALUES (:id,:t,NOW(),:foto,:lat,:lon,'Hadir')");
        $stmt->execute(['id'=>$id_k,'t'=>$today,'foto'=>$webpath,'lat'=>$lat,'lon'=>$lon]);
        echo json_encode(['success'=>true,'message'=>'Clock In tersimpan']);
        exit;
    } else {
        // update clock out
        $stmt = $pdo->prepare("UPDATE absensi SET jam_pulang=NOW(), foto_pulang=:foto, lat_pulang=:lat, lon_pulang=:lon WHERE id=:id");
        $stmt->execute(['foto'=>$webpath,'lat'=>$lat,'lon'=>$lon,'id'=>$row['id']]);
        echo json_encode(['success'=>true,'message'=>'Clock Out tersimpan']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
    exit;
}
