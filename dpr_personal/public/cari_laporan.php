<?php
include "../config/koneksi.php";

// Menangkap input pencarian
$keyword = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';

// Query gabungan Aspirasi dan Berita
$sql = "SELECT 'aspirasi' as tipe, id, judul, foto as gambar, tanggal_aspirasi as tanggal FROM aspirasi 
        WHERE judul LIKE '%$keyword%' OR isi_aspirasi LIKE '%$keyword%'
        UNION
        SELECT 'berita' as tipe, id, judul, gambar, tanggal_post as tanggal FROM berita
        WHERE judul LIKE '%$keyword%' OR isi LIKE '%$keyword%'
        ORDER BY tanggal DESC";

$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelajahi | AspirasiRakyat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* Menggunakan gambar kirim.jpeg dengan overlay gelap agar elegan */
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.80)), 
                        url('../assets/img/kirim.jpeg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            color: #f8fafc; 
            margin: 0;
        }
        
        .explore-grid-item { 
            aspect-ratio: 1 / 1; 
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(5px);
        }
        
        .explore-grid-item:hover { 
            transform: scale(0.96);
            filter: brightness(1.2);
            z-index: 10;
        }
        
        .glass-nav {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .search-container input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .search-container input:focus {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(99, 102, 241, 0.5) !important;
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.2);
        }
    </style>
</head>
<body class="pb-40">

<!-- Header & Search -->
<nav class="sticky top-0 z-50 glass-nav px-4 py-5">
    <div class="max-w-4xl mx-auto">
        <form action="" method="GET" class="search-container relative flex items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" 
                       placeholder="Cari berita atau aspirasi warga..." 
                       class="w-full rounded-2xl py-3 pl-11 pr-4 text-sm transition-all placeholder:text-slate-500 outline-none">
            </div>
            <?php if($keyword): ?>
                <a href="cari_laporan.php" class="text-[10px] font-black uppercase tracking-widest text-indigo-400 bg-indigo-500/10 px-5 py-3 rounded-2xl border border-indigo-500/20">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</nav>

<main class="max-w-4xl mx-auto px-1 sm:px-4 mt-4">
    
    <!-- Info Status -->
    <div class="px-3 py-6">
        <?php if($keyword): ?>
            <h2 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em]">Hasil Pencarian</h2>
            <p class="text-xl text-white font-bold mt-1">Menampilkan <span class="italic text-indigo-300">"<?= htmlspecialchars($keyword) ?>"</span></p>
        <?php else: ?>
            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Discovery</h2>
            <p class="text-xl text-white font-bold mt-1 italic opacity-90">Jelajahi aspirasi publik.</p>
        <?php endif; ?>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-3 gap-1 sm:gap-3">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $tipe = $row['tipe'];
                $id = $row['id'];
                $path = ($tipe == 'aspirasi') ? '../assets/img/' : '../assets/img/berita/';
                $link = ($tipe == 'aspirasi') ? "aspirasi_detail.php?id=$id" : "berita_detail.php?id=$id";
                $gambar = !empty($row['gambar']) ? $path . $row['gambar'] : 'https://placehold.co/400x400/1e293b/475569?text=No+Image';
                ?>
                
                <a href="<?= $link ?>" class="explore-grid-item relative group overflow-hidden rounded-lg sm:rounded-2xl shadow-2xl">
                    <img src="<?= $gambar ?>" class="w-full h-full object-cover brightness-[0.8] group-hover:brightness-110 transition-all duration-700">
                    
                    <div class="absolute top-2 right-2 z-10">
                        <?php if($tipe == 'berita'): ?>
                            <div class="bg-indigo-600/80 backdrop-blur-md text-white p-2 rounded-xl shadow-lg border border-white/10">
                                <i class="fas fa-newspaper text-[10px]"></i>
                            </div>
                        <?php else: ?>
                            <div class="bg-slate-900/60 backdrop-blur-md text-white p-2 rounded-xl shadow-lg border border-white/10">
                                <i class="fas fa-comment-alt text-[10px]"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-4">
                        <p class="text-[10px] text-white font-bold line-clamp-2 leading-tight uppercase tracking-tighter mb-1"><?= $row['judul'] ?></p>
                        <div class="flex items-center gap-2">
                            <div class="h-[1px] w-4 bg-indigo-500 shadow-[0_0_8px_#6366f1]"></div>
                            <span class="text-[8px] text-indigo-300 uppercase font-black tracking-widest">
                                <?= date('d M Y', strtotime($row['tanggal'])) ?>
                            </span>
                        </div>
                    </div>
                </a>

                <?php
            }
        } else {
            echo '
            <div class="col-span-3 py-32 text-center">
                <div class="bg-white/5 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 border border-white/10 shadow-inner">
                    <i class="fas fa-search-minus text-slate-500 text-3xl"></i>
                </div>
                <h3 class="text-white font-black text-base uppercase tracking-widest italic">Data Tidak Ditemukan</h3>
                <p class="text-slate-500 text-[11px] mt-2 tracking-widest uppercase">Coba kata kunci lain</p>
            </div>';
        }
        ?>
    </div>
</main>

<!-- Bottom Navigation Bar -->
<div class="fixed bottom-0 left-0 right-0 p-6 z-50 pointer-events-none">
    <nav class="max-w-md mx-auto bg-slate-950/90 backdrop-blur-2xl border border-white/10 rounded-[2.8rem] px-8 py-5 flex justify-between items-center shadow-2xl pointer-events-auto">
        <a href="index.php" class="text-slate-500 hover:text-white transition-all"><i class="fas fa-home text-xl"></i></a>
        <a href="cari_laporan.php" class="text-indigo-400 scale-110"><i class="fas fa-search text-xl"></i></a>
        
        <a href="buat_laporan.php" class="relative -mt-16 group">
            <div class="absolute -inset-2 bg-indigo-500 rounded-full blur opacity-40 group-hover:opacity-80 transition duration-500"></div>
            <div class="relative w-16 h-16 bg-indigo-600 rounded-full border-[6px] border-slate-950 text-white flex items-center justify-center shadow-2xl transform active:scale-90 transition">
                <i class="fas fa-plus text-2xl"></i>
            </div>
        </a>

        <a href="chat_umum.php" class="text-slate-500 hover:text-white transition-all"><i class="far fa-comment-dots text-xl"></i></a>
        <a href="profil_dpr.php" class="text-slate-500 hover:text-white transition-all"><i class="far fa-user text-xl"></i></a>
    </nav>
</div>

</body>
</html>