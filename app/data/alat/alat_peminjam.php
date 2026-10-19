<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Peminjam') {
    header("Location: ../../../public/login.php");
    exit;
}

include "../../config/connection.php";

$query = "SELECT alat.*, kategori.nama_kategori
          FROM alat
          INNER JOIN kategori
          ON alat.id_kategori = kategori.id_kategori";

$data = $conn->query($query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Alat</title>

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

                    <a href="../../controller/peminjam/alat.php" class="active">
                        Data Alat
                    </a>

                </li>


                <li>

                    <a href="../../controller/peminjam/peminjaman.php">
                        Data Peminjaman
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

                    <h1>Data Alat</h1>

                    <p>
                        Daftar alat yang dapat dipinjam
                    </p>

                </div>

            </div>


            <div class="card">

                <div class="toolbar">

                    <h2>Daftar Alat</h2>

                </div>


                <table>

                    <tr>

                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Kondisi</th>
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
                                <?= $row['nama_alat']; ?>
                            </td>


                            <td>
                                <?= $row['nama_kategori']; ?>
                            </td>


                            <td>
                                <?= $row['stok']; ?>
                            </td>


                            <td>
                                <?= $row['kondisi']; ?>
                            </td>


                            <td>

                                <?php if ($row['stok'] > 0) { ?>

                                    <a href="tambah_peminjaman.php?id_alat=<?= $row['id_alat']; ?>"
                                        class="btn btn-primary btn-sm">
                                        Pinjam
                                    </a>

                                <?php } else { ?>

                                    <span class="badge badge-danger">
                                        Stok Habis
                                    </span>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>


            <!-- KEMBALI KE DASHBOARD -->

            <div class="card">

                <a href="../../views/peminjam.php" class="btn btn-muted">
                    Kembali ke Dashboard
                </a>

            </div>

        </div>

    </div>

</body>

</html>