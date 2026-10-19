<?php

session_start();

include "../../config/connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Peminjam') {
    header("Location: ../../../public/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$id_alat = $_GET['id_alat'];

// Ambil data alat
$query = "SELECT alat.*, kategori.nama_kategori
          FROM alat
          INNER JOIN kategori
          ON alat.id_kategori = kategori.id_kategori
          WHERE alat.id_alat = '$id_alat'";

$data = $conn->query($query);
$row = $data->fetch_assoc();

if (!$row) {
    echo "Data alat tidak ditemukan.";
    exit;
}

if (isset($_POST['simpan'])) {

    $jumlah = $_POST['jumlah'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_rencana_kembali = $_POST['tanggal_rencana_kembali'];

    // Cek stok
    if ($jumlah > $row['stok']) {
        echo "Jumlah yang dipinjam melebihi stok.";
        exit;
    }

    // Simpan peminjaman
    $query_peminjaman = "INSERT INTO peminjaman
                         (id_user, tanggal_peminjaman, tanggal_rencana_kembali, status)
                         VALUES
                         ('$id_user', '$tanggal_peminjaman', '$tanggal_rencana_kembali', 'Menunggu')";

    $data_peminjaman = $conn->query($query_peminjaman);

    if ($data_peminjaman) {

        // Ambil ID peminjaman yang baru dibuat
        $id_peminjaman = $conn->insert_id;

        // Simpan detail peminjaman
        $query_detail = "INSERT INTO detail_peminjaman
                         (id_peminjaman, id_alat, jumlah)
                         VALUES
                         ('$id_peminjaman', '$id_alat', '$jumlah')";

        $data_detail = $conn->query($query_detail);

        if ($data_detail) {

            // Kurangi stok alat
            $conn->query("UPDATE alat
                          SET stok = stok - $jumlah
                          WHERE id_alat = '$id_alat'");

            header("Location: ../peminjaman/peminjaman_peminjam.php");
            exit;

        } else {
            echo "Detail peminjaman gagal ditambahkan: " . $conn->error;
        }

    } else {
        echo "Peminjaman gagal ditambahkan: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Ajukan Peminjaman</title>

    <link rel="stylesheet" href="../../../public/css/style.css">

    <style>
        .form-box {
            max-width: 500px;
            margin: 40px auto;
        }

        .form-box .header {
            text-align: center;
        }

        .form-box .menu {
            padding: 30px;
        }

        .form-box form {
            max-width: 100%;
        }

        .form-box form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-box form button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-box form button:hover {
            background-color: #555;
        }

        .kembali {
            display: inline-block;
            margin-top: 15px;
            color: #333;
            text-decoration: none;
        }

        .kembali:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container form-box">

        <div class="header">
            <h1>Ajukan Peminjaman</h1>
            <p>Sistem Peminjaman Alat</p>
        </div>

        <div class="menu">

            <form method="POST">

                <label>Nama Alat</label>
                <input type="text" value="<?= $row['nama_alat']; ?>" readonly>

                <br><br>

                <label>Kategori</label>
                <input type="text" value="<?= $row['nama_kategori']; ?>" readonly>

                <br><br>

                <label>Stok Tersedia</label>
                <input type="number" value="<?= $row['stok']; ?>" readonly>

                <br><br>

                <label>Jumlah yang Dipinjam</label>
                <input type="number" name="jumlah" min="1" max="<?= $row['stok']; ?>" required>

                <br><br>

                <label>Tanggal Peminjaman</label>
                <input type="date" name="tanggal_peminjaman" required>

                <br><br>

                <label>Tanggal Rencana Kembali</label>
                <input type="date" name="tanggal_rencana_kembali" required>

                <br><br>

                <button type="submit" name="simpan">
                    Ajukan Peminjaman
                </button>

            </form>

            <a class="kembali" href="alat_peminjam.php">
                Kembali ke Data Alat
            </a>

        </div>

    </div>

</body>

</html>