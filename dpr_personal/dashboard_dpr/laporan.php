<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// Ambil Data Statistik
$total_aspirasi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi"))['total'];
$aspirasi_masuk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi WHERE status='Masuk'"))['total'];
$aspirasi_proses = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi WHERE status='Diproses'"))['total'];
$aspirasi_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi WHERE status='Selesai'"))['total'];

// Ambil Data List untuk Laporan
// Kita asumsikan nama kolom di tabel bidang adalah 'nama_bidang' atau 'nama'
// Jika masih error, kita akan menggunakan fallback ke bidang_id saja
$sql_aspirasi = "SELECT aspirasi.*, 
                 (SELECT nama_bidang FROM bidang WHERE bidang.id = aspirasi.bidang_id) as nama_bidang
                 FROM aspirasi 
                 ORDER BY aspirasi.tanggal_aspirasi DESC";

$aspirasi_list = mysqli_query($koneksi, $sql_aspirasi);

// Jika query di atas gagal karena kolom 'nama_bidang' salah, gunakan query cadangan ini:
if (!$aspirasi_list) {
    $sql_aspirasi = "SELECT * FROM aspirasi ORDER BY tanggal_aspirasi DESC";
    $aspirasi_list = mysqli_query($koneksi, $sql_aspirasi);
}

// Sesuaikan query agenda & berita (asumsi nama kolom umum)
$agenda_list = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY id DESC LIMIT 10");
$berita_list = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY id DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Kinerja - <?= $_SESSION['nama_dpr']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; padding: 0 !important; }
            .print-area { box-shadow: none !important; border: none !important; width: 100% !important; margin: 0 !important; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid black !important; padding: 8px; font-size: 10pt; }
        }
        
        .print-area {
            background: white;
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .line-double { border-bottom: 4px double black; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
    </style>
</head>
<body class="bg-slate-500 py-10">

    <div class="max-w-[210mm] mx-auto mb-5 no-print flex justify-between">
        <a href="index.php" class="bg-slate-800 text-white px-5 py-2 rounded-lg font-bold">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-8 py-2 rounded-lg font-bold shadow-lg">
            <i class="fa-solid fa-print mr-2"></i> Cetak Laporan (PDF)
        </button>
    </div>

    <div class="print-area">
        <div class="text-center mb-6 line-double pb-4">
            <h1 class="text-xl font-bold uppercase">Dewan Perwakilan Rakyat</h1>
            <p class="text-sm">Provinsi Lampung - Sekretariat Jenderal</p>
            <p class="text-xs italic">Jl. Wolter Monginsidi No. 69, Bandar Lampung, Kode Pos 35116</p>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-lg font-bold text-decoration-underline uppercase">Laporan Evaluasi Kinerja Sistem Aspirasi</h2>
            <p class="text-sm">Tanggal Cetak: <?= date('d F Y'); ?></p>
        </div>

        <div class="mb-6 text-sm">
            <table class="border-none w-auto">
                <tr class="border-none">
                    <td class="border-none p-1 font-bold">Nama Anggota</td>
                    <td class="border-none p-1">: <?= $_SESSION['nama_dpr']; ?></td>
                </tr>
                <tr class="border-none">
                    <td class="border-none p-1 font-bold">Jabatan</td>
                    <td class="border-none p-1">: Anggota Legislatif / Administrator</td>
                </tr>
            </table>
        </div>

        <h3 class="font-bold border-b mb-3 text-sm uppercase">I. Ringkasan Statistik</h3>
        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="border p-3 text-center">
                <p class="text-[10px] uppercase font-bold text-slate-500">Total Aspirasi</p>
                <p class="text-xl font-bold"><?= $total_aspirasi; ?></p>
            </div>
            <div class="border p-3 text-center">
                <p class="text-[10px] uppercase font-bold text-amber-500">Masuk</p>
                <p class="text-xl font-bold"><?= $aspirasi_masuk; ?></p>
            </div>
            <div class="border p-3 text-center">
                <p class="text-[10px] uppercase font-bold text-blue-500">Proses</p>
                <p class="text-xl font-bold"><?= $aspirasi_proses; ?></p>
            </div>
            <div class="border p-3 text-center">
                <p class="text-[10px] uppercase font-bold text-emerald-500">Selesai</p>
                <p class="text-xl font-bold"><?= $aspirasi_selesai; ?></p>
            </div>
        </div>

        <h3 class="font-bold border-b mb-3 text-sm uppercase">II. Rincian Aspirasi Masyarakat</h3>
        <table class="mb-8">
            <thead>
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="20%">Bidang</th>
                    <th>Judul / Perihal</th>
                    <th width="15%">Status</th>
                </tr>
            </thead>
            <tbody class="text-[10px]">
                <?php $no=1; while($row = mysqli_fetch_assoc($aspirasi_list)): ?>
                <tr>
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= date('d/m/Y', strtotime($row['tanggal_aspirasi'])); ?></td>
                    <td><?= $row['bidang'] ?? 'Umum'; ?></td>
                    <td><strong><?= $row['judul']; ?></strong><br><span class="italic text-slate-600"><?= substr($row['isi_aspirasi'], 0, 100); ?>...</span></td>
                    <td class="font-bold"><?= $row['status']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-bold border-b mb-3 text-sm uppercase">III. Agenda Kerja</h3>
                <table class="text-[9px]">
                    <thead>
                        <tr><th>Tanggal</th><th>Kegiatan</th></tr>
                    </thead>
                    <tbody>
                        <?php while($ag = mysqli_fetch_assoc($agenda_list)): ?>
                        <tr>
                            <td><?= isset($ag['tanggal']) ? date('d/m/Y', strtotime($ag['tanggal'])) : '-'; ?></td>
                            <td><?= $ag['kegiatan'] ?? ($ag['judul'] ?? '-'); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div>
                <h3 class="font-bold border-b mb-3 text-sm uppercase">IV. Publikasi Berita</h3>
                <table class="text-[9px]">
                    <thead>
                        <tr><th>Tanggal</th><th>Judul Berita</th></tr>
                    </thead>
                    <tbody>
                        <?php while($br = mysqli_fetch_assoc($berita_list)): ?>
                        <tr>
                            <td><?= isset($br['tanggal_post']) ? date('d/m/Y', strtotime($br['tanggal_post'])) : (isset($br['created_at']) ? date('d/m/Y', strtotime($br['created_at'])) : '-'); ?></td>
                            <td><?= $br['judul']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-20 flex justify-end">
            <div class="text-center w-64">
                <p class="text-sm">Bandar Lampung, <?= date('d F Y'); ?></p>
                <p class="text-sm mb-24">Dicetak Oleh,</p>
                <p class="text-sm font-bold underline"><?= $_SESSION['nama_dpr']; ?></p>
                <p class="text-[10px]">ID Anggota: <?= $_SESSION['id_dpr'] ?? 'DPR-2026'; ?></p>
            </div>
        </div>
    </div>

    <p class="text-center text-slate-300 text-xs no-print mb-10">
        &copy; 2026 Sistem Aspirasi Rakyat - Halaman ini dioptimalkan untuk cetak A4.
    </p>

</body>
</html>