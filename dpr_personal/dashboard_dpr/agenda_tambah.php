<?php
session_start();
include '../config/koneksi.php';

// CEK LOGIN DPR
if (!isset($_SESSION['login_dpr'])) {
    header("Location: ../auth/login_dpr.php");
    exit;
}

// PROSES SIMPAN
if (isset($_POST['simpan'])) {
    $judul      = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal    = $_POST['tanggal'];
    $waktu      = $_POST['waktu'];
    $lokasi     = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $query = "INSERT INTO agenda 
              (judul, tanggal, waktu, lokasi, deskripsi) 
              VALUES 
              ('$judul', '$tanggal', '$waktu', '$lokasi', '$deskripsi')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: agenda.php?status=success");
        exit;
    } else {
        $error = "Gagal menyimpan agenda!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Agenda | Dashboard Legislator</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
        .form-input { 
            transition: all 0.2s; 
            border: 1px solid #e2e8f0;
        }
        .form-input:focus { 
            border-color: #2563eb; 
            ring: 2px; 
            ring-color: #bfdbfe;
            outline: none;
        }
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
            </nav>
        </div>

        <div class="mt-auto p-6 border-t border-slate-800/50">
            <a href="../auth/logout.php" class="flex items-center justify-center gap-3 px-4 py-3 bg-red-500/10 text-red-500 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-red-500 hover:text-white transition-all">
                <i class="fa-solid fa-power-off"></i> Keluar
            </a>
        </div>
    </aside>

    <main class="flex-1">
        <header class="glass-header sticky top-0 z-40 px-10 py-5 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="agenda.php" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 transition-all text-slate-500">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-black text-slate-800">Tambah Agenda Baru</h1>
            </div>
        </header>

        <div class="p-10 max-w-4xl mx-auto animate__animated animate__fadeIn">
            
            <?php if (isset($error)) { ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 font-bold rounded-r-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= $error; ?>
                </div>
            <?php } ?>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-10">
                    <div class="mb-8">
                        <h3 class="text-lg font-black text-slate-800">Formulir Kegiatan</h3>
                        <p class="text-slate-500 text-sm">Lengkapi detail agenda kerja Anda di bawah ini.</p>
                    </div>

                    <form method="post" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Judul Agenda</label>
                                <input type="text" name="judul" placeholder="Contoh: Rapat Paripurna Ke-II" 
                                       class="w-full px-5 py-4 rounded-2xl bg-slate-50 form-input font-semibold text-slate-700" required>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal</label>
                                <div class="relative">
                                    <i class="fa-solid fa-calendar absolute left-5 top-4 text-slate-400"></i>
                                    <input type="date" name="tanggal" 
                                           class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 form-input font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Waktu</label>
                                <div class="relative">
                                    <i class="fa-solid fa-clock absolute left-5 top-4 text-slate-400"></i>
                                    <input type="time" name="waktu" 
                                           class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 form-input font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Lokasi Kegiatan</label>
                                <div class="relative">
                                    <i class="fa-solid fa-location-dot absolute left-5 top-4 text-slate-400"></i>
                                    <input type="text" name="lokasi" placeholder="Gedung DPRD Lt. 3 / Ruang Rapat" 
                                           class="w-full pl-12 pr-5 py-4 rounded-2xl bg-slate-50 form-input font-semibold text-slate-700" required>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Deskripsi / Keterangan</label>
                                <textarea name="deskripsi" rows="4" placeholder="Tuliskan detail singkat mengenai kegiatan ini..."
                                          class="w-full px-5 py-4 rounded-2xl bg-slate-50 form-input font-semibold text-slate-700"></textarea>
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col md:flex-row gap-4">
                            <button type="submit" name="simpan" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-100 transition-all flex items-center justify-center gap-3">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Agenda
                            </button>
                            <a href="agenda.php" class="px-8 py-4 bg-slate-100 text-slate-500 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="bg-slate-50 px-10 py-6 border-t border-slate-100 flex items-center gap-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Data akan langsung dipublikasikan ke publik setelah disimpan</p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>