<?php
session_start();
include "../config/koneksi.php";

/* =========================
   CEK LOGIN DPR
========================= */
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

/* =========================
   AMBIL ID AGENDA
========================= */
if (!isset($_GET['id'])) {
    header("Location: agenda.php");
    exit;
}

$id = intval($_GET['id']);

/* =========================
   AMBIL DATA AGENDA
========================= */
$query = mysqli_query($koneksi, "SELECT * FROM agenda WHERE id = $id");
$agenda = mysqli_fetch_assoc($query);

if (!$agenda) {
    echo "<script>alert('Agenda tidak ditemukan');window.location='agenda.php';</script>";
    exit;
}

/* =========================
   PROSES UPDATE
========================= */
if (isset($_POST['update'])) {
    $judul     = htmlspecialchars($_POST['judul']);
    $tanggal   = $_POST['tanggal'];
    $waktu     = $_POST['waktu'];
    $lokasi    = htmlspecialchars($_POST['lokasi']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);

    $update = mysqli_query($koneksi, "
        UPDATE agenda SET
            judul='$judul',
            tanggal='$tanggal',
            waktu='$waktu',
            lokasi='$lokasi',
            deskripsi='$deskripsi'
        WHERE id=$id
    ");

    if ($update) {
        echo "<script>alert('Agenda berhasil diperbarui');window.location='agenda.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui agenda');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Agenda</title>

<style>
body{
    font-family:Arial, sans-serif;
    background:#f4f6f8;
}
.container{
    max-width:700px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:8px;
    box-shadow:0 4px 10px rgba(0,0,0,.1);
}
h2{
    margin-bottom:20px;
}
label{
    display:block;
    margin-top:15px;
    font-weight:bold;
}
input, textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    border-radius:5px;
    border:1px solid #ccc;
}
button{
    margin-top:20px;
    padding:10px 20px;
    background:#0d6efd;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
.btn-back{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#0d6efd;
}
</style>
</head>
<body>

<div class="container">

    <a href="agenda.php" class="btn-back">⬅ Kembali ke Agenda</a>

    <h2>✏️ Edit Agenda</h2>

    <form method="POST">
        <label>Judul Agenda</label>
        <input type="text" name="judul" required value="<?= htmlspecialchars($agenda['judul']) ?>">

        <label>Tanggal</label>
        <input type="date" name="tanggal" required value="<?= $agenda['tanggal'] ?>">

        <label>Waktu</label>
        <input type="time" name="waktu" required value="<?= $agenda['waktu'] ?>">

        <label>Lokasi</label>
        <input type="text" name="lokasi" required value="<?= htmlspecialchars($agenda['lokasi']) ?>">

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="4"><?= htmlspecialchars($agenda['deskripsi']) ?></textarea>

        <button type="submit" name="update">💾 Simpan Perubahan</button>
    </form>

</div>

</body>
</html>
