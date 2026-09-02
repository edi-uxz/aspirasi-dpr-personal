<?php
session_start();
include "../config/koneksi.php";

// 1. LOGIKA IDENTITAS
if (isset($_POST['join_chat'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $cek = mysqli_query($koneksi, "SELECT nama FROM masyarakat WHERE email = '$email'");
    
    if (mysqli_num_rows($cek) > 0) {
        $user = mysqli_fetch_assoc($cek);
        $_SESSION['chat_user'] = $user['nama'];
        $_SESSION['chat_email'] = $email;
    } else {
        $_SESSION['chat_user'] = explode('@', $email)[0];
        $_SESSION['chat_email'] = $email;
    }
}

// 2. LOGIKA KIRIM PESAN
if (isset($_POST['send_chat']) && isset($_SESSION['chat_user'])) {
    $nama = $_SESSION['chat_user'];
    $pesan = mysqli_real_escape_string($koneksi, $_POST['message']);
    if (!empty($pesan)) {
        mysqli_query($koneksi, "INSERT INTO chat_umum (nama, pesan, waktu) VALUES ('$nama', '$pesan', NOW())");
    }
    header("Location: chat_umum.php");
    exit;
}

// 3. AMBIL DATA PESAN
$query_chat = mysqli_query($koneksi, "SELECT * FROM (SELECT * FROM chat_umum ORDER BY id DESC LIMIT 50) AS sub ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Diskusi | AspirasiRakyat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            /* Background menggunakan file a.jpeg dengan overlay gelap elegan */
            background: linear-gradient(rgba(15, 23, 42, 0.92), rgba(15, 23, 42, 0.88)), 
                        url('../assets/img/a.jpeg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            color: #f8fafc; 
            margin: 0;
        }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        .glass-nav {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.4);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 2.5rem;
        }
        
        .bubble-me { 
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); 
            color: white; 
            border-radius: 1.5rem 1.5rem 0.2rem 1.5rem; 
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.3);
        }
        
        .bubble-others { 
            background: rgba(255, 255, 255, 0.05); 
            color: #f8fafc; 
            border-radius: 1.5rem 1.5rem 1.5rem 0.2rem; 
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .chat-container { height: calc(100vh - 220px); }

        .input-dark {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
        }

        .input-dark:focus {
            border-color: rgba(99, 102, 241, 0.5) !important;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
        }
    </style>
</head>
<body class="pb-32">

<!-- Header Sticky -->
<nav class="fixed top-0 left-0 right-0 z-50 glass-nav px-6 py-5">
    <div class="max-w-md mx-auto flex items-center justify-between">
        <a href="index.php" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/5 text-slate-400 transition">
            <i class="fas fa-chevron-left text-sm"></i>
        </a>
        <div class="text-center">
            <h1 class="text-[10px] font-black uppercase tracking-[0.3em] text-white">Ruang Diskusi</h1>
            <div class="flex items-center justify-center gap-1.5 mt-1">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                <p class="text-[8px] text-indigo-300 font-black uppercase tracking-widest">Warga Online</p>
            </div>
        </div>
        <div class="w-10 h-10"></div>
    </div>
</nav>

<main class="max-w-md mx-auto pt-24 px-4">
    
    <?php if (!isset($_SESSION['chat_user'])): ?>
        <!-- Form Identitas -->
        <div class="mt-12 flex flex-col items-center text-center px-4">
            <div class="w-20 h-20 bg-indigo-600/20 rounded-[2.5rem] flex items-center justify-center text-indigo-400 text-3xl mb-8 border border-indigo-500/30 shadow-2xl">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2 class="text-xl font-black text-white mb-2 uppercase tracking-tight">Akses Diskusi</h2>
            <p class="text-[11px] text-slate-500 leading-relaxed mb-10 tracking-wide uppercase">Masukan email anda untuk berpartisipasi dalam percakapan publik.</p>
            
            <form action="" method="POST" class="w-full space-y-5">
                <div class="relative">
                    <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                    <input type="email" name="email" placeholder="Email Aktif..." required 
                           class="w-full input-dark rounded-[1.5rem] py-5 pl-14 pr-6 text-xs font-bold focus:outline-none transition-all">
                </div>
                <button type="submit" name="join_chat" class="w-full bg-gradient-to-r from-indigo-600 to-violet-700 text-white font-black py-5 rounded-[1.5rem] shadow-2xl transform active:scale-95 transition-all uppercase text-[10px] tracking-[0.2em]">
                    Masuk Ruang Chat
                </button>
            </form>
        </div>

    <?php else: ?>
        <!-- Area Chat -->
        <div class="flex justify-between items-center mb-6 px-1">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/5 rounded-2xl flex items-center justify-center text-indigo-400 border border-white/10 shadow-inner">
                    <i class="fas fa-fingerprint text-sm"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest">Identitas Anda</span>
                    <span class="text-xs font-bold text-indigo-300"><?= $_SESSION['chat_user'] ?></span>
                </div>
            </div>
            <a href="logout_chat.php" class="text-[9px] font-black text-rose-400 bg-rose-500/10 px-4 py-2 rounded-xl border border-rose-500/20 uppercase tracking-widest">Logout</a>
        </div>

        <div id="chat-box" class="chat-container overflow-y-auto space-y-6 no-scrollbar pb-20">
            <?php while($row = mysqli_fetch_assoc($query_chat)): 
                $isMe = ($row['nama'] == $_SESSION['chat_user']);
            ?>
                <div class="flex flex-col <?= $isMe ? 'items-end' : 'items-start' ?>">
                    <?php if(!$isMe): ?>
                        <span class="text-[8px] font-black text-slate-500 ml-4 mb-2 uppercase tracking-widest"><?= $row['nama'] ?></span>
                    <?php endif; ?>
                    
                    <div class="max-w-[85%] px-5 py-4 text-sm leading-relaxed tracking-tight <?= $isMe ? 'bubble-me' : 'bubble-others' ?>">
                        <?= htmlspecialchars($row['pesan']) ?>
                    </div>
                    
                    <span class="text-[8px] text-slate-600 font-black mt-2 mx-3 uppercase tracking-tighter">
                        <?= date('H:i', strtotime($row['waktu'])) ?>
                    </span>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Input Box Sticky -->
        <div class="fixed bottom-28 left-0 right-0 px-4">
            <div class="max-w-md mx-auto">
                <form action="" method="POST" class="flex items-center gap-3 bg-slate-900/80 backdrop-blur-3xl border border-white/10 shadow-2xl rounded-[2.5rem] p-2 pl-6">
                    <input type="text" name="message" placeholder="Tulis pesan misterius..." autocomplete="off" required
                           class="flex-1 bg-transparent border-none text-xs focus:outline-none py-3 text-white placeholder:text-slate-600 font-bold tracking-tight">
                    <button type="submit" name="send_chat" class="w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center shadow-lg shadow-indigo-900/20 hover:bg-indigo-700 transition-all">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<!-- Bottom Navigation Bar (Floating Style) -->
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

        <a href="chat_umum.php" class="text-indigo-400 scale-110"><i class="fas fa-comment-dots text-xl"></i></a>
        <a href="profil_dpr.php" class="text-slate-500 hover:text-white transition-all"><i class="far fa-user text-xl"></i></a>
    </nav>
</div>

<script>
    const chatBox = document.getElementById('chat-box');
    if(chatBox) { chatBox.scrollTop = chatBox.scrollHeight; }
</script>

</body>
</html>