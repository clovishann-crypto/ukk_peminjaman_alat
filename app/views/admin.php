<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../../public/login.php");
    exit;
}

include "../config/connection.php";

// Menghitung peminjaman yang masih menunggu
$query_notif = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'Menunggu'"
);

$data_notif = mysqli_fetch_assoc($query_notif);
$total_peminjaman_baru = $data_notif['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css?v=2">
</head>

<body>
<div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Peminjaman Alat</h2>
            <p>Admin</p>
        </div>

        <ul class="sidebar-menu">
            <li><a href="../controller/admin/alat.php">Data Alat</a></li>
            <li><a href="../controller/admin/kategori.php">Data Kategori</a></li>
            <li><a href="../controller/admin/user.php">Data User</a></li>

            <li>
                <a href="../controller/admin/peminjaman.php">
                    Data Peminjaman

                    <?php if ($total_peminjaman_baru > 0) { ?>
                        <span style="background:#ff4757;color:white;padding:3px 8px;margin-left:5px;border:2px solid black;">
                            <?= $total_peminjaman_baru; ?>
                        </span>
                    <?php } ?>
                </a>
            </li>

            <li><a href="../controller/admin/detail_peminjaman.php">Detail Peminjaman</a></li>
            <li><a href="../controller/admin/pengembalian.php">Data Pengembalian</a></li>
            <li><a href="../controller/admin/transaksi.php">Data Transaksi</a></li>

            <li>
                <a href="../data/laporan/laporan_peminjaman.php" class="dashboard-item">
                    <h3>Cetak Laporan</h3>
                    <p>Cetak laporan peminjaman alat</p>
                </a>
            </li>

            <li class="logout-menu">
                <a href="../../public/logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <!-- AREA UTAMA -->
    <div class="main">
        <div class="topbar">
            <div>
                <h1>Aku Admin</h1>
                <p>
                    ADMIN DATANG
                    Selamat datang, <?= htmlspecialchars($_SESSION['nama_user']); ?>
                </p>
            </div>
        </div>

        <div class="card">
            <h2>Menu Admin</h2>
            <p>
                Silakan pilih menu pada sidebar untuk mengelola
                data Sistem Peminjaman Alat.
            </p>

            <?php if ($total_peminjaman_baru > 0) { ?>
                <p style="background:#ff4757;color:white;padding:12px;border:3px solid black;box-shadow:4px 4px black;">
                    <strong>Notifikasi:</strong>
                    Ada <?= $total_peminjaman_baru; ?> peminjaman baru yang menunggu diproses!
                </p>
            <?php } else { ?>
                <p>Tidak ada peminjaman baru yang menunggu diproses.</p>
            <?php } ?>
        </div>

        <div class="card">
            <h2>Data Sistem</h2>

            <div class="dashboard-menu">
                <a href="../controller/admin/alat.php" class="dashboard-item">
                    <h3>Data Alat</h3>
                    <p>Kelola data alat</p>
                </a>

                <a href="../controller/admin/kategori.php" class="dashboard-item">
                    <h3>Data Kategori</h3>
                    <p>Kelola kategori alat</p>
                </a>

                <a href="../controller/admin/user.php" class="dashboard-item">
                    <h3>Data User</h3>
                    <p>Kelola pengguna</p>
                </a>

                <a href="../controller/admin/peminjaman.php" class="dashboard-item">
                    <h3>Data Peminjaman</h3>
                    <p>Kelola peminjaman</p>
                </a>

                <a href="../controller/admin/detail_peminjaman.php" class="dashboard-item">
                    <h3>Detail Peminjaman</h3>
                    <p>Lihat detail peminjaman</p>
                </a>

                <a href="../controller/admin/pengembalian.php" class="dashboard-item">
                    <h3>Pengembalian</h3>
                    <p>Kelola pengembalian</p>
                </a>

                <a href="../controller/admin/transaksi.php" class="dashboard-item">
                    <h3>Transaksi</h3>
                    <p>Kelola transaksi</p>
                </a>

                <a href="../data/laporan/laporan_peminjaman.php" class="dashboard-item">
                    <h3>Cetak Laporan</h3>
                    <p>Cetak laporan peminjaman alat</p>
                </a>
            </div>
        </div>
    </div>

</div>
</body>
</html>