<?php
session_start();
include "../config/koneksi.php";

// Ambil data DPR (ID sesuai session jika sudah login)
$dpr_id = isset($_SESSION['dpr_id']) ? $_SESSION['dpr_id'] : 1;
$query_dpr = mysqli_query($koneksi, "SELECT * FROM anggota_dpr WHERE id = '$dpr_id'");
$dpr = mysqli_fetch_assoc($query_dpr);

/**
 * FUNGSI DATA
 */
function getAspirasiByStatus($koneksi, $status) {
    $sql = "SELECT a.*, b.nama_bidang, m.nama AS nama_asli 
            FROM aspirasi a 
            JOIN bidang b ON a.bidang_id = b.id 
            JOIN masyarakat m ON a.masyarakat_id = m.id 
            WHERE a.status = '$status' 
            ORDER BY a.tanggal_aspirasi DESC";
    return mysqli_query($koneksi, $sql);
}

function getBerita($koneksi) {
    $sql = "SELECT * FROM berita ORDER BY tanggal_post DESC LIMIT 10"; 
    return mysqli_query($koneksi, $sql);
}

function getAgenda($koneksi) {
    $sql = "SELECT * FROM agenda ORDER BY tanggal DESC LIMIT 10";
    return mysqli_query($koneksi, $sql);
}

