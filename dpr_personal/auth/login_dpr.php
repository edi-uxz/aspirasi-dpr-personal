<?php
session_start();
include "../config/koneksi.php";

$error = "";

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query(
        $koneksi, 
        "SELECT id, nama, email, password 
         FROM anggota_dpr 
         WHERE email='$email' 
         LIMIT 1"
    );

    if ($query && mysqli_num_rows($query) === 1) {
        $data = mysqli_fetch_assoc($query);
        if (password_verify($password, $data['password'])) {
            $_SESSION['login_dpr'] = true;
            $_SESSION['dpr_id']    = $data['id'];
            $_SESSION['foto_dpr'] = $data['foto'];
            $_SESSION['nama_dpr'] = $data['nama'];
            header("Location: ../dashboard_dpr/index.php");
            exit;
        }
    }
    $error = "Email atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Anggota DPD RI – Portal Internal</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #cbd5e1 100%);
            overflow: hidden;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transform: translateY(0);
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
        .input-group:focus-within label {
            color: #2563eb;
            letter-spacing: 0.3em;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full animate__animated animate__fadeIn">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-700 text-white rounded-[2rem] shadow-2xl shadow-blue-300 mb-6 rotate-3 animate__animated animate__jackInTheBox">
                <i class="fa-solid fa-shield-halved text-3xl"></i>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight animate__animated animate__fadeInDown">Portal Internal</h1>
            <p class="text-slate-500 font-medium mt-2 animate__animated animate__fadeInUp animate__delay-1s">Sistem Informasi Konstituen DPD RI</p>
        </div>

        <div class="login-card p-10 rounded-[3.5rem] shadow-2xl animate__animated animate__zoomIn">
            
            <?php if($error): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-[13px] font-bold rounded-2xl flex items-center gap-3 animate__animated animate__shakeX">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="input-group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 transition-all duration-300">Email Otoritas</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" name="email" 
                               class="w-full pl-11 pr-5 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white outline-none transition-all font-semibold" 
                               placeholder="admin@dpd.go.id" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 transition-all duration-300">Kunci Keamanan</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" 
                               class="w-full pl-11 pr-5 py-4 bg-white/50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white outline-none transition-all font-semibold" 
                               placeholder="••••••••" required>
                    </div>
                </div>

                <div class="flex items-center justify-between px-1 animate__animated animate__fadeIn animate__delay-1s">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded text-blue-600 border-slate-300 focus:ring-blue-500 transition-all">
                        <span class="text-xs font-bold text-slate-500 group-hover:text-slate-700">Tetap Masuk</span>
                    </label>
                </div>

                <button name="login" class="w-full py-5 bg-blue-700 hover:bg-blue-800 text-white font-black rounded-2xl shadow-xl shadow-blue-200 transition-all active:scale-95 flex items-center justify-center gap-3 uppercase tracking-widest text-xs animate__animated animate__fadeInUp animate__delay-1s">
                    Verifikasi Akses <i class="fa-solid fa-circle-right"></i>
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-slate-100 text-center animate__animated animate__fadeIn animate__delay-2s">
                <a href="../public/index.php" class="group text-xs font-black text-slate-400 hover:text-blue-600 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fa-solid fa-chevron-left transition-transform group-hover:-translate-x-1"></i> Kembali ke Publik
                </a>
            </div>
        </div>

        <p class="text-center mt-10 text-slate-400 text-[10px] font-bold uppercase tracking-[0.5em] animate__animated animate__fadeIn">
            Secured by Cyber DPD RI
        </p>
    </div>

</body>
</html>