<?php
// aspirasi_detail.php
include "../config/koneksi.php";
session_start(); 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// MENGAMBIL IDENTITAS EMAIL DARI SESSION CHAT
// Jika chat_email ada, tampilkan email tersebut. Jika tidak, tampilkan 'Anonim'
$identitas_pengirim = $_SESSION['chat_email'] ?? 'Anonim'; 

// Proses Simpan Komentar
if (isset($_POST['kirim_komentar'])) {
    $pesan = mysqli_real_escape_string($koneksi, $_POST['isi_komentar']);
    
    // Validasi: User harus sudah input email di halaman chat
    if (!isset($_SESSION['chat_email'])) {
        echo "<script>alert('Silahkan masukkan email Anda di Ruang Chat terlebih dahulu!'); window.location.href='chat_umum.php';</script>";
        exit;
    }

    if (!empty($pesan)) {
        // Simpan email sebagai nama pengomentar
        mysqli_query($koneksi, "INSERT INTO komentar_aspirasi (aspirasi_id, nama_pengomentar, isi_komentar) VALUES ('$id', '$identitas_pengirim', '$pesan')");
        echo "<script>window.location.href='detail_aspirasi.php?id=$id';</script>";
    }
}

// Query Detail Aspirasi
$query = mysqli_query($koneksi, "
    SELECT a.*, m.nama as nama_pengirim, b.nama_bidang, k.nama_kabupaten, kec.nama_kecamatan, d.nama_desa
    FROM aspirasi a
    JOIN masyarakat m ON a.masyarakat_id = m.id
    JOIN bidang b ON a.bidang_id = b.id
    JOIN kabupaten k ON a.kabupaten_id = k.id
    JOIN kecamatan kec ON a.kecamatan_id = kec.id
    JOIN desa d ON a.desa_id = d.id
    WHERE a.id = '$id'
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Aspirasi tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aspirasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fafafa; }
        .hidden-comment { display: none; }
    </style>
</head>
<body class="pb-24 text-slate-900">

<nav class="sticky top-0 z-50 px-4 py-3 bg-white border-b border-slate-200">
    <div class="flex items-center gap-4 mx-auto max-w-2xl">
        <button onclick="history.back()" class="text-xl hover:text-blue-600 transition"><i class="fas fa-arrow-left"></i></button>
        <h1 class="text-lg font-bold">Detail Aspirasi</h1>
    </div>
</nav>

<main class="px-4 mx-auto mt-4 max-w-2xl">
    <div class="overflow-hidden bg-white border border-slate-200 rounded-xl shadow-sm">
        
        <!-- Foto Bukti -->
        <div class="aspect-video bg-slate-100">
            <?php if($data['foto']): ?>
                <img src="../assets/img/<?= $data['foto']; ?>" class="object-cover w-full h-full">
            <?php else: ?>
                <div class="flex flex-col items-center justify-center w-full h-full text-slate-400">
                    <i class="mb-2 fas fa-image text-4xl"></i>
                    <p class="text-sm">Tidak ada foto bukti</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="p-5">
            <!-- Header & Isi -->
            <div class="mb-4">
                <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded uppercase tracking-wider"><?= $data['nama_bidang']; ?></span>
                <h2 class="mt-2 text-xl font-bold leading-tight"><?= $data['judul']; ?></h2>
            </div>
            <p class="leading-relaxed text-slate-700 mb-6"><?= nl2br(htmlspecialchars($data['isi_aspirasi'])); ?></p>
            
            <!-- Lokasi -->
            <div class="flex items-start gap-2 mb-6 text-sm text-slate-500 bg-slate-50 p-3 rounded-lg border border-slate-100">
                <i class="fas fa-location-dot text-red-500 mt-1"></i>
                <span><?= htmlspecialchars($data['lokasi_jalan']); ?>, <?= $data['nama_desa']; ?>, <?= $data['nama_kecamatan']; ?></span>
            </div>

            <!-- HASIL KOMEN (Format List Biasa) -->
            <div class="mb-6 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tanggapan Masyarakat</h3>
                <?php
                $komentar = mysqli_query($koneksi, "SELECT * FROM komentar_aspirasi WHERE aspirasi_id = '$id' ORDER BY created_at ASC");
                if(mysqli_num_rows($komentar) > 0):
                    while($k = mysqli_fetch_assoc($komentar)):
                ?>
                    <div class="p-4 bg-white border border-slate-100 rounded-xl shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <!-- Menampilkan Email Pengirim -->
                            <span class="text-[11px] font-bold text-blue-600"><?= htmlspecialchars($k['nama_pengomentar']); ?></span>
                            <span class="text-[9px] text-slate-400"><?= date('d M Y, H:i', strtotime($k['created_at'])); ?></span>
                        </div>
                        <p class="text-sm text-slate-600 leading-snug"><?= htmlspecialchars($k['isi_komentar']); ?></p>
                    </div>
                <?php endwhile; else: ?>
                    <p class="text-center text-xs text-slate-400 py-4">Belum ada tanggapan untuk aspirasi ini.</p>
                <?php endif; ?>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="flex gap-4 border-t border-b border-slate-50 py-3 mb-4">
                <button onclick="toggleComment()" class="flex flex-1 items-center justify-center gap-2 font-semibold text-slate-600 hover:text-blue-600 transition">
                    <i class="far fa-comment"></i> Tanggapi
                </button>
                <button onclick="shareContent()" class="flex flex-1 items-center justify-center gap-2 font-semibold text-slate-600 hover:text-green-600 transition">
                    <i class="far fa-share-square"></i> Bagikan
                </button>
            </div>

            <!-- FORM TANGGAPAN -->
            <div id="commentSection" class="hidden-comment mt-4">
                <?php if (!isset($_SESSION['chat_email'])): ?>
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl text-center">
                        <p class="text-xs text-blue-700 mb-2">Anda perlu memasukkan email untuk menanggapi.</p>
                        <a href="chat_umum.php" class="inline-block bg-blue-600 text-white text-[10px] px-4 py-2 rounded-lg font-bold uppercase">Masuk di Ruang Chat</a>
                    </div>
                <?php else: ?>
                    <form action="" method="POST" class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-slate-400"></i>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Menanggapi sebagai: <span class="text-blue-600"><?= $identitas_pengirim; ?></span></span>
                        </div>
                        <textarea name="isi_komentar" id="inputKomentar" placeholder="Tulis tanggapan Anda secara bijak..." class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition" rows="2" required></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="toggleComment()" class="px-4 py-2 text-xs font-bold text-slate-400">Batal</button>
                            <button type="submit" name="kirim_komentar" class="px-6 py-2 text-xs font-bold text-white bg-blue-600 rounded-xl shadow-md transition active:scale-95">Kirim</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script>
    function toggleComment() {
        const section = document.getElementById('commentSection');
        const input = document.getElementById('inputKomentar');
        if (section.style.display === "block") {
            section.style.display = "none";
        } else {
            section.style.display = "block";
            if(input) input.focus();
            section.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    async function shareContent() {
        const shareData = {
            title: '<?= addslashes($data['judul']); ?>',
            url: window.location.href
        };
        try {
            if (navigator.share) { await navigator.share(shareData); }
            else { await navigator.clipboard.writeText(window.location.href); alert('Link disalin ke clipboard!'); }
        } catch (err) { console.error(err); }
    }
</script>

</body>
</html>