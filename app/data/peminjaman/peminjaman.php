<?php

session_start();

if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Petugas')
) {
    header("Location: ../../public/login.php");
    exit;
}

include "../../config/connection.php";

$query = "SELECT peminjaman.*, user.nama_user
          FROM peminjaman
          INNER JOIN user
          ON peminjaman.id_user = user.id_user";

$data = $conn->query($query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Peminjaman</title>

    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css?v=2">

</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="sidebar-header">

                <h2>Peminjaman Alat</h2>

                <p>
                    <?= $_SESSION['role']; ?>
                </p>

            </div>

            <ul class="sidebar-menu">

                <?php if ($_SESSION['role'] == 'Admin') { ?>

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

                <?php } else { ?>

                    <li>
                        <a href="../../controller/petugas/alat.php">
                            Data Alat
                        </a>
                    </li>

                    <li>
                        <a href="../../controller/petugas/peminjaman.php">
                            Data Peminjaman
                        </a>
                    </li>

                    <li>
                        <a href="../../controller/petugas/detail_peminjaman.php">
                            Detail Peminjaman
                        </a>
                    </li>

                    <li>
                        <a href="../../controller/petugas/pengembalian.php">
                            Data Pengembalian
                        </a>
                    </li>

                    <li>
                        <a href="../../controller/petugas/transaksi.php">
                            Data Transaksi
                        </a>
                    </li>

                <?php } ?>

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

                    <h1>Data Peminjaman</h1>

                    <p>
                        Daftar peminjaman alat
                    </p>

                </div>

            </div>


            <div class="card">

                <div class="toolbar">

                    <h2>Daftar Peminjaman</h2>

                    <a href="tambah.php" class="btn btn-primary">
                        + Tambah Data Peminjaman
                    </a>

                </div>


                <table>

                    <tr>

                        <th>No</th>
                        <th>ID Peminjaman</th>
                        <th>User</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Rencana Kembali</th>
                        <th>Status</th>
                        <th>Konfirmasi</th>
                        <th>Cetak</th>
                        <th>Hapus</th>

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
                                <?= $row['id_peminjaman']; ?>
                            </td>

                            <td>
                                <?= $row['nama_user']; ?>
                            </td>

                            <td>
                                <?= $row['tanggal_peminjaman']; ?>
                            </td>

                            <td>
                                <?= $row['tanggal_rencana_kembali']; ?>
                            </td>

                            <td>
                                <?= $row['status']; ?>
                            </td>

                            <td>

                                <a href="edit.php?id=<?= $row['id_peminjaman']; ?>" class="btn btn-primary btn-sm">
                                    Acc
                                </a>

                                <?php if ($row['status'] == 'Dipinjam') { ?>

                                    <a href="selesai.php?id=<?= $row['id_peminjaman']; ?>" class="btn btn-success btn-sm">
                                        Selesai
                                    </a>

                                <?php } ?>

                            </td>

                            <td>

                                <a href="../laporan/cetak_peminjaman.php?id=<?= $row['id_peminjaman']; ?>"
                                    class="btn btn-primary btn-sm">
                                    🖨 Cetak
                                </a>

                            </td>

                            <td>

                                <a href="hapus.php?id=<?= $row['id_peminjaman']; ?>" class="btn btn-danger btn-sm">
                                    Hapus
                                </a>

                            </td>
                        </tr>

                    <?php } ?>

                </table>

            </div>


            <!-- KEMBALI KE DASHBOARD -->

            <div class="card">

                <?php if ($_SESSION['role'] == 'Admin') { ?>

                    <a href="../../views/admin.php" class="btn btn-muted">
                        Kembali ke Dashboard
                    </a>

                <?php } else { ?>

                    <a href="../../views/petugas.php" class="btn btn-muted">
                        Kembali ke Dashboard
                    </a>

                <?php } ?>

            </div>

        </div>

    </div>

</body>

</html>