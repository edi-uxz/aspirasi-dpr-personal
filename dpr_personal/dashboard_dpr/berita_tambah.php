<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);

    // Upload gambar
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $filename = $_FILES['gambar']['name'];
        $tmpname = $_FILES['gambar']['tmp_name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array(strtolower($ext), $allowed)) {
            $newname = time() . '_' . rand(1000,9999) . '.' . $ext;
            $upload = move_uploaded_file($tmpname, '../assets/berita/' . $newname);
            if ($upload) {
                $gambar = $newname;
            } else {
                echo "<script>alert('Gagal upload gambar');</script>";
            }
        } else {
            echo "<script>alert('Format gambar tidak diperbolehkan');</script>";
        }
    }

    // Insert ke database
    $query = mysqli_query($koneksi, "INSERT INTO berita (judul, isi, gambar) VALUES ('$judul', '$isi', '$gambar')");
    if ($query) {
        echo "<script>alert('Berita berhasil ditambahkan');window.location='berita.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan berita');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        nav {
            background-color: #0d6efd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        nav a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            font-weight: 500;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .card {
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-4">

    <!-- Navbar -->
    <nav class="mb-4">
        <a href="index.php">Dashboard</a>
        <a href="aspirasi_masuk.php">Aspirasi</a>
        <a href="agenda.php">Agenda</a>
        <a href="berita.php">Berita</a>
        <a href="../public/transparansi.php">Transparansi Publik</a>
        <a href="profil.php">Profil</a>
        <a href="../auth/logout.php">Logout</a>
    </nav>

    <div class="card shadow-sm">
        <h3 class="mb-4">Tambah Berita</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Berita</label>
                <textarea name="isi" class="form-control" rows="6" placeholder="Masukkan isi berita" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar (opsional)</label>
                <input type="file" name="gambar" class="form-control">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                <a href="berita.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
