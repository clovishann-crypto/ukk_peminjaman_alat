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

$query = "SELECT pengembalian.*, 
                 peminjaman.id_peminjaman,
                 user.nama_user
          FROM pengembalian
          INNER JOIN peminjaman
          ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
          INNER JOIN user
          ON peminjaman.id_user = user.id_user";

$data = $conn->query($query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Pengembalian</title>

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

                    <a href="pengembalian.php" class="active">
                        Data Pengembalian
                    </a>

                    <a href="../transaksi/transaksi.php">
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

                    <a href="pengembalian.php" class="active">
                        Data Pengembalian
                    </a>

                    <a href="../transaksi/transaksi.php">
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

                <h1>Data Pengembalian</h1>

            </div>


            <div class="card">

                <div class="toolbar">

                    <div>
                        <h2 style="margin:0;font-size:18px;">
                            Data Pengembalian
                        </h2>

                        <p style="margin:5px 0 0;color:#64748b;font-size:13px;">
                            Daftar pengembalian alat
                        </p>
                    </div>

                </div>


                <div style="overflow-x:auto;">

                    <table>

                        <tr>
                            <th>No</th>
                            <th>ID Pengembalian</th>
                            <th>ID Peminjaman</th>
                            <th>Nama Peminjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Kondisi Kembali</th>
                            <th>Denda</th>
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
                                    <?= $row['id_pengembalian']; ?>
                                </td>

                                <td>
                                    <?= $row['id_peminjaman']; ?>
                                </td>

                                <td>
                                    <?= $row['nama_user']; ?>
                                </td>

                                <td>
                                    <?= $row['tanggal_pengembalian']; ?>
                                </td>

                                <td>
                                    <?= $row['kondisi_kembali']; ?>
                                </td>

                                <td>
                                    Rp
                                    <?= number_format($row['denda'], 0, ',', '.'); ?>
                                </td>

                                <td>

                                    <a class="btn btn-primary btn-sm" href="edit.php?id=<?= $row['id_pengembalian']; ?>">
                                        Edit
                                    </a>

                                    <a class="btn btn-danger btn-sm" href="hapus.php?id=<?= $row['id_pengembalian']; ?>"
                                        onclick="return confirm('Yakin ingin menghapus data ini?');">
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