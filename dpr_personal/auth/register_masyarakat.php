<?php
include "../config/koneksi.php";

$success = "";
$error = "";

if (isset($_POST['daftar'])) {
    $nik  = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($koneksi, "SELECT id FROM masyarakat WHERE nik='$nik'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "NIK sudah terdaftar dalam sistem!";
    } else {
        $simpan = mysqli_query($koneksi, "INSERT INTO masyarakat (nik, nama, password) VALUES ('$nik','$nama','$password')");
        if ($simpan) {
            $success = "Pendaftaran berhasil! Silakan menuju halaman login.";
        } else {
            $error = "Terjadi kesalahan sistem, pendaftaran gagal.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Masyarakat – Portal Aspirasi Lampung</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #eff6ff, #ffffff);
            overflow-x: hidden;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.4s ease;
        }
        .input-group:focus-within label {
            color: #2563eb;
            transform: translateX(5px);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            background: #fff;
            transform: translateY(-2px);
        }
        /* Animasi custom untuk tombol */
        .btn-animate:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full animate__animated animate__fadeInUp">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 text-white rounded-2xl shadow-xl mb-4 animate__animated animate__bounceIn animate__delay-1s">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Buat Akun</h2>
            <p class="text-slate-500 text-sm font-medium mt-1">Gabung untuk Lampung yang lebih baik</p>
        </div>

        <div class="register-card p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100">
            
            <?php if($success): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[13px] font-bold rounded-2xl flex items-center gap-3 animate__animated animate__fadeIn">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-[13px] font-bold rounded-2xl flex items-center gap-3 animate__animated animate__shakeX">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div class="input-group">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-all">Nomor Induk Kependudukan (NIK)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-300">
                            <i class="fa-solid fa-id-card"></i>
                        </span>
                        <input type="text" name="nik" maxlength="16"
                               class="form-input w-full pl-11 pr-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all font-medium text-slate-700" 
                               placeholder="16 digit NIK" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-all">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-300">
                            <i class="fa-solid fa-signature"></i>
                        </span>
                        <input type="text" name="nama" 
                               class="form-input w-full pl-11 pr-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all font-medium text-slate-700" 
                               placeholder="Nama sesuai KTP" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 transition-all">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-300">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" 
                               class="form-input w-full pl-11 pr-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all font-medium text-slate-700" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button name="daftar" class="btn-animate w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-200 transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-xs mt-4">
                    Proses Pendaftaran <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-50 text-center">
                <p class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-tighter">Sudah menjadi bagian dari kami?</p>
                <a href="login_masyarakat.php" class="inline-flex items-center gap-2 text-blue-600 font-black text-sm hover:gap-3 transition-all tracking-tight">
                    Login Ke Akun Masyarakat <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <p class="text-center mt-10 text-slate-400 text-[10px] font-bold uppercase tracking-[0.4em] animate__animated animate__fadeIn animate__delay-2s">
            Layanan Aspirasi Digital • Provinsi Lampung
        </p>
    </div>

</body>
</html>