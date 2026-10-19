<?php

include "../app/config/connection.php";

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

    <link rel="stylesheet" href="css/style.css">

    <style>
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        .data-table th {
            background-color: #333;
            color: white;
            padding: 12px;
            text-align: left;
        }

        .data-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .data-table tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h1>Data Alat</h1>
            <p>Daftar Alat yang Tersedia</p>
        </div>

        <div class="menu">

            <table class="data-table">

                <tr>
                    <th>ID Alat</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Kondisi</th>
                </tr>

                <?php while ($row = $data->fetch_assoc()) { ?>

                    <tr>
                        <td>
                            <?= $row['id_alat']; ?>
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
                    </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</body>

</html>