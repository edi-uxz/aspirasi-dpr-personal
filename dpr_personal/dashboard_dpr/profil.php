<?php
session_start();
include '../config/koneksi.php';

// CEK LOGIN DPR
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Legislator | Dashboard DPR</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .profile-card { background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; }
        .section-card { border-radius: 2rem; transition: transform 0.3s ease; }
        .section-card:hover { transform: translateY(-5px); }
        .badge-blue { background: #eff6ff; color: #2563eb; }
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
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl font-semibold transition-all">
                    <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
                </a>
                <a href="aspirasi_masuk.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl font-semibold transition-all">
                    <i class="fa-solid fa-envelope-open-text w-5"></i> Aspirasi
                </a>
                <a href="agenda.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl font-semibold transition-all">
                    <i class="fa-solid fa-calendar-check w-5"></i> Agenda
                </a>
                <a href="berita.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl font-semibold transition-all">
                    <i class="fa-solid fa-newspaper w-5"></i> Berita
                </a>

                <div class="my-6 border-t border-slate-800/50 mx-2"></div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 ml-4">Pengaturan</p>
                <a href="../public/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-xl font-semibold transition-all">
                    <i class="fa-solid fa-globe w-5"></i> View Publik
                </a>
                <a href="profil.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20">
                    <i class="fa-solid fa-user-gear w-5"></i> Profil Akun
                </a>
            </nav>
        </div>

        <div class="mt-auto p-6 border-t border-slate-800/50 bg-slate-900/50">
            <a href="../auth/logout.php" onclick="return confirm('Keluar dari sistem?')" class="flex items-center justify-center gap-3 px-4 py-3 bg-red-500/10 text-red-500 border border-red-500/20 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-red-500 hover:text-white transition-all">
                <i class="fa-solid fa-power-off"></i> Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto">
        <header class="glass-header sticky top-0 z-40 px-10 py-5 flex justify-between items-center">
            <div>
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Profil Resmi</h2>
                <h1 class="text-xl font-black text-slate-800">Data Legislator</h1>
            </div>
            <div class="flex items-center gap-3 bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-xs font-bold text-emerald-700 uppercase italic">Status Jabatan Aktif</span>
            </div>
        </header>

        <div class="p-10 max-w-6xl mx-auto animate__animated animate__fadeIn">
            
            <div class="profile-card rounded-[3rem] p-10 mb-10 shadow-sm flex flex-col md:flex-row items-center gap-10">
                <div class="relative w-40 h-40 md:w-48 md:h-48 rounded-[2.5rem] overflow-hidden flex-shrink-0">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-[2.5rem] blur opacity-25"></div>
                        <img 
                            src="../assets/img/edi.jpg"
                            alt="Foto DPR"
                            class="w-full h-full object-cover relative z-10"
                            onerror="this.style.display='none'"
                        />
                </div>
                <div class="text-center md:text-left">
                    <span class="px-4 py-1 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Anggota DPD RI</span>
                    <h2 class="text-3xl font-black text-slate-800 mt-4 mb-2">Edi Kurniawan, A.Md.Kom</h2>
                    <p class="text-lg font-bold text-blue-600 flex items-center justify-center md:justify-start gap-2">
                        <i class="fa-solid fa-map-pin text-sm"></i> Dapil Provinsi Lampung
                    </p>
                    <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3">
                        <div class="px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-sm font-bold text-slate-600">
                            Masa Bakti: 2024 – 2029
                        </div>
                        <div class="px-4 py-2 bg-white border border-slate-200 rounded-xl shadow-sm text-sm font-bold text-slate-600">
                            Komite IV
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white p-8 section-card shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-blue-600 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-address-card"></i> Biografi Singkat
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pendidikan Terakhir</p>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-graduation-cap text-blue-500 mt-1"></i>
                                    <span class="text-sm font-bold text-slate-700">LSPR Jakarta (Master Degree) <br><small class="text-slate-400 font-medium">2023 – 2024</small></span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="fa-solid fa-graduation-cap text-blue-500 mt-1"></i>
                                    <span class="text-sm font-bold text-slate-700">Monash University Malaysia (Bachelor) <br><small class="text-slate-400 font-medium">2018 – 2020</small></span>
                                </li>
                            </ul>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Lahir</p>
                                <p class="text-xs font-bold text-slate-700 italic text-wrap">Bandar Lampung, 07 Jan 1999</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Jabatan</p>
                                <p class="text-xs font-bold text-slate-700 italic">Anggota Komite IV</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 text-white p-8 section-card shadow-xl shadow-blue-100 relative overflow-hidden">
                    <i class="fa-solid fa-building absolute -right-10 -bottom-10 text-9xl opacity-5"></i>
                    <h3 class="text-sm font-black text-blue-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot"></i> Alamat Kantor Pusat
                    </h3>
                    <p class="text-lg font-medium leading-relaxed mb-6">
                        Gedung A DPD RI Lt. 03 Ruang 308 <br>
                        Jl. Gatot Subroto No. 6 Jakarta 10270
                    </p>
                    <div class="space-y-3 border-t border-slate-800 pt-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-blue-400">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <p class="text-sm font-bold">(021) 57897228</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-blue-400">
                                <i class="fa-solid fa-print"></i>
                            </div>
                            <p class="text-sm font-bold">(021) 57897229</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 section-card shadow-sm border border-slate-100 lg:col-span-2">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <h3 class="text-sm font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-briefcase"></i> Pelaksanaan Tugas & Lingkup Komite IV
                        </h3>
                        <span class="text-[10px] font-black text-slate-400 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-tighter">
                            Peraturan DPD RI No. 2 Tahun 2024
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm italic">
                                <span class="w-2 h-2 bg-blue-600 rounded-full"></span> Sub-Bidang Fokus:
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <?php 
                                $fokus = ["APBN", "Pajak", "UMKM", "Koperasi", "Perbankan", "Investasi", "BUMN", "Statistik"];
                                foreach($fokus as $f) echo "<span class='px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold'>$f</span>";
                                ?>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-800 mb-4 flex items-center gap-2 text-sm italic">
                                <span class="w-2 h-2 bg-blue-600 rounded-full"></span> Fungsi Utama:
                            </h4>
                            <ul class="space-y-2">
                                <li class="text-xs font-medium text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-check text-blue-500"></i> Legislasi RUU Perimbangan Keuangan
                                </li>
                                <li class="text-xs font-medium text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-check text-blue-500"></i> Pengawasan Hasil Pemeriksaan BPK
                                </li>
                                <li class="text-xs font-medium text-slate-600 flex items-center gap-2">
                                    <i class="fa-solid fa-check text-blue-500"></i> Pertimbangan Pemilihan Anggota BPK
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10">
                <p class="text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Instansi Mitra Kerja Strategis</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <?php 
                    $mitra = ["Kemenkeu", "Bappenas", "Kemenkop UKM", "BKPM", "Kemenperin"];
                    foreach($mitra as $m): ?>
                    <div class="px-6 py-3 bg-white border border-slate-100 rounded-2xl shadow-sm flex items-center gap-3">
                        <i class="fa-solid fa-landmark text-blue-600"></i>
                        <span class="text-xs font-black text-slate-700 uppercase"><?= $m ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </main>

</body>
</html>