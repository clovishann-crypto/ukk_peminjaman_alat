<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Peminjam') {
    header("Location: ../../../public/login.php");
    exit;
}

header("Location: ../../data/peminjaman/peminjaman_peminjam.php");
exit;

?>