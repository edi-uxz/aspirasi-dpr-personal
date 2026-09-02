<?php
include "../config/koneksi.php";

/* ⚠️ HALAMAN INTERNAL
Digunakan hanya oleh admin / setup awal untuk akun Anggota DPD RI
*/

$success = "";
$error = "";

if (isset($_POST['daftar'])) {

    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // CEK EMAIL
    $cek = mysqli_query($koneksi, "SELECT id FROM anggota_dpr WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar dalam sistem!";
    } else {

        // INSERT DENGAN DEFAULT VALUE DPD RI & KOMITE IV
        $simpan = mysqli_query($koneksi, "
            INSERT INTO anggota_dpr 
            (nama, email, password, foto, jabatan, komite, daerah_pemilihan, visi, misi, created_at)
            VALUES
            (
                '$nama',
                '$email',
                '$password',
                'default.jpg',
                'Anggota DPD RI',
                'Komite IV',
                'Provinsi Lampung',
                '-',
                '-',
                NOW()
            )
        ");

        if ($simpan) {
            $success = "Otoritas Berhasil: Akun DPD RI telah dibuat.";
        } else {
            $error = "Gagal sistem: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internal Setup – DPD RI</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f8fafc;
        }
        .admin-card {
            border: 1px solid rgba(255, 255, 255, 1);
        }
        .form-input:focus {
            transform: scale(1.01);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full animate__animated animate__fadeIn">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 text-white rounded-[2rem] shadow-2xl shadow-blue-200 mb-6 animate__animated animate__bounceIn">
                <i class="fa-solid fa-user-shield text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Setup Akun</h2>
            <p class="text-slate-500 text-sm font-medium">Halaman Khusus Administrator</p>
        </div>

        <div class="admin-card bg-white p-10 rounded-[3rem] shadow-2xl shadow-slate-200/60 animate__animated animate__fadeInUp">
            
            <?php if($success): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold rounded-2xl flex items-center gap-3 animate__animated animate__flipInX">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if($error): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 text-xs font-bold rounded-2xl flex items-center gap-3 animate__animated animate__shakeX">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div class="animate__animated animate__fadeInLeft animate__delay-1s" style="animation-delay: 0.2s;">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap Anggota</label>
                    <input type="text" name="nama" 
                           class="form-input w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700" 
                           placeholder="Nama & Gelar Lengkap" required>
                </div>

                <div class="animate__animated animate__fadeInLeft animate__delay-1s" style="animation-delay: 0.4s;">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Kredensial</label>
                    <input type="email" name="email" 
                           class="form-input w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700" 
                           placeholder="email@dpd.go.id" required>
                </div>

                <div class="animate__animated animate__fadeInLeft animate__delay-1s" style="animation-delay: 0.6s;">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Password Awal</label>
                    <input type="password" name="password" 
                           class="form-input w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 focus:bg-white outline-none transition-all font-semibold text-slate-700" 
                           placeholder="••••••••" required>
                </div>

                <button name="daftar" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-95 mt-4 flex items-center justify-center gap-3 uppercase tracking-widest text-[11px] animate__animated animate__fadeInUp animate__delay-1s" style="animation-delay: 0.8s;">
                    Daftarkan Akun <i class="fa-solid fa-plus-circle"></i>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-50 text-center animate__animated animate__fadeIn animate__delay-1s" style="animation-delay: 1s;">
                <a href="../auth/login_masyarakat.php" class="text-[10px] font-black text-slate-400 hover:text-blue-600 transition uppercase tracking-[0.2em]">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Login Utama
                </a>
            </div>
        </div>

        <div class="mt-8 p-4 bg-amber-50 rounded-2xl border border-amber-100 animate__animated animate__fadeInUp animate__delay-1s" style="animation-delay: 1.2s;">
            <p class="text-[9px] text-amber-700 font-bold leading-relaxed text-center uppercase tracking-tighter">
                Perhatian: Halaman ini hanya digunakan untuk inisialisasi akun pejabat. <br>Segala aktivitas pendaftaran dicatat oleh sistem keamanan.
            </p>
        </div>
    </div>

</body>
</html>