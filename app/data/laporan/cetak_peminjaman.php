<?php

session_start();

include "../../config/connection.php";

// Cek login Admin atau Petugas
if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Petugas')
) {
    header("Location: ../../../public/login.php");
    exit;
}

// Cek ID peminjaman
if (!isset($_GET['id'])) {
    echo "ID peminjaman tidak ditemukan.";
    exit;
}

$id_peminjaman = $_GET['id'];

// Ambil data peminjaman
$query = "SELECT
            peminjaman.id_peminjaman,
            user.nama_user,
            peminjaman.tanggal_peminjaman,
            peminjaman.tanggal_rencana_kembali,
            peminjaman.status
          FROM peminjaman
          INNER JOIN user
          ON peminjaman.id_user = user.id_user
          WHERE peminjaman.id_peminjaman = '$id_peminjaman'";

$data_peminjaman = $conn->query($query);

if ($data_peminjaman->num_rows == 0) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

$peminjaman = $data_peminjaman->fetch_assoc();


// Ambil detail alat yang dipinjam
$query_detail = "SELECT
                    alat.nama_alat,
                    detail_peminjaman.jumlah
                 FROM detail_peminjaman
                 INNER JOIN alat
                 ON detail_peminjaman.id_alat = alat.id_alat
                 WHERE detail_peminjaman.id_peminjaman = '$id_peminjaman'";

$data_detail = $conn->query($query_detail);


// Ambil data pengembalian jika sudah dikembalikan
$query_pengembalian = "SELECT
                         tanggal_pengembalian,
                         kondisi_kembali,
                         denda
                       FROM pengembalian
                       WHERE id_peminjaman = '$id_peminjaman'";

$data_pengembalian = $conn->query($query_pengembalian);

$pengembalian = null;

if ($data_pengembalian->num_rows > 0) {
    $pengembalian = $data_pengembalian->fetch_assoc();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Cetak Peminjaman</title>

    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css?v=2">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: white !important;
            color: black !important;
            font-family: Arial, sans-serif !important;
        }

        .laporan {
            width: 800px;
            max-width: 90%;
            margin: 40px auto;
            padding: 40px;
            background: white !important;
            color: black !important;
            border: 2px solid black;
            box-shadow: none !important;
        }

        .judul {
            text-align: center;
            margin-bottom: 30px;
            color: black !important;
        }

        .judul h1 {
            margin: 0;
            font-size: 24px;
            color: black !important;
        }

        .judul p {
            margin: 5px 0;
            color: black !important;
        }

        .data-peminjaman {
            margin-bottom: 25px;
            background: white !important;
            color: black !important;
        }

        .data-peminjaman table,
        .data-alat table {
            width: 100%;
            border-collapse: collapse;
            background: white !important;
            color: black !important;
        }

        .data-peminjaman tr,
        .data-peminjaman td {
            background: white !important;
            color: black !important;
        }

        .data-peminjaman td {
            padding: 8px;
            border: none;
        }

        .data-peminjaman td:first-child {
            width: 180px;
            font-weight: bold;
        }

        .data-alat {
            background: white !important;
            color: black !important;
        }

        .data-alat h3 {
            color: black !important;
        }

        .data-alat th,
        .data-alat td {
            border: 2px solid black !important;
            padding: 10px;
            background: white !important;
            color: black !important;
        }

        .data-alat th {
            background: #eeeeee !important;
            color: black !important;
        }

        .tanda-tangan {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            text-align: center;
            color: black !important;
        }

        .tanda-tangan div {
            width: 200px;
        }

        .ruang-tanda {
            height: 70px;
        }

        .tombol {
            text-align: center;
            margin: 20px auto;
        }

        .data-alat table {
            table-layout: fixed;
        }

        .data-alat th:first-child,
        .data-alat td:first-child {
            width: 35%;
        }

        .data-alat th:nth-child(2),
        .data-alat td:nth-child(2) {
            width: 35%;
        }

        .data-alat th:nth-child(3),
        .data-alat td:nth-child(3) {
            width: 30%;
        }

        .tabel-pengembalian {
            table-layout: fixed;
        }

        .tabel-pengembalian th:nth-child(1),
        .tabel-pengembalian td:nth-child(1) {
            width: 35%;
        }

        .tabel-pengembalian th:nth-child(2),
        .tabel-pengembalian td:nth-child(2) {
            width: 35%;
        }

        .tabel-pengembalian th:nth-child(3),
        .tabel-pengembalian td:nth-child(3) {
            width: 30%;
        }

        /* Saat dicetak */
        @media print {
            * {
                background: white !important;
                color: black !important;
                box-shadow: none !important;
            }

            body {
                margin: 0;
                padding: 0;
                background: white !important;
                color: black !important;
            }

            .laporan {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 20px;
                border: none;
                box-shadow: none !important;
                background: white !important;
            }

            .data-alat th {
                background: #eeeeee !important;
                color: black !important;
            }

            .data-alat th,
            .data-alat td {
                border: 2px solid black !important;
            }

            .tombol {
                display: none !important;
            }
        }

        .laporan,
        .laporan * {
            font-family: Arial, Helvetica, sans-serif !important;
            letter-spacing: normal !important;
            text-shadow: none !important;
        }
    </style>

