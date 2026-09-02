<?php
session_start();
include "../config/koneksi.php";

// 1. Cek Login
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// 2. Ambil data dari URL (GET) atau Form (POST)
// Disini saya buat fleksibel, bisa lewat link atau form
$id     = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
$status = isset($_GET['status']) ? $_GET['status'] : (isset($_POST['status']) ? $_POST['status'] : '');

// 3. Validasi Data
if ($id <= 0 || empty($status)) {
    echo "<script>
            alert('Data tidak valid!');
            window.location='aspirasi_masuk.php';
          </script>";
    exit;
}

// 4. Daftar status yang diperbolehkan (keamanan)
$allowed_status = ['Masuk', 'Diproses', 'Ditindaklanjuti', 'Selesai'];
if (!in_array($status, $allowed_status)) {
    echo "<script>
            alert('Status tidak dikenali!');
            window.location='aspirasi_masuk.php';
          </script>";
    exit;
}

// 5. Update Status ke Database
$query = "UPDATE aspirasi SET status = '$status' WHERE id = '$id'";
$update = mysqli_query($koneksi, $query);

if ($update) {
    // Redirect balik ke halaman detail atau daftar dengan pesan sukses
    echo "<script>
            alert('Status aspirasi berhasil diperbarui menjadi $status!');
            window.location='aspirasi_detail.php?id=$id';
          </script>";
} else {
    echo "<script>
            alert('Gagal memperbarui status: " . mysqli_error($koneksi) . "');
            window.history.back();
          </script>";
}
?>