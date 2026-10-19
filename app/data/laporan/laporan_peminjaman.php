<?php
session_start();
include "../../config/connection.php";

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Petugas')) {
    header("Location: ../../../public/login.php");
    exit;
}

$query = "SELECT 
            peminjaman.id_peminjaman,
            user.nama_user,
            peminjaman.tanggal_peminjaman,
            peminjaman.tanggal_rencana_kembali,
            peminjaman.status
          FROM peminjaman
          INNER JOIN user 
          ON peminjaman.id_user = user.id_user
          ORDER BY peminjaman.id_peminjaman DESC";

$data = $conn->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman</title>

    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css">

    <style>
        body {
            background: white !important;
            color: black !important;
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .laporan {
            width: 90%;
            margin: 30px auto;
            background: white !important;
            color: black !important;
        }

        .judul {
            text-align: center;
            margin-bottom: 30px;
        }

        .judul h1 {
            color: black !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white !important;
            color: black !important;
        }

        th,
        td {
            border: 2px solid black;
            padding: 10px;
            color: black !important;
            background: white !important;
            text-align: center;
        }

        th {
            background: #eeeeee !important;
        }

        .tombol {
            text-align: center;
            margin: 20px;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            * {
                font-family: Arial, Helvetica, sans-serif !important;
                text-shadow: none !important;
                letter-spacing: normal !important;
                box-shadow: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .tombol {
                display: none !important;
            }

            .laporan {
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            table {
                width: 100% !important;
                table-layout: fixed;
                font-size: 11px;
            }

            th,
            td {
                padding: 6px !important;
                word-wrap: break-word;
                color: black !important;
                background: white !important;
            }

            /* Kolom Cetak tidak ikut dicetak */
            th:nth-child(7),
            td:nth-child(7) {
                display: none !important;
            }
        }

        .laporan,
        .laporan * {
            font-family: Arial, Helvetica, sans-serif !important;
            letter-spacing: normal !important;
            text-shadow: none !important;
        }

        .judul h1,
        .judul p,
        table,
        th,
        td,
        a {
            font-family: Arial, Helvetica, sans-serif !important;
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

        <table>
            <tr>
                <th>No</th>
                <th>ID Peminjaman</th>
                <th>Nama Peminjam</th>
                <th>Tanggal Peminjaman</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th>Cetak</th>
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
                        <a href="cetak_peminjaman.php?id=<?= $row['id_peminjaman']; ?>">
                            🖨 Cetak
                        </a>
                    </td>
                </tr>

            <?php } ?>

        </table>

    </div>

    <div class="tombol">
        <button onclick="window.print()">🖨 Cetak Laporan</button>
        <button onclick="history.back()">Kembali</button>
    </div>

</body>

</html>