</head>

<body>

    <div class="laporan">

        <div class="judul">

            <h1>LAPORAN PEMINJAMAN ALAT</h1>

            <p>Sistem Peminjaman Alat</p>

        </div>


        <div class="data-peminjaman">

            <table>

                <tr>
                    <td>ID Peminjaman</td>
                    <td>:
                        <?= $peminjaman['id_peminjaman']; ?>
                    </td>
                </tr>

                <tr>
                    <td>Nama Peminjam</td>
                    <td>:
                        <?= $peminjaman['nama_user']; ?>
                    </td>
                </tr>

                <tr>
                    <td>Tanggal Peminjaman</td>
                    <td>:
                        <?= $peminjaman['tanggal_peminjaman']; ?>
                    </td>
                </tr>

                <tr>
                    <td>Rencana Kembali</td>
                    <td>:
                        <?= $peminjaman['tanggal_rencana_kembali']; ?>
                    </td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td>:
                        <?= $peminjaman['status']; ?>
                    </td>
                </tr>

            </table>

        </div>


        <div class="data-alat">

            <h3>Daftar Alat yang Dipinjam</h3>

            <table>

                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Jumlah</th>
                </tr>

                <?php

                $no = 1;

                while ($row = $data_detail->fetch_assoc()) {

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

                    </tr>

                <?php } ?>

            </table>

        </div>


        <?php if ($pengembalian != null) { ?>

            <div class="data-alat">

                <h3>Data Pengembalian</h3>

                <table class="tabel-pengembalian">
                    <tr>
                        <th>Tanggal Pengembalian</th>
                        <th>Kondisi Kembali</th>
                        <th>Denda</th>
                    </tr>

                    <tr>

                        <td>
                            <?= $pengembalian['tanggal_pengembalian']; ?>
                        </td>

                        <td>
                            <?= $pengembalian['kondisi_kembali']; ?>
                        </td>

                        <td>
                            Rp
                            <?= number_format(
                                $pengembalian['denda'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </td>

                    </tr>

                </table>

            </div>

        <?php } ?>


        <div class="tanda-tangan">

            <div>

                <p>Petugas</p>

                <div class="ruang-tanda"></div>

                <p>____________________</p>

            </div>

            <div>

                <p>Peminjam</p>

                <div class="ruang-tanda"></div>

                <p>____________________</p>

            </div>

        </div>

    </div>


    <div class="tombol">

        <button onclick="window.print()" class="btn btn-primary">

            🖨 Cetak Laporan

        </button>

        <button onclick="history.back()" class="btn btn-muted">

            Kembali

        </button>

    </div>


</body>

</html>