<?php

session_start();

include "../../config/connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Peminjam') {
    header("Location: ../../../public/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = "SELECT peminjaman.*,   
                 user.nama_user,  
                 alat.nama_alat,  
                 detail_peminjaman.jumlah  
          FROM peminjaman  
          INNER JOIN user  
          ON peminjaman.id_user = user.id_user  
          INNER JOIN detail_peminjaman  
          ON peminjaman.id_peminjaman = detail_peminjaman.id_peminjaman  
          INNER JOIN alat  
          ON detail_peminjaman.id_alat = alat.id_alat  
          WHERE peminjaman.id_user = '$id_user'";

$data = $conn->query($query);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Peminjaman Saya</title>

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

                    <a href="../../controller/peminjam/alat.php">
                        Data Alat
                    </a>

                </li>


                <li>

                    <a href="../../controller/peminjam/peminjaman.php" class="active">
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

                    <h1>Peminjaman Saya</h1>

                    <p>
                        Daftar peminjaman alat Anda
                    </p>

                </div>

            </div>


            <div class="card">

                <div class="toolbar">

                    <h2>Daftar Peminjaman</h2>

                </div>


                <table>

                    <tr>

                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Jumlah</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Rencana Kembali</th>
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
                                <?= $row['nama_alat']; ?>
                            </td>


                            <td>
                                <?= $row['jumlah']; ?>
                            </td>


                            <td>
                                <?= $row['tanggal_peminjaman']; ?>
                            </td>


                            <td>
                                <?= $row['tanggal_rencana_kembali']; ?>
                            </td>


                            <td>

                                <?php if ($row['status'] == 'Menunggu') { ?>

                                    <span class="badge badge-warning">
                                        Menunggu
                                    </span>

                                <?php } elseif ($row['status'] == 'Dipinjam') { ?>

                                    <span class="badge badge-success">
                                        Dipinjam
                                    </span>

                                <?php } elseif ($row['status'] == 'Selesai') { ?>

                                    <span class="badge badge-success">
                                        Selesai
                                    </span>

                                <?php } elseif ($row['status'] == 'Ditolak') { ?>

                                    <span class="badge badge-danger">
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