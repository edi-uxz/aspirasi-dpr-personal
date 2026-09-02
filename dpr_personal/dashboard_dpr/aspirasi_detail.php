<?php
session_start();
include "../config/koneksi.php";

// ================== CEK LOGIN DPR ==================
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// ================== VALIDASI ID ==================
if (!isset($_GET['id'])) {
    header("Location: aspirasi_masuk.php");
    exit;
}

$id = intval($_GET['id']);

// ================== AMBIL DETAIL ASPIRASI ==================
$query = mysqli_query($koneksi, "
    SELECT 
        a.*,
        b.nama_bidang,
        k.nama_kabupaten,
        kc.nama_kecamatan,
        d.nama_desa,
        m.nama AS nama_masyarakat,
        m.nik
    FROM aspirasi a
    JOIN bidang b ON a.bidang_id = b.id
    JOIN masyarakat m ON a.masyarakat_id = m.id
    LEFT JOIN kabupaten k ON a.kabupaten_id = k.id
    LEFT JOIN kecamatan kc ON a.kecamatan_id = kc.id
    LEFT JOIN desa d ON a.desa_id = d.id
    WHERE a.id = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div style='text-align:center; margin-top:50px;'><h3>Data aspirasi tidak ditemukan.</h3><a href='aspirasi_masuk.php'>Kembali</a></div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aspirasi | Dashboard DPR</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary-navy: #1a237e; }
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .navbar-custom { background-color: var(--primary-navy); }
        .card-detail { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,.08); }
        .label-custom { color: #6c757d; font-size: 0.85rem; text-transform: uppercase; font-weight: 700; }
        .value-custom { color: #1e293b; font-weight: 500; font-size: 1.05rem; margin-bottom: 20px; }
        .status-badge { padding: 8px 16px; border-radius: 50px; font-weight: 600; font-size: 0.85rem; border: 1px solid rgba(0,0,0,0.05); }
        
        /* Warna Status */
        .Masuk { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .Diproses { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .Selesai { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        
        .content-box { background: #f8fafc; border-left: 4px solid var(--primary-navy); padding: 15px; border-radius: 8px; }
        
        .img-attachment {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .img-attachment:hover { transform: scale(1.02); }
        .empty-photo {
            border: 2px dashed #dee2e6;
            padding: 30px;
            text-align: center;
            color: #adb5bd;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark navbar-custom py-3 mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            <i class="fas fa-landmark me-2 text-warning"></i> DETAIL ASPIRASI
        </a>
        <div class="d-flex align-items-center">
            <a href="aspirasi_masuk.php" class="btn btn-outline-light btn-sm rounded-pill me-2">Kembali</a>
            <a href="../auth/logout.php" class="btn btn-danger btn-sm rounded-pill">Logout</a>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-detail bg-white p-4 p-md-5">
                
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h3 class="fw-bold mb-0">Laporan #<?= $data['id'] ?></h3>
                    <span class="status-badge <?= $data['status'] ?>">
                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i> <?= $data['status'] ?>
                    </span>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p class="label-custom mb-1">Judul</p>
                        <h4 class="fw-bold text-primary"><?= htmlspecialchars($data['judul']) ?></h4>

                        <p class="label-custom mb-1 mt-4">Pengirim</p>
                        <div class="value-custom">
                            <?= htmlspecialchars($data['nama_masyarakat']) ?> 
                            <small class="text-muted">(<?= $data['nik'] ?>)</small>
                        </div>

                        <p class="label-custom mb-1">Bidang</p>
                        <div class="value-custom"><span class="badge bg-primary"><?= $data['nama_bidang'] ?></span></div>
                    </div>

                    <div class="col-md-6">
                        <p class="label-custom mb-1">Tanggal</p>
                        <div class="value-custom"><?= date('d-m-Y', strtotime($data['tanggal_aspirasi'])) ?></div>

                        <p class="label-custom mb-1">Wilayah</p>
                        <div class="value-custom">
                            <?= $data['nama_desa'] ?? '-' ?>, <?= $data['nama_kecamatan'] ?? '-' ?>, <?= $data['nama_kabupaten'] ?? '-' ?>
                        </div>

                        <p class="label-custom mb-1">Lokasi Detail (Jalan)</p>
                        <div class="value-custom text-muted small"><?= htmlspecialchars($data['lokasi_jalan'] ?? '-') ?></div>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="label-custom mb-2">Isi Aspirasi</p>
                    <div class="content-box">
                        <?= nl2br(htmlspecialchars($data['isi_aspirasi'])) ?>
                    </div>
                </div>

                <div class="mt-5">
                    <p class="label-custom mb-3">Lampiran Foto Lapangan</p>
                    <div class="row">
                        <div class="col-md-8">
                            <?php if (!empty($data['foto'])): ?>
                                <img src="../assets/img/aspirasi/<?= $data['foto'] ?>" 
                                     alt="Foto Aspirasi" 
                                     class="img-attachment mb-3"
                                     onclick="window.open(this.src)">
                                <p class="text-muted small"><i class="fas fa-search-plus me-1"></i> Klik gambar untuk memperbesar</p>
                            <?php else: ?>
                                <div class="empty-photo">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p class="mb-0">Tidak ada lampiran foto untuk laporan ini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <a href="aspirasi_masuk.php" class="btn btn-secondary px-4">Tutup</a>
                    
                    <div class="gap-2 d-flex">
                        <?php if ($data['status'] == 'Masuk'): ?>
                            <a href="aspirasi_update_status.php?id=<?= $data['id'] ?>&status=Diproses" 
                               class="btn btn-warning px-4 fw-bold shadow"
                               onclick="return confirm('Apakah Anda yakin ingin memproses aspirasi ini?')">
                               <i class="fas fa-spinner me-2"></i> Proses Aspirasi
                            </a>

                        <?php elseif ($data['status'] == 'Diproses'): ?>
                            <a href="aspirasi_update_status.php?id=<?= $data['id'] ?>&status=Selesai" 
                               class="btn btn-success px-4 fw-bold shadow"
                               onclick="return confirm('Tandai aspirasi ini sebagai Selesai?')">
                               <i class="fas fa-check-circle me-2"></i> Tandai Selesai
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>