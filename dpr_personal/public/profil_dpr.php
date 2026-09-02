<?php
// profil_dpr.php
include "../config/koneksi.php";

// 1. Ambil Data Anggota DPR
$query_dpr = mysqli_query($koneksi, "SELECT * FROM anggota_dpr WHERE id = 1");
$dpr = mysqli_fetch_assoc($query_dpr);

// 2. Ambil Data Aspirasi
$query_aspirasi = mysqli_query($koneksi, "SELECT * FROM aspirasi ORDER BY created_at DESC");
$jml_aspirasi = mysqli_num_rows($query_aspirasi);

// 3. Ambil Data Kerja Nyata
$query_kerja_nyata = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal_post DESC");
$jml_kerja = mysqli_num_rows($query_kerja_nyata);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $dpr['nama'] ?> | Profil Resmi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.9)), 
                        url('../assets/img/i.jpeg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            color: #f8fafc;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        .glass-nav {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2rem;
        }

        .tab-active { 
            color: #818cf8; 
            border-bottom: 2px solid #6366f1;
        }
        
        .tab-inactive { 
            color: #475569; 
            border-bottom: 2px solid transparent;
        }

        .insta-grid-item { 
            aspect-ratio: 1 / 1; 
            border: 0.5px solid rgba(255, 255, 255, 0.05);
        }

        .profile-ring {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 4px;
            box-shadow: 0 0 30px rgba(79, 70, 229, 0.3);
        }
    </style>
</head>
<body class="pb-32">

<!-- Nav Atas -->
<nav class="sticky top-0 z-50 glass-nav px-6 py-5 text-center">
    <h1 class="text-[10px] font-black uppercase tracking-[0.3em] text-white">
        Profil <span class="text-indigo-500 italic">Dewan</span>
    </h1>
</nav>

<main class="max-w-2xl mx-auto pt-8">
    <!-- Header Profil -->
    <header class="flex flex-col items-center px-6 mb-10 text-center">
        <!-- Foto Profil -->
        <div class="w-32 h-32 flex-shrink-0 mb-6 relative">
            <div class="w-full h-full rounded-[2.5rem] profile-ring">
                <div class="w-full h-full rounded-[2.3rem] bg-slate-900 p-1">
                    <img src="../assets/img/<?= $dpr['foto'] ?>" 
                         class="w-full h-full rounded-[2.1rem] object-cover grayscale hover:grayscale-0 transition-all duration-500" 
                         onerror="this.src='https://ui-avatars.com/api/?name=Dewan&background=1e293b&color=fff'">
                </div>
            </div>
            <div class="absolute -bottom-2 -right-2 bg-indigo-600 w-8 h-8 rounded-full flex items-center justify-center border-4 border-slate-950 shadow-lg">
                <i class="fas fa-check text-[10px] text-white"></i>
            </div>
        </div>

        <!-- Nama & Bio -->
        <div class="w-full">
            <h2 class="text-xl font-black text-white italic tracking-tight mb-2 uppercase"><?= $dpr['nama'] ?></h2>
            
            <div class="flex flex-wrap justify-center gap-3 mb-6 text-[9px] font-black uppercase tracking-widest text-indigo-400">
                <span class="bg-indigo-500/10 px-3 py-1 rounded-lg border border-indigo-500/20"><?= $dpr['jabatan'] ?></span>
                <span class="bg-indigo-500/10 px-3 py-1 rounded-lg border border-indigo-500/20"><?= $dpr['daerah_pemilihan'] ?></span>
            </div>

            <!-- Stats -->
            <div class="flex justify-center gap-12 mb-8 bg-white/5 py-4 rounded-[1.5rem] border border-white/5">
                <div class="text-center">
                    <p class="text-lg font-black text-white"><?= $jml_aspirasi ?></p>
                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-[0.2em]">Aspirasi</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-black text-white"><?= $jml_kerja ?></p>
                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-[0.2em]">Kegiatan</p>
                </div>
            </div>

            <!-- Visi/Kontak -->
            <div class="glass-card p-6 mb-8 text-left relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fas fa-quote-right text-4xl"></i>
                </div>
                <p class="text-[12px] text-slate-300 leading-relaxed font-medium mb-4 italic italic">
                    "<?= $dpr['visi'] !== '-' ? $dpr['visi'] : 'Melayani dengan integritas tinggi untuk aspirasi rakyat.' ?>"
                </p>
                <div class="flex items-center gap-2 text-indigo-400">
                    <i class="fas fa-envelope text-[10px]"></i>
                    <p class="text-[10px] font-black uppercase tracking-tighter"><?= $dpr['email'] ?></p>
                </div>
            </div>

            <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-[1.5rem] text-[10px] font-black shadow-xl shadow-indigo-900/20 uppercase tracking-[0.2em] transform active:scale-95 transition-all">
                Kirim Pesan Privat
            </button>
        </div>
    </header>

    <!-- Menu Tab (Instagram Style) -->
    <div class="flex border-b border-white/5 sticky top-[68px] z-40 bg-slate-950/80 backdrop-blur-md">
        <button onclick="switchTab('aspirasi')" id="btn-aspirasi" class="flex-1 py-4 flex flex-col items-center gap-1 text-[9px] font-black tracking-widest tab-active transition-all">
            <i class="fas fa-th-large text-sm mb-1"></i> ASPIRASI
        </button>
        <button onclick="switchTab('kerja')" id="btn-kerja" class="flex-1 py-4 flex flex-col items-center gap-1 text-[9px] font-black tracking-widest tab-inactive transition-all">
            <i class="fas fa-play-circle text-sm mb-1"></i> KERJA NYATA
        </button>
    </div>

    <!-- Konten Tab -->
    <div class="bg-black/20 min-h-screen">
        <!-- Aspirasi -->
        <div id="content-aspirasi" class="grid grid-cols-3 gap-0.5 mt-0.5">
            <?php while($row = mysqli_fetch_assoc($query_aspirasi)): 
                $foto = $row['foto'] ? "../assets/img/".$row['foto'] : "https://via.placeholder.com/400?text=Post";
            ?>
            <div class="insta-grid-item relative group overflow-hidden bg-slate-900">
                <img src="<?= $foto ?>" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-500 grayscale group-hover:grayscale-0">
                <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center p-4">
                    <i class="fas fa-comment text-white text-xs mr-2"></i>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Kerja Nyata -->
        <div id="content-kerja" class="hidden grid grid-cols-3 gap-0.5 mt-0.5">
            <?php while($row_kerja = mysqli_fetch_assoc($query_kerja_nyata)): 
                $foto_kerja = $row_kerja['gambar'] ? "../assets/img/".$row_kerja['gambar'] : "https://via.placeholder.com/400?text=Aktifitas";
            ?>
            <div class="insta-grid-item relative group overflow-hidden bg-slate-900">
                <img src="<?= $foto_kerja ?>" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition duration-500">
                <div class="absolute top-2 right-2">
                    <i class="fas fa-certificate text-indigo-500 text-[10px]"></i>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>