$status_list = ['Masuk', 'Diproses', 'Selesai'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspirasi Rakyat | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            background-image: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.75)), 
                              url('../assets/img/dpr.jpeg'); 
            background-attachment: fixed;
            background-size: cover;
            background-position: center top;
            color: #ffffff; 
        }

        .card-elegant { 
            background: rgba(255, 255, 255, 0.98); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
            backdrop-filter: blur(4px);
        }
        .card-elegant:hover { 
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }

        .snap-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            gap: 1.5rem;
            padding: 20px 4px;
            scrollbar-width: none;
        }
        .snap-container::-webkit-scrollbar { display: none; }
        
        .snap-item { scroll-snap-align: start; flex: 0 0 85%; }

        @media (min-width: 1024px) { 
            .snap-item { flex: 0 0 calc(33.333% - 1rem); } 
        }

        .glass-nav {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-gradient {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="pb-32">

<!-- Header -->
<nav class="sticky top-0 z-50 glass-nav px-6 py-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="relative group cursor-pointer">
                <div class="absolute -inset-1 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full blur opacity-30 group-hover:opacity-70 transition duration-500"></div>
                <div class="relative w-11 h-11 rounded-full border-2 border-slate-600 overflow-hidden shadow-sm">
                    <img src="../assets/img/<?= !empty($dpr['foto']) ? $dpr['foto'] : 'edi.jpg' ?>" 
                         class="w-full h-full object-cover"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($dpr['nama']) ?>&background=4f46e5&color=fff'">
                </div>
            </div>
            <div>
                <h1 class="text-sm font-black tracking-tighter uppercase italic text-white">
                    Aspirasi<span class="text-indigo-400">Rakyat</span>
                </h1>
                <p class="text-[8px] font-bold text-slate-300 tracking-[0.3em] uppercase">Private Dashboard</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <?php if(!isset($_SESSION['dpr_id'])): ?>
                <a href="../auth/login_dpr.php" class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-2xl hover:bg-white hover:text-indigo-600 transition-all shadow-xl font-bold group">
                    <i class="fas fa-fingerprint text-xs"></i>
                    <span class="text-[10px] uppercase tracking-widest">Portal DPR</span>
                </a>
            <?php else: ?>
                <a href="logout.php" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white transition-all">
                    <i class="fas fa-power-off text-sm"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 mt-10 space-y-16">

    <!-- HERO SECTION -->
    <section class="px-2">
        <h2 class="text-4xl font-extrabold tracking-tighter text-white leading-tight drop-shadow-lg">
            Monitor <span class="text-gradient">Aspirasi</span>.<br>Kelola Masa Depan.
        </h2>
        <div class="h-1 w-20 bg-indigo-500 rounded-full mt-4 shadow-[0_0_15px_rgba(99,102,241,0.8)]"></div>
    </section>

    <!-- BERITA TERBARU -->
    <section class="mt-12">
        <div class="flex justify-between items-end mb-6 px-4">
            <h2 class="text-xl font-black text-white italic tracking-tight uppercase drop-shadow-lg">
                Warta <span class="text-indigo-500">Utama</span>
            </h2>
            <a href="berita.php" class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-indigo-400 transition-all border-b border-slate-800 pb-1">
                Lihat Semua
            </a>
        </div>

        <div class="snap-container no-scrollbar">
            <?php 
            $berita_data = getBerita($koneksi);
            while($b = mysqli_fetch_assoc($berita_data)): 
                // Logika Perbaikan Gambar Berita
                $nama_file_berita = $b['gambar'];
                $path_berita = "../assets/img/berita/" . $nama_file_berita;
                
                if(!empty($nama_file_berita) && file_exists($path_berita)) {
                    $src_berita = $path_berita;
                    $class_berita = "grayscale group-hover:grayscale-0 group-hover:scale-110 opacity-70 group-hover:opacity-100";
                } else {
                    $src_berita = "https://ui-avatars.com/api/?name=" . urlencode($b['judul']) . "&background=1e293b&color=fff&size=512";
                    $class_berita = "opacity-50";
                }
            ?>
            <div class="snap-item group">
                <div class="bg-white/5 backdrop-blur-md rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl transition-all duration-500 hover:border-indigo-500/30">
                    <div class="aspect-[16/10] relative overflow-hidden bg-slate-900">
                        <img src="<?= $src_berita; ?>" 
                             class="w-full h-full object-cover transition duration-700 <?= $class_berita; ?>">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                        <div class="absolute top-4 left-4">
                            <span class="bg-indigo-600 text-white text-[8px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                                <?= date('d M Y', strtotime($b['tanggal_post'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="font-black text-white text-md mb-3 line-clamp-2 leading-tight tracking-tight uppercase italic group-hover:text-indigo-400 transition-colors">
                            <?= $b['judul'] ?>
                        </h3>
                        <p class="text-[11px] text-slate-400 line-clamp-2 italic font-medium leading-relaxed opacity-70">
                            "<?= strip_tags($b['isi']) ?>"
                        </p>
                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-[8px] font-black text-slate-600 uppercase tracking-widest">Official Post</span>
                            <i class="fas fa-arrow-right text-indigo-500 text-xs transform group-hover:translate-x-2 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- AGENDA -->
    <section>
        <div class="flex justify-between items-end mb-6 px-2">
            <h2 class="text-xl font-black text-white italic tracking-tight uppercase drop-shadow-md">Agenda Terkini</h2>
            <a href="agenda.php" class="text-[10px] font-black uppercase tracking-widest text-slate-300 hover:text-indigo-400 transition-colors">Lihat Semua</a>
        </div>
        <div class="snap-container">
            <?php 
            $agenda_data = getAgenda($koneksi);
            while($ag = mysqli_fetch_assoc($agenda_data)): 
            ?>
            <div class="snap-item">
                <div class="card-elegant rounded-[2.5rem] p-8 border-l-[6px] border-l-indigo-500 relative overflow-hidden">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-indigo-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="far fa-calendar-alt text-xl"></i>
                        </div>
                        <span class="text-[9px] font-black text-white bg-slate-900 px-3 py-1 rounded-lg uppercase tracking-widest">
                            <?= date('H:i', strtotime($ag['waktu'])) ?> WIB
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-1 uppercase"><?= $ag['judul'] ?></h3>
                    <div class="flex items-center gap-2 text-indigo-600 mb-4 font-bold uppercase text-[9px] tracking-widest">
                        <i class="fas fa-map-marker-alt"></i> <?= $ag['lokasi'] ?>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-3"><?= $ag['deskripsi'] ?></p>
                    <div class="mt-4 text-[9px] font-bold text-slate-400 uppercase">
                        <?= date('d F Y', strtotime($ag['tanggal'])) ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- STATUS LAPORAN -->
    <div class="pt-8 pb-10">
        <?php foreach($status_list as $status): 
            $data = getAspirasiByStatus($koneksi, $status);
            $count = mysqli_num_rows($data);
        ?>
        <div class="mb-14">
            <div class="flex justify-between items-center mb-6 px-2">
                <h2 class="text-sm font-black text-white uppercase tracking-[0.3em] italic drop-shadow-md">Laporan <?= $status ?></h2>
                <span class="text-[10px] bg-indigo-500 text-white px-3 py-1 rounded-full font-black shadow-lg"><?= $count ?></span>
            </div>
            
            <div class="snap-container">
                <?php if($count > 0): while($row = mysqli_fetch_assoc($data)): 
                    // Logika Perbaikan Gambar Aspirasi
                    $nama_file_aspirasi = $row['foto'];
                    $path_aspirasi = "../assets/img/" . $nama_file_aspirasi;
                ?>
                    <div class="snap-item group">
                        <div class="card-elegant rounded-[2.5rem] overflow-hidden">
                            <div class="aspect-video bg-slate-800 overflow-hidden relative">
                                <?php if(!empty($nama_file_aspirasi) && file_exists($path_aspirasi)): ?>
                                    <img src="<?= $path_aspirasi; ?>" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition duration-500">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-slate-700">
                                        <i class="fas fa-file-alt text-slate-500 text-3xl"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-3 right-3">
                                    <span class="text-[7px] bg-white/90 text-slate-900 px-2 py-1 rounded font-black uppercase"><?= $row['nama_bidang'] ?></span>
                                </div>
                            </div>
                            <div class="p-7">
                                <h3 class="font-black text-slate-900 text-sm mb-4 line-clamp-1 uppercase italic group-hover:text-indigo-600 transition-colors"><?= $row['judul'] ?></h3>
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest"><?= $row['nama_asli'] ?></span>
                                    <a href="detail_aspirasi.php?id=<?= $row['id'] ?>" class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center hover:bg-indigo-600 transition-colors">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <div class="w-full py-12 text-center bg-white/5 backdrop-blur-sm rounded-[2.5rem] border border-white/10">
                        <p class="text-[10px] text-slate-300 uppercase font-black tracking-widest italic">Belum Ada Data <?= $status ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</main>

<!-- Bottom Nav -->
<div class="fixed bottom-0 left-0 right-0 p-6 z-50 pointer-events-none">
    <nav class="max-w-md mx-auto bg-slate-950/90 backdrop-blur-2xl border border-white/10 rounded-[2.8rem] px-8 py-5 flex justify-between items-center shadow-2xl pointer-events-auto">
        <a href="index.php" class="text-indigo-400 scale-110"><i class="fas fa-home text-xl"></i></a>
        <a href="cari_laporan.php" class="text-slate-500 hover:text-white transition-colors"><i class="fas fa-search text-xl"></i></a>
        
        <a href="buat_laporan.php" class="relative -mt-16 group">
            <div class="absolute -inset-2 bg-indigo-500 rounded-full blur opacity-40 group-hover:opacity-80 transition duration-500"></div>
            <div class="relative w-16 h-16 bg-indigo-600 rounded-full border-[6px] border-slate-950 text-white flex items-center justify-center shadow-2xl">
                <i class="fas fa-plus text-2xl"></i>
            </div>
        </a>

        <a href="chat_umum.php" class="text-slate-500 hover:text-white transition-colors"><i class="far fa-comment-dots text-xl"></i></a>
        <a href="profil_dpr.php" class="text-slate-500 hover:text-white transition-colors"><i class="far fa-user text-xl"></i></a>
    </nav>
</div>

</body>
</html>