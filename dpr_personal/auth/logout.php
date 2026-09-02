<?php
session_start();

// Hapus seluruh data session
session_unset();
session_destroy();

// Arahkan ke halaman utama (publik)
header("Location: ../public/index.php");
exit;
