<?php
session_start();
include '../config/koneksi.php';

// CEK LOGIN DPR
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// Ambil semua berita
$query = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal_post DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita | Dashboard Legislator</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .table-container { border-radius: 1.5rem; overflow: hidden; }
        .img-preview { object-fit: cover; border-radius: 0.75rem; }
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
                <a href="berita.php" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20">
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
                <h2 class="text-xs font-black text-slate-400 uppercase tracking-widest">Informasi Publik</h2>
                <h1 class="text-xl font-black text-slate-800">Manajemen Berita</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                        Status: Online
                    </p>
                    <p class="text-sm font-bold text-slate-700">
                        <?= htmlspecialchars($_SESSION['nama_dpr']); ?>
                    </p>
                </div>

                <!-- FOTO -->
                <div class="w-10 h-10 rounded-full border-2 border-white shadow-md overflow-hidden">
                    <img 
                        src="../assets/img/edi.jpg"
                        alt="Foto DPR"
                        class="w-full h-full object-cover"
                        onerror="this.style.display='none'"
                    />
                </div>

                <!-- LOGOUT -->
                <a href="../auth/logout.php"
                    onclick="return confirm('Yakin ingin logout?')"
                    class="text-xs font-bold text-red-600 hover:text-red-800 transition">
                    Logout
                </a>
            </div>
        </header>

        <div class="p-10 max-w-7xl mx-auto animate__animated animate__fadeIn">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                        <i class="fa-solid fa-paper-plane text-blue-600"></i> Publikasi Berita
                    </h3>
                    <p class="text-slate-500 mt-1">Kelola berita kegiatan dan informasi penting untuk masyarakat.</p>
                </div>
                <a href="berita_tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-blue-200 transition-all flex items-center gap-2 hover:scale-105">
                    <i class="fa-solid fa-plus"></i> Buat Berita Baru
                </a>
            </div>

            <div class="bg-white table-container shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-8 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center w-20">No</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Informasi Berita</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Pratinjau</th>
                                <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Tanggal Rilis</th>
                                <th class="px-8 py-4 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php if (mysqli_num_rows($query) > 0): $no=1; ?>
                                <?php while($row = mysqli_fetch_assoc($query)): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5 text-center text-sm font-bold text-slate-400"><?= $no++ ?></td>
                                    <td class="px-6 py-5">
                                        <p class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors line-clamp-2 max-w-md">
                                            <?= htmlspecialchars($row['judul']) ?>
                                        </p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <?php if($row['gambar']): ?>
                                            <img src="../assets/berita/<?= $row['gambar'] ?>" class="w-20 h-14 img-preview shadow-sm border border-slate-200" alt="Thumbnail">
                                        <?php else: ?>
                                            <div class="w-20 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 text-[10px] font-bold uppercase italic">
                                                No Image
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-semibold text-slate-600">
                                            <i class="fa-regular fa-calendar-check mr-2 text-blue-400"></i><?= date('d M Y', strtotime($row['tanggal_post'])) ?>
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-400 ml-6 uppercase"><?= date('H:i', strtotime($row['tanggal_post'])) ?> WIB</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="berita_edit.php?id=<?= $row['id'] ?>" 
                                               class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all"
                                               title="Edit">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <a href="berita_hapus.php?id=<?= $row['id'] ?>" 
                                               onclick="return confirm('Yakin hapus berita ini?')"
                                               class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all"
                                               title="Hapus">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                                                <i class="fa-solid fa-newspaper text-3xl"></i>
                                            </div>
                                            <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Belum ada berita yang diterbitkan</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Total Berita</p>
                    <p class="text-xl font-black text-blue-700"><?= mysqli_num_rows($query) ?></p>
                </div>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update Terakhir</p>
                    <p class="text-sm font-black text-slate-700">Hari ini, <?= date('H:i') ?> WIB</p>
                </div>
                <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Status Server</p>
                    <p class="text-sm font-black text-emerald-700">Online / Terhubung</p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>