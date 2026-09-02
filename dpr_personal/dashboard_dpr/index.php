<?php
session_start();
include "../config/koneksi.php";

// =========================
// CEK LOGIN DPR
// =========================
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// =========================
// STATISTIK ASPIRASI
// =========================
$total_aspirasi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi"))['total'];
$aspirasi_masuk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi WHERE status='Masuk'"))['total'];
$aspirasi_proses = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi WHERE status='Diproses'"))['total'];
$aspirasi_selesai = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM aspirasi WHERE status='Selesai'"))['total'];

// =========================
// BERITA TERBARU (3 TERAKHIR)
// =========================
$berita_query = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal_post DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Legislator – Sistem Aspirasi</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; overflow-x: hidden; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(226, 232, 240, 0.8); }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .blue-gradient-bg { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-72 sidebar-gradient text-slate-300 hidden lg:flex flex-col sticky top-0 h-screen shadow-2xl">
    <div class="p-8">
        <div class="flex items-center gap-3 mb-10">
            <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-building-columns text-white text-xl"></i>
            </div>
            <span class="font-black text-white tracking-wider text-sm uppercase">Portal Legislator</span>
        </div>

        <nav class="space-y-2">
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 ml-4">Utama</p>
            
            <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            
            <a href="aspirasi_masuk.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-xl font-semibold transition-all">
                <i class="fa-solid fa-envelope-open-text w-5"></i> Aspirasi
            </a>
            
            <a href="agenda.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-xl font-semibold transition-all">
                <i class="fa-solid fa-calendar-check w-5"></i> Agenda
            </a>
            
            <a href="berita.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-xl font-semibold transition-all">
                <i class="fa-solid fa-newspaper w-5"></i> Berita
            </a>

            <div class="my-6 border-t border-slate-800/50 mx-2"></div>

            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 ml-4">Evaluasi</p>
            
            <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 hover:bg-emerald-600 hover:text-white rounded-xl font-semibold transition-all group">
                <i class="fa-solid fa-print w-5 group-hover:animate-pulse"></i> Laporan Cetak
            </a>

            <div class="my-6 border-t border-slate-800/50 mx-2"></div>

            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 ml-4">Pengaturan</p>
            
            <a href="../public/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-xl font-semibold transition-all">
                <i class="fa-solid fa-globe w-5"></i> View Publik
            </a>
            
            <a href="profil.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-xl font-semibold transition-all">
                <i class="fa-solid fa-user-gear w-5"></i> Profil Akun
            </a>

            <a href="../auth/logout.php" class="flex lg:hidden items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-xl font-bold transition-all">
                <i class="fa-solid fa-power-off w-5"></i> Keluar
            </a>
        </nav>
    </div>

    <div class="mt-auto p-6 border-t border-slate-800/50 bg-slate-900/50">
        <a href="../auth/logout.php" 
            onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')"
            class="flex items-center justify-center gap-3 px-4 py-3 bg-red-500/10 text-red-500 border border-red-500/20 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-red-500 hover:text-white transition-all duration-300 shadow-lg group">
            <i class="fa-solid fa-power-off group-hover:rotate-90 transition-transform"></i>
            Keluar Sistem
        </a>
    </div>
</aside>

    <main class="flex-1 flex flex-col">
        <header class="glass-header sticky top-0 z-40 px-10 py-5 flex justify-between items-center">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">
                Ringkasan Kinerja
            </h1>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                        Status: Online
                    </p>
                    <p class="text-sm font-bold text-slate-700">
                        <?= htmlspecialchars($_SESSION['nama_dpr']); ?>
                    </p>
                </div>

                <div class="w-10 h-10 rounded-full border-2 border-white shadow-md overflow-hidden">
                    <img 
                        src="../assets/img/edi.jpg"
                        alt="Foto DPR"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'"
                    />
                </div>

                <a href="../auth/logout.php"
                    onclick="return confirm('Yakin ingin logout?')"
                    class="text-xs font-bold text-red-600 hover:text-red-800 transition">
                    Logout
                </a>
            </div>
        </header>

        <div class="p-10 max-w-7xl mx-auto w-full space-y-8">
            
            <section class="animate__animated animate__fadeIn">
                <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight">👋 Selamat Datang, <?= htmlspecialchars($_SESSION['nama_dpr']); ?></h2>
                            <p class="text-slate-500 mt-2 max-w-2xl">
                                Pantau aspirasi masyarakat Lampung dan kelola agenda kerja Anda melalui satu panel terintegrasi.
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <a href="aspirasi_masuk.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-100 flex items-center gap-2">
                                <i class="fa-solid fa-magnifying-glass"></i> Cek Aspirasi
                            </a>
                            <a href="laporan.php" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-100 flex items-center gap-2">
                                <i class="fa-solid fa-print"></i> Cetak Laporan
                            </a>
                        </div>
                    </div>
                    <i class="fa-solid fa-landmark absolute -bottom-4 -right-4 text-[120px] text-slate-50"></i>
                </div>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate__animated animate__fadeInUp">
                <div class="stat-card bg-white p-6 rounded-[2rem]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-slate-100 p-3 rounded-2xl text-slate-600"><i class="fa-solid fa-layer-group"></i></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800"><?= $total_aspirasi ?></h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Aspirasi Terkirim</p>
                </div>
                <div class="stat-card bg-white p-6 rounded-[2rem]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-amber-50 p-3 rounded-2xl text-amber-600"><i class="fa-solid fa-inbox"></i></div>
                        <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Baru</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800"><?= $aspirasi_masuk ?></h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Belum Dibaca</p>
                </div>
                <div class="stat-card bg-white p-6 rounded-[2rem]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-50 p-3 rounded-2xl text-blue-600"><i class="fa-solid fa-spinner fa-spin"></i></div>
                        <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Aktif</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800"><?= $aspirasi_proses ?></h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Sedang Diproses</p>
                </div>
                <div class="stat-card bg-white p-6 rounded-[2rem]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-emerald-50 p-3 rounded-2xl text-emerald-600"><i class="fa-solid fa-check-double"></i></div>
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Selesai</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800"><?= $aspirasi_selesai ?></h3>
                    <p class="text-xs font-medium text-slate-400 mt-1">Tuntas Dikerjakan</p>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <section class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-lg font-black text-slate-800">🗞️ Update Berita Terbaru</h3>
                            <a href="berita.php" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua <i class="fa-solid fa-arrow-right ml-1"></i></a>
                        </div>
                        
                        <div class="space-y-4">
                            <?php if(mysqli_num_rows($berita_query) > 0): ?>
                                <?php while($b = mysqli_fetch_assoc($berita_query)): ?>
                                    <div class="group flex items-center justify-between p-5 bg-slate-50 rounded-2xl border border-transparent hover:border-blue-200 hover:bg-white transition-all">
                                        <div class="flex items-center gap-5">
                                            <div class="hidden sm:flex w-12 h-12 bg-white rounded-xl items-center justify-center text-blue-600 shadow-sm font-black text-xs text-center leading-tight">
                                                <?= date('d', strtotime($b['tanggal_post'])) ?><br><?= date('M', strtotime($b['tanggal_post'])) ?>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors line-clamp-1"><?= htmlspecialchars($b['judul']) ?></h4>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">
                                                    Post: <?= date('d-m-Y H:i', strtotime($b['tanggal_post'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                        <a href="berita.php" class="p-2 text-slate-300 group-hover:text-blue-600 transition-all"><i class="fa-solid fa-circle-arrow-right text-xl"></i></a>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-10">
                                    <img src="https://illustrations.popsy.co/slate/empty-folder.svg" class="w-32 mx-auto opacity-50 mb-4">
                                    <p class="text-slate-400 font-bold">Belum ada berita yang diterbitkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl text-white">
                        <h3 class="text-lg font-black mb-6 tracking-tight">Akses Cepat</h3>
                        <div class="space-y-3">
                            <a href="laporan.php" class="flex items-center justify-between w-full p-4 bg-emerald-600/10 border border-emerald-500/20 rounded-2xl hover:bg-emerald-600 transition-all group">
                                <span class="text-sm font-bold flex items-center gap-3 text-emerald-500 group-hover:text-white">
                                    <i class="fa-solid fa-file-invoice"></i> Laporan Tahunan
                                </span>
                                <i class="fa-solid fa-print text-xs opacity-0 group-hover:opacity-100 transition-all text-white"></i>
                            </a>
                            <a href="agenda.php" class="flex items-center justify-between w-full p-4 bg-slate-800 rounded-2xl hover:bg-blue-600 transition-all group">
                                <span class="text-sm font-bold flex items-center gap-3">
                                    <i class="fa-solid fa-calendar text-slate-500 group-hover:text-white"></i> Agenda Kerja
                                </span>
                                <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all"></i>
                            </a>
                            <a href="../public/transparansi.php" class="flex items-center justify-between w-full p-4 bg-slate-800 rounded-2xl hover:bg-blue-600 transition-all group">
                                <span class="text-sm font-bold flex items-center gap-3">
                                    <i class="fa-solid fa-chart-line text-slate-500 group-hover:text-white"></i> Laporan Transparansi
                                </span>
                                <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-all"></i>
                            </a>
                        </div>
                    </div>

                    <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="font-black text-lg leading-tight">Butuh Bantuan Sistem?</h4>
                            <p class="text-blue-100 text-xs mt-2 font-medium opacity-80">Hubungi tim IT Sekretariat DPD untuk kendala teknis dashboard.</p>
                        </div>
                        <i class="fa-solid fa-headset absolute -bottom-4 -right-2 text-7xl text-blue-500 opacity-40"></i>
                    </div>
                </section>
            </div>
        </div>
    </main>

</body>
</html>