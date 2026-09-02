<?php
session_start();
include "../config/koneksi.php";

// ================== CEK LOGIN DPR ==================
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// ================== AMBIL SEMUA ASPIRASI ==================
// Kita gunakan FIELD() untuk mengurutkan status: Masuk (1), Diproses (2), Selesai (3)
$aspirasi = mysqli_query($koneksi, "
    SELECT 
        a.id,
        a.judul,
        a.isi_aspirasi,
        a.status,
        a.tanggal_aspirasi, 
        b.nama_bidang,
        k.nama_kabupaten
    FROM aspirasi a
    JOIN bidang b ON a.bidang_id = b.id
    LEFT JOIN kabupaten k ON a.kabupaten_id = k.id
    ORDER BY FIELD(a.status, 'Masuk', 'Diproses', 'Selesai') ASC, a.tanggal_aspirasi DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspirasi Masuk | Dashboard Legislator</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .table-container { border-radius: 1.5rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        
        /* Warna Status Dinamis */
        .badge-Masuk { @apply bg-amber-100 text-amber-600 border-amber-200; }
        .badge-Diproses { @apply bg-blue-100 text-blue-600 border-blue-200; }
        .badge-Selesai { @apply bg-emerald-100 text-emerald-600 border-emerald-200; }
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
                <a href="agenda.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20">
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
                <a href="profil.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-xl font-semibold transition-all">
                    <i class="fa-solid fa-user-gear w-5"></i> Profil Akun
                </a>
            </nav>
        </div>

        <div class="mt-auto p-6 border-t border-slate-800/50 bg-slate-900/50">
            <a href="../auth/logout.php" 
               onclick="return confirm('Apakah Anda yakin ingin keluar?')"
               class="flex items-center justify-center gap-3 px-4 py-3 bg-red-500/10 text-red-500 border border-red-500/20 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-red-500 hover:text-white transition-all duration-300 group">
                <i class="fa-solid fa-power-off group-hover:rotate-90 transition-transform"></i>
                Keluar Sistem
            </a>
        </div>
    </aside>

    <main class="flex-1">
        <header class="glass-header sticky top-0 z-40 px-10 py-5 flex justify-between items-center">
            <div>
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Sistem Aspirasi</h2>
                <h1 class="text-xl font-black text-slate-800">Manajemen Aspirasi</h1>
            </div>
            <div class="flex items-center gap-4">
                <p class="text-sm font-bold text-slate-700"><?= htmlspecialchars($_SESSION['nama_dpr']); ?></p>
                <div class="w-10 h-10 rounded-full border-2 border-white shadow-md overflow-hidden">
                    <img src="../assets/img/edi.jpg" class="w-full h-full object-cover" />
                </div>
            </div>
        </header>

        <div class="p-10 max-w-7xl mx-auto animate__animated animate__fadeIn">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                        <i class="fa-solid fa-inbox text-blue-600"></i> List Aspirasi
                    </h3>
                    <p class="text-slate-500 mt-1">Daftar semua aspirasi masyarakat berdasarkan status penanganan.</p>
                </div>
            </div>

            <div class="bg-white table-container border border-slate-100 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">No</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Judul Aspirasi</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Bidang</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (mysqli_num_rows($aspirasi) > 0): $no=1; ?>
                                <?php while($a = mysqli_fetch_assoc($aspirasi)): 
                                    // Tentukan class warna berdasarkan status
                                    $statusClass = "bg-amber-100 text-amber-600 border-amber-200"; // Default Masuk
                                    if($a['status'] == 'Diproses') $statusClass = "bg-blue-100 text-blue-600 border-blue-200";
                                    if($a['status'] == 'Selesai') $statusClass = "bg-emerald-100 text-emerald-600 border-emerald-200";
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-400"><?= $no++ ?></td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-bold text-slate-700 line-clamp-1"><?= htmlspecialchars($a['judul']) ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-bold px-3 py-1 bg-slate-100 text-slate-600 rounded-lg">
                                            <?= htmlspecialchars($a['nama_bidang']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[10px] font-black uppercase px-3 py-1 rounded-full border <?= $statusClass ?>">
                                            <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i> <?= $a['status'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-500">
                                        <?= date('d M Y', strtotime($a['tanggal_aspirasi'])) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="aspirasi_detail.php?id=<?= $a['id'] ?>" 
                                               class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition-all">
                                                Detail
                                            </a>
                                            
                                            <?php if($a['status'] == 'Masuk'): ?>
                                            <a href="aspirasi_update_status.php?id=<?= $a['id'] ?>&status=Diproses" 
                                               onclick="return confirm('Pindahkan ke status Diproses?')"
                                               class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-100 transition-all">
                                                Proses
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>