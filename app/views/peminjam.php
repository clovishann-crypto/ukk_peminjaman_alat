<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Peminjam') {
    header("Location: ../../public/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Peminjam</title>
    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css?v=2">
</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="sidebar-header">
                <h2>Peminjaman Alat</h2>
                <p>Peminjam</p>
            </div>

            <ul class="sidebar-menu">

                <li>
                    <a href="../controller/peminjam/alat.php">
                        Data Alat
                    </a>
                </li>

                <li>
                    <a href="../controller/peminjam/peminjaman.php">
                        Data Peminjaman
                    </a>
                </li>

                <li class="logout-menu">
                    <a href="../../public/logout.php">
                        Logout
                    </a>
                </li>

            </ul>

        </div>

        <!-- AREA UTAMA -->
        <div class="main">

            <div class="topbar">
                <div>
                    <h1>Kau Peminjam</h1>

                    <p>
                        Selamat datang,
                        <?= $_SESSION['nama_user']; ?>
                    </p>
                </div>
            </div>

            <div class="card">

                <h2>Menu Peminjam</h2>

                <p>
                    Silakan pilih menu pada sidebar untuk melihat
                    alat dan data peminjaman.
                </p>

            </div>

            <div class="card">

                <h2>Data Sistem</h2>

                <div class="dashboard-menu">

                    <a href="../controller/peminjam/alat.php" class="dashboard-item">
                        <h3>Data Alat</h3>
                        <p>Lihat alat yang tersedia</p>
                    </a>

                    <a href="../controller/peminjam/peminjaman.php" class="dashboard-item">
                        <h3>Data Peminjaman</h3>
                        <p>Lihat data peminjaman</p>
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>

</html>