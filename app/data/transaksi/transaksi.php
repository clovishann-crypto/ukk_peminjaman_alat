<?php

session_start();

if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Petugas')
) {
    header("Location: ../../../public/login.php");
    exit;
}

include "../../config/connection.php";

$query = "SELECT transaksi.*, 
                 pengembalian.id_pengembalian,
                 user.nama_user
          FROM transaksi
          INNER JOIN pengembalian
          ON transaksi.id_pengembalian = pengembalian.id_pengembalian
          INNER JOIN peminjaman
          ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
          INNER JOIN user
          ON peminjaman.id_user = user.id_user";

$data = $conn->query($query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Transaksi</title>

    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css?v=2">

</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-header">
                <h2>Sistem Peminjaman</h2>

                <div class="role">
                    <?= $_SESSION['role']; ?>
                </div>
            </div>

            <div class="sidebar-menu">

                <?php if ($_SESSION['role'] == 'Admin') { ?>

                    <a href="../../views/admin.php">
                        Dashboard
                    </a>

                    <a href="../alat/alat.php">
                        Data Alat
                    </a>

                    <a href="../kategori/kategori.php">
                        Data Kategori
                    </a>

                    <a href="../user/user.php">
                        Data User
                    </a>

                    <a href="../peminjaman/peminjaman.php">
                        Data Peminjaman
                    </a>

                    <a href="../detail_peminjaman/detail_peminjaman.php">
                        Detail Peminjaman
                    </a>

                    <a href="../pengembalian/pengembalian.php">
                        Data Pengembalian
                    </a>

                    <a href="transaksi.php" class="active">
                        Data Transaksi
                    </a>

                <?php } else { ?>

                    <a href="../../views/petugas.php">
                        Dashboard
                    </a>

                    <a href="../alat/alat.php">
                        Data Alat
                    </a>

                    <a href="../peminjaman/peminjaman.php">
                        Data Peminjaman
                    </a>

                    <a href="../detail_peminjaman/detail_peminjaman.php">
                        Detail Peminjaman
                    </a>

                    <a href="../pengembalian/pengembalian.php">
                        Data Pengembalian
                    </a>

                    <a href="transaksi.php" class="active">
                        Data Transaksi
                    </a>

                <?php } ?>

                <a href="../../../public/logout.php" class="logout-menu">
                    Logout
                </a>

            </div>

        </aside>


        <!-- KONTEN UTAMA -->
        <main class="main">

            <div class="topbar">

                <h1>Data Transaksi</h1>

            </div>


            <div class="card">

                <div class="toolbar">

                    <div>
                        <h2 style="margin:0;font-size:18px;">
                            Data Transaksi
                        </h2>

                        <p style="margin:5px 0 0;color:#64748b;font-size:13px;">
                            Daftar transaksi pembayaran
                        </p>
                    </div>
                    <br>
                    <a class="btn btn-primary" href="tambah.php">
                        + Tambah Transaksi
                    </a>

                </div>


                <div style="overflow-x:auto;">

                    <table>

                        <tr>
                            <th>No</th>
                            <th>ID Transaksi</th>
                            <th>ID Pengembalian</th>
                            <th>Nama Peminjam</th>
                            <th>Tanggal Transaksi</th>
                            <th>Jumlah Bayar</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
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
                                    <?= $row['id_transaksi']; ?>
                                </td>

                                <td>
                                    <?= $row['id_pengembalian']; ?>
                                </td>

                                <td>
                                    <?= $row['nama_user']; ?>
                                </td>

                                <td>
                                    <?= $row['tanggal_transaksi']; ?>
                                </td>

                                <td>
                                    Rp
                                    <?= number_format($row['jumlah_bayar'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <?= $row['metode_pembayaran']; ?>
                                </td>

                                <td>

                                    <?php if ($row['status'] == 'Lunas') { ?>

                                        <span class="badge badge-selesai">
                                            Lunas
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge badge-diajukan">
                                            <?= $row['status']; ?>
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>

                                    <a class="btn btn-primary btn-sm" href="edit.php?id=<?= $row['id_transaksi']; ?>">
                                        Edit
                                    </a>

                                    <a class="btn btn-danger btn-sm" href="hapus.php?id=<?= $row['id_transaksi']; ?>"
                                        onclick="return confirm('Yakin ingin menghapus transaksi ini?');">
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

        </main>

    </div>

</body>

</html>