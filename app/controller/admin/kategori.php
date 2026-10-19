<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../../../public/login.php");
    exit;
}

header("Location: ../../data/kategori/kategori.php");
exit;

?>