<?php
// proses_laporan.php
include "../config/koneksi.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil Nama Pengirim yang sudah disinkronkan dari Chat Umum
    $nama_pengirim_chat = mysqli_real_escape_string($koneksi, $_POST['nama_dari_chat']);
    
    // 2. Ambil ID Masyarakat dari session (jika ada)
    $masyarakat_id = $_SESSION['id_masyarakat'] ?? 2; 
    
    $bidang_id     = (int)$_POST['kategori'];
    $judul         = mysqli_real_escape_string($koneksi, $_POST['judul'] ?? '');
    $isi_aspirasi  = mysqli_real_escape_string($koneksi, $_POST['laporan'] ?? '');
    $lokasi_jalan  = mysqli_real_escape_string($koneksi, $_POST['lokasi'] ?? '');
    
    // Data wilayah otomatis
    $kabupaten_id  = 26; 
    $kecamatan_id  = 4;  
    $desa_id       = 1;  
    $tanggal       = date('Y-m-d');

    // Proses Upload Foto
    $foto_name = NULL;
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = date('YmdHis') . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../assets/img/" . $foto_name);
    }

    // Query INSERT (Pastikan tabel aspirasi memiliki kolom untuk menyimpan identitas nama jika diperlukan)
    // Di sini kita tetap memasukkan masyarakat_id, namun identitas tampilan akan mengikuti $nama_pengirim_chat
    $query = "INSERT INTO aspirasi (masyarakat_id, bidang_id, kabupaten_id, kecamatan_id, desa_id, judul, isi_aspirasi, lokasi_jalan, status, tanggal_aspirasi, foto) 
              VALUES ('$masyarakat_id', '$bidang_id', '$kabupaten_id', '$kecamatan_id', '$desa_id', '$judul', '$isi_aspirasi', '$lokasi_jalan', 'Masuk', '$tanggal', '$foto_name')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Aspirasi berhasil diajukan sebagai $nama_pengirim_chat!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>