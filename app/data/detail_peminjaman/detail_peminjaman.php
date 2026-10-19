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

$query = "SELECT detail_peminjaman.*, 
                 alat.nama_alat,
                 peminjaman.status
          FROM detail_peminjaman
          INNER JOIN alat
          ON detail_peminjaman.id_alat = alat.id_alat
          INNER JOIN peminjaman
          ON detail_peminjaman.id_peminjaman = peminjaman.id_peminjaman";

$data = $conn->query($query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Detail Peminjaman</title>

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

                    <a href="detail_peminjaman.php" class="active">
                        Detail Peminjaman
                    </a>

                    <a href="../pengembalian/pengembalian.php">
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

                    <a href="detail_peminjaman.php" class="active">
                        Detail Peminjaman
                    </a>

                    <a href="../pengembalian/pengembalian.php">
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

                <h1>Detail Peminjaman</h1>

            </div>


            <div class="card">

                <div class="toolbar">

                    <div>
                        <h2 style="margin:0;font-size:18px;">
                            Data Detail Peminjaman
                        </h2>

                        <p style="margin:5px 0 0;color:#64748b;font-size:13px;">
                            Daftar detail alat yang dipinjam
                        </p>
                    </div>

                </div>


                <div style="overflow-x:auto;">

                    <table>

                        <tr>
                            <th>No</th>
                            <th>ID Detail</th>
                            <th>ID Peminjaman</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Status</th>
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
                                    <?= $row['id_detail']; ?>
                                </td>

                                <td>
                                    <?= $row['id_peminjaman']; ?>
                                </td>

                                <td>
                                    <?= $row['nama_alat']; ?>
                                </td>

                                <td>
                                    <?= $row['jumlah']; ?>
                                </td>

                                <td>

                                    <?php if ($row['status'] == 'Menunggu') { ?>

                                        <span class="badge badge-diajukan">
                                            Menunggu
                                        </span>

                                    <?php } elseif ($row['status'] == 'Dipinjam') { ?>

                                        <span class="badge badge-dipinjam">
                                            Dipinjam
                                        </span>

                                    <?php } elseif ($row['status'] == 'Selesai') { ?>

                                        <span class="badge badge-selesai">
                                            Selesai
                                        </span>

                                    <?php } elseif ($row['status'] == 'Ditolak') { ?>

                                        <span class="badge badge-ditolak">
                                            Ditolak
                                        </span>

                                    <?php } else { ?>

                                        <?= $row['status']; ?>

                                    <?php } ?>

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