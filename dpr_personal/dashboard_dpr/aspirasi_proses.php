<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID aspirasi tidak ditemukan.";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Join Query agar lebih efisien (sekali tarik data)
$sql = "SELECT a.*, m.nama as nama_pengirim, b.nama_bidang 
        FROM aspirasi a 
        LEFT JOIN masyarakat m ON a.masyarakat_id = m.id 
        LEFT JOIN bidang b ON a.bidang_id = b.id 
        WHERE a.id = $id";

$query = mysqli_query($koneksi, $sql);
$aspirasi = mysqli_fetch_assoc($query);

if (!$aspirasi) {
    echo "Data aspirasi tidak ditemukan.";
    exit;
}

// Fungsi warna badge status
$status_class = ($aspirasi['status'] == 'Selesai') ? 'bg-success' : 'bg-warning text-dark';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aspirasi | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; color: #333; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .detail-label { font-weight: 600; color: #6c757d; font-size: 0.85rem; text-transform: uppercase; }
        .detail-value { font-size: 1.05rem; margin-bottom: 1.5rem; }
        .status-badge { padding: 8px 16px; border-radius: 30px; font-weight: 600; font-size: 0.8rem; }
        .content-box { background: #fdfdfd; border-radius: 10px; padding: 20px; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Detail Aspirasi</h3>
                    <p class="text-muted">ID Transaksi: #ASP-<?= $aspirasi['id'] ?></p>
                </div>
                <a href="aspirasi_masuk.php" class="btn btn-outline-secondary btn-sm rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="status-badge <?= $status_class ?>">
                            <i class="fas fa-circle-info me-1"></i> <?= $aspirasi['status'] ?>
                        </span>
                        <span class="text-muted">
                            <i class="far fa-calendar-alt me-1"></i> <?= date('d M Y, H:i', strtotime($aspirasi['tanggal_aspirasi'])) ?>
                        </span>
                    </div>

                    <h2 class="fw-bold mb-4 text-primary"><?= htmlspecialchars($aspirasi['judul']) ?></h2>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="detail-label">Pengirim</p>
                            <p class="detail-value text-dark fw-semibold">
                                <i class="fas fa-user-circle me-2 text-primary"></i> <?= htmlspecialchars($aspirasi['nama_pengirim']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="detail-label">Kategori Bidang</p>
                            <p class="detail-value text-dark">
                                <span class="badge bg-light text-primary border border-primary px-3 rounded-pill"><?= htmlspecialchars($aspirasi['nama_bidang']) ?></span>
                            </p>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <p class="detail-label">Lokasi Kejadian</p>
                            <p class="detail-value italic">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i> <?= htmlspecialchars($aspirasi['lokasi_jalan']) ?>
                            </p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <p class="detail-label">Isi Aspirasi</p>
                    <div class="content-box mb-4">
                        <p class="mb-0" style="line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($aspirasi['isi_aspirasi'])) ?>
                        </p>
                    </div>

                    <div class="mt-5 d-flex gap-2">
                        <?php if ($aspirasi['status'] != 'Selesai'): ?>
                            <form action="aspirasi_update_status.php" method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?= $aspirasi['id'] ?>">
                                <button type="submit" name="status_selesai" class="btn btn-success w-100 py-3 rounded-pill fw-bold">
                                    <i class="fas fa-check-circle me-2"></i> Tandai Selesai
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-outline-success w-100 py-3 rounded-pill disabled fw-bold">
                                <i class="fas fa-check-double me-2"></i> Aspirasi Telah Diselesaikan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">Sistem Informasi Aspirasi v2.0 &copy; 2026</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>