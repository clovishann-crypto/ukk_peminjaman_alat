<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../../public/login.php");
    exit;
}

include "../../config/connection.php";

$query = "SELECT * FROM kategori";

$data = $conn->query($query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Kategori</title>

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

                <li>
                    <a href="../../controller/admin/alat.php">
                        Data Alat
                    </a>
                </li>

                <li>
                    <a href="../../controller/admin/kategori.php">
                        Data Kategori
                    </a>
                </li>

                <li>
                    <a href="../../controller/admin/user.php">
                        Data User
                    </a>
                </li>

                <li>
                    <a href="../../controller/admin/peminjaman.php">
                        Data Peminjaman
                    </a>
                </li>

                <li>
                    <a href="../../controller/admin/detail_peminjaman.php">
                        Detail Peminjaman
                    </a>
                </li>

                <li>
                    <a href="../../controller/admin/pengembalian.php">
                        Data Pengembalian
                    </a>
                </li>

                <li>
                    <a href="../../controller/admin/transaksi.php">
                        Data Transaksi
                    </a>
                </li>

                <li class="logout-menu">
                    <a href="../../../public/logout.php">
                        Logout
                    </a>
                </li>

            </ul>

        </div>


        <!-- AREA UTAMA -->
        <div class="main">

            <div class="topbar">

                <div>

                    <h1>Data Kategori</h1>

                    <p>
                        Daftar kategori alat
                    </p>

                </div>

            </div>


            <div class="card">

                <div class="toolbar">

                    <h2>Daftar Kategori</h2>

                    <a href="tambah.php" class="btn btn-primary">
                        + Tambah Data Kategori
                    </a>

                </div>


                <table>

                    <tr>

                        <th>No</th>
                        <th>ID Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>

                    </tr>

                    <?php

                    $no = 1;

                    while ($row = $data->fetch_assoc()) {

                        ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= $row['id_kategori']; ?>
                            </td>

                            <td>
                                <?= $row['nama_kategori']; ?>
                            </td>

                            <td>

                                <a href="edit.php?id=<?= $row['id_kategori']; ?>" class="btn btn-primary btn-sm">
                                    Edit
                                </a>

                                <a href="hapus.php?id=<?= $row['id_kategori']; ?>" class="btn btn-danger btn-sm">
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>


            <!-- KEMBALI -->

            <div class="card">

                <a href="../../views/admin.php" class="btn btn-muted">
                    Kembali ke Dashboard
                </a>

            </div>

        </div>

    </div>

</body>

</html>