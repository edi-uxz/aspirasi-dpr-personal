<?php
session_start();
include "../config/koneksi.php";

$error = "";

if (isset($_POST['login'])) {
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM masyarakat WHERE nik='$nik' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['login_masyarakat'] = true;
        $_SESSION['masyarakat_id'] = $data['id'];
        $_SESSION['nama_masyarakat'] = $data['nama'];

        header("Location: ../dashboard_masyarakat/index.php");
        exit;
    } else {
        $error = "NIK atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Masyarakat – Aspirasi DPD RI</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at bottom left, #eff6ff, #ffffff);
            overflow-x: hidden;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .input-focus-effect:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full animate__animated animate__zoomIn animate__faster">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 text-white rounded-[2rem] shadow-2xl shadow-blue-200 mb-6 animate__animated animate__fadeInDown">
                <i class="fa-solid fa-fingerprint text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-400 text-sm font-medium mt-1 uppercase tracking-widest">Portal Aspirasi Masyarakat</p>
        </div>

        <div class="login-card p-10 rounded-[3rem] shadow-2xl shadow-slate-200/50">
            
            <?php if($error): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-xs font-bold rounded-2xl flex items-center gap-3 animate__animated animate__headShake">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="animate__animated animate__fadeInLeft animate__delay-1s">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Nomor Induk Kependudukan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-id-card"></i>
                        </span>
                        <input type="text" name="nik" 
                               class="input-focus-effect w-full pl-11 pr-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold text-slate-700" 
                               placeholder="16 Digit NIK Anda" required>
                    </div>
                </div>

                <div class="animate__animated animate__fadeInLeft animate__delay-1s" style="animation-delay: 1.1s;">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" 
                               class="input-focus-effect w-full pl-11 pr-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:border-blue-500 focus:bg-white transition-all font-semibold text-slate-700" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <button name="login" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-xs animate__animated animate__fadeInUp animate__delay-1s" style="animation-delay: 1.3s;">
                    Masuk Sekarang <i class="fa-solid fa-right-to-bracket"></i>
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-50 text-center animate__animated animate__fadeIn animate__delay-2s">
                <p class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-tighter">Belum memiliki akun?</p>
                <a href="register_masyarakat.php" class="group inline-flex items-center gap-2 text-blue-600 font-black text-sm transition-all tracking-tight hover:gap-3 mb-6">
                    Daftar Akun Baru <i class="fa-solid fa-arrow-right-long transition-transform group-hover:translate-x-1"></i>
                </a>

                <div class="flex justify-center">
                    <a href="http://localhost/dpr_personal/public/index.php" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-slate-50 text-slate-500 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-slate-100 hover:text-blue-600 transition-all border border-slate-100">
                        <i class="fa-solid fa-house text-[10px]"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-8 animate__animated animate__fadeIn animate__delay-2s">
            <a href="login_dpr.php" class="text-[9px] font-black text-slate-300 hover:text-blue-400 uppercase tracking-[0.3em] transition-colors">Akses Khusus Anggota DPD RI</a>
        </div>
    </div>

</body>
</html>