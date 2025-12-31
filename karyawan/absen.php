<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/config.php';
$page_title = 'Absen';
include __DIR__ . '/../includes/template_karyawan.php';
?>
<p>Pastikan browser Anda mengizinkan kamera dan lokasi.</p>
<video id="video" width="320" height="240" autoplay playsinline></video>
<canvas id="canvas" width="320" height="240" style="display:none"></canvas>
<br>
<button id="btn">Clock In / Clock Out</button>
<div id="status"></div>

<script>
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const btn = document.getElementById('btn');
const status = document.getElementById('status');

async function setupCamera(){
  const stream = await navigator.mediaDevices.getUserMedia({video:true});
  video.srcObject = stream;
  return new Promise(r=>video.onloadedmetadata=r);
}
setupCamera().catch(e=>status.innerText='Tidak dapat mengakses kamera: '+e.message);

btn.addEventListener('click', async ()=>{
  status.innerText = 'Mengambil data...';
  // capture photo
  canvas.getContext('2d').drawImage(video,0,0,canvas.width,canvas.height);
  const dataUrl = canvas.toDataURL('image/jpeg',0.9);

  // get location
  if (!navigator.geolocation) { status.innerText='GPS tidak tersedia'; return; }
  navigator.geolocation.getCurrentPosition(async (pos)=>{
    const payload = {
      foto: dataUrl,
      lat: pos.coords.latitude,
      lon: pos.coords.longitude
    };
    status.innerText = 'Mengirim...';
    const res = await fetch('proses_absen.php',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      body: JSON.stringify(payload)
    });
    const j = await res.json();
    status.innerText = j.message || JSON.stringify(j);
  }, (err)=>{
    status.innerText = 'Gagal ambil lokasi: '+err.message;
  }, {enableHighAccuracy:true});
});
</script>

<?php include __DIR__ . '/../includes/template_karyawan_end.php'; ?>