<!-- Floating Nav (Konsisten) -->
<div class="fixed bottom-0 left-0 right-0 p-6 z-50 pointer-events-none">
    <nav class="max-w-md mx-auto bg-slate-950/90 backdrop-blur-2xl border border-white/10 rounded-[2.8rem] px-8 py-5 flex justify-between items-center shadow-2xl pointer-events-auto">
        <a href="index.php" class="text-slate-500 hover:text-white transition-all"><i class="fas fa-home text-xl"></i></a>
        <a href="cari_laporan.php" class="text-slate-500 hover:text-white transition-all"><i class="fas fa-search text-xl"></i></a>
        
        <a href="buat_laporan.php" class="relative -mt-16 group">
            <div class="absolute -inset-2 bg-indigo-500 rounded-full blur opacity-40 group-hover:opacity-80 transition duration-500"></div>
            <div class="relative w-16 h-16 bg-indigo-600 rounded-full border-[6px] border-slate-950 text-white flex items-center justify-center shadow-2xl transform active:scale-90 transition">
                <i class="fas fa-plus text-2xl"></i>
            </div>
        </a>

        <a href="chat_umum.php" class="text-slate-500 hover:text-white transition-all"><i class="fas fa-comment-dots text-xl"></i></a>
        <a href="profil_dpr.php" class="text-indigo-400 scale-110"><i class="fas fa-user text-xl"></i></a>
    </nav>
</div>

<script>
function switchTab(tab) {
    const btnAspirasi = document.getElementById('btn-aspirasi');
    const btnKerja = document.getElementById('btn-kerja');
    const contentAspirasi = document.getElementById('content-aspirasi');
    const contentKerja = document.getElementById('content-kerja');

    if (tab === 'aspirasi') {
        btnAspirasi.className = "flex-1 py-4 flex flex-col items-center gap-1 text-[9px] font-black tracking-widest tab-active transition-all";
        btnKerja.className = "flex-1 py-4 flex flex-col items-center gap-1 text-[9px] font-black tracking-widest tab-inactive transition-all";
        contentAspirasi.classList.remove('hidden');
        contentKerja.classList.add('hidden');
    } else {
        btnKerja.className = "flex-1 py-4 flex flex-col items-center gap-1 text-[9px] font-black tracking-widest tab-active transition-all";
        btnAspirasi.className = "flex-1 py-4 flex flex-col items-center gap-1 text-[9px] font-black tracking-widest tab-inactive transition-all";
        contentKerja.classList.remove('hidden');
        contentAspirasi.classList.add('hidden');
    }
}
</script>

</body>
</html>