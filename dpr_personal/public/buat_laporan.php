<?php
// buat_laporan.php
include "../config/koneksi.php";
session_start();

// Proteksi: Pastikan user sudah input identitas
if (!isset($_SESSION['chat_email'])) {
    echo "<script>alert('Silahkan masukkan identitas email Anda di menu Chat terlebih dahulu!'); window.location.href='chat_umum.php';</script>";
    exit;
}

$email_aktif = $_SESSION['chat_email'];
$query_user = mysqli_query($koneksi, "SELECT nama FROM chat_umum WHERE nama LIKE '%$email_aktif%' ORDER BY id DESC LIMIT 1");
$data_user = mysqli_fetch_assoc($query_user);
$nama_pengaju = ($data_user) ? $data_user['nama'] : ($_SESSION['chat_user'] ?? $email_aktif);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sampaikan Aspirasi | AspirasiRakyat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* Background menggunakan file s.jpeg dengan overlay gelap elegan */
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.85)), 
                        url('../assets/img/s.jpeg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            color: #f8fafc; 
        }
        
        .glass-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(20px);
            border-radius: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .glass-nav {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-dark {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .input-dark:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(99, 102, 241, 0.5) !important;
            outline: none;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
        }

        .gradient-btn {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
            transition: all 0.3s ease;
        }
        .gradient-btn:active { transform: scale(0.95); }

        .upload-area {
            background: rgba(255, 255, 255, 0.02);
            border: 2px dashed rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.05);
        }
    </style>
</head>
<body class="pb-32">

<!-- Header Modern -->
<nav class="sticky top-0 z-50 glass-nav px-6 py-5">
    <div class="max-w-2xl mx-auto flex justify-between items-center">
        <button onclick="history.back()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/5 text-slate-400 transition">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h1 class="text-sm font-black text-white uppercase tracking-[0.2em]">Aspirasi<span class="text-indigo-500">Rakyat</span></h1>
        <div class="w-10 h-10"></div>
    </div>
</nav>

<main class="max-w-2xl mx-auto mt-8 px-4">
    
    <!-- Profil Singkat Pengirim -->
    <div class="mb-8 p-6 glass-card border-indigo-500/20 flex items-center gap-4 relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl"></div>
        <div class="w-14 h-14 bg-indigo-600/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-indigo-400 border border-indigo-500/30 shadow-inner">
            <i class="fas fa-user-shield text-xl"></i>
        </div>
        <div>
            <p class="text-[9px] text-indigo-300 font-black uppercase tracking-[0.3em]">Pelapor Terverifikasi</p>
            <p class="text-lg font-bold text-white tracking-tight"><?= htmlspecialchars($nama_pengaju); ?></p>
        </div>
    </div>

    <form id="formAspirasi" action="proses_laporan.php" method="POST" enctype="multipart/form-data" class="glass-card p-6 sm:p-8 space-y-8 overflow-hidden">
        
        <input type="hidden" name="nama_dari_chat" value="<?= htmlspecialchars($nama_pengaju); ?>">

        <!-- Upload Foto Area -->
        <div class="space-y-3">
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Bukti Visual</label>
            <div class="aspect-[16/9] upload-area rounded-[2rem] flex flex-col items-center justify-center relative group cursor-pointer overflow-hidden">
                <input type="file" name="foto" id="foto" class="absolute inset-0 opacity-0 cursor-pointer z-20" onchange="previewImage(event)">
                
                <div id="placeholder" class="text-center p-6">
                    <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 text-indigo-400 border border-white/10 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-camera-retro text-2xl"></i>
                    </div>
                    <p class="font-bold text-slate-300 text-xs tracking-widest uppercase">Lampirkan Foto</p>
                    <p class="text-slate-500 text-[10px] mt-2">Format JPG, PNG (Maks 2MB)</p>
                </div>
                
                <img id="preview" class="hidden w-full h-full object-cover rounded-[2rem] z-10 brightness-75">
                
                <div id="editBtn" class="hidden absolute top-4 right-4 z-30 bg-slate-900/80 backdrop-blur px-4 py-2 rounded-xl border border-white/10 text-[9px] font-black text-indigo-400 tracking-widest shadow-2xl">
                    UBAH MEDIA
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Judul Input -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pokok Laporan</label>
                <input type="text" name="judul" class="w-full text-lg font-bold input-dark rounded-2xl px-5 py-4 placeholder:text-slate-600" placeholder="Ringkasan aspirasi..." required>
            </div>

            <!-- Detail Laporan -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Deskripsi Lengkap</label>
                <textarea name="laporan" rows="4" class="w-full text-sm input-dark rounded-2xl p-5 placeholder:text-slate-600 leading-relaxed" placeholder="Jelaskan secara detail kronologi atau keluhan Anda..." required></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Sektor Komisi</label>
                    <div class="relative">
                        <select name="kategori" class="w-full input-dark rounded-xl py-4 pl-5 pr-10 text-xs font-bold appearance-none cursor-pointer" required>
                            <option value="" class="bg-slate-900">Pilih Bidang</option>
                            <option value="1" class="bg-slate-900">Komisi I – Pertahanan & IT</option>
                            <option value="4" class="bg-slate-900">Komisi IV – Pertanian</option>
                            <option value="5" class="bg-slate-900">Komisi V – Infrastruktur</option>
                            <option value="8" class="bg-slate-900">Komisi VIII – Sosial</option>
                            <option value="9" class="bg-slate-900">Komisi IX – Kesehatan</option>
                            <option value="11" class="bg-slate-900">Komisi XI – Keuangan</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Lokasi Peristiwa</label>
                    <div class="relative">
                        <input type="text" name="lokasi" placeholder="Kecamatan / Desa" class="w-full input-dark rounded-xl py-4 px-5 text-xs font-bold" required>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="kirim_aspirasi" class="gradient-btn w-full py-5 rounded-[1.5rem] text-white font-black text-[10px] tracking-[0.2em] shadow-2xl flex items-center justify-center gap-3 mt-4 uppercase">
                <span>Kirim Aspirasi</span>
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </div>
    </form>
    
    <p class="text-center text-[10px] text-slate-500 mt-10 mb-12 uppercase tracking-widest opacity-60">
        Keamanan data Anda dilindungi secara enkripsi sistem.
    </p>
</main>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            const editBtn = document.getElementById('editBtn');
            
            output.src = reader.result;
            output.classList.remove('hidden');
            placeholder.classList.add('hidden');
            editBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>