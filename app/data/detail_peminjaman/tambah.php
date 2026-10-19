<?php

include "../../config/connection.php";

$id = $_GET['id'];

// Ambil data lama
$query_lama = "SELECT * FROM detail_peminjaman
               WHERE id_detail = '$id'";

$data_lama = $conn->query($query_lama);
$lama = $data_lama->fetch_assoc();

if (!$lama) {
    echo "Data detail peminjaman tidak ditemukan.";
    exit;
}

if (isset($_POST['update'])) {

    $id_peminjaman = $_POST['id_peminjaman'];
    $id_alat_baru = $_POST['id_alat'];
    $jumlah_baru = $_POST['jumlah'];

    $id_alat_lama = $lama['id_alat'];
    $jumlah_lama = $lama['jumlah'];

    // Cek jumlah harus lebih dari 0
    if ($jumlah_baru <= 0) {
        echo "Jumlah harus lebih dari 0.";
        exit;
    }

    // Jika alat tidak diganti
    if ($id_alat_lama == $id_alat_baru) {

        // Hitung perubahan jumlah
        $selisih = $jumlah_baru - $jumlah_lama;

        // Jika jumlah bertambah, cek stok
        if ($selisih > 0) {

            $cek_stok = $conn->query(
                "SELECT stok FROM alat WHERE id_alat = '$id_alat_baru'"
            );

            $alat = $cek_stok->fetch_assoc();

            if (!$alat) {
                echo "Alat tidak ditemukan.";
                exit;
            }

            if ($selisih > $alat['stok']) {
                echo "Stok alat tidak mencukupi.";
                exit;
            }

            // Kurangi stok sesuai selisih
            $conn->query(
                "UPDATE alat
                 SET stok = stok - $selisih
                 WHERE id_alat = '$id_alat_baru'"
            );

        } elseif ($selisih < 0) {

            // Jika jumlah dikurangi, kembalikan selisih ke stok
            $kembali = abs($selisih);

            $conn->query(
                "UPDATE alat
                 SET stok = stok + $kembali
                 WHERE id_alat = '$id_alat_baru'"
            );
        }

    } else {

        // Jika alat diganti,
        // kembalikan stok alat lama
        $conn->query(
            "UPDATE alat
             SET stok = stok + $jumlah_lama
             WHERE id_alat = '$id_alat_lama'"
        );

        // Cek stok alat baru
        $cek_stok = $conn->query(
            "SELECT stok FROM alat WHERE id_alat = '$id_alat_baru'"
        );

        $alat_baru = $cek_stok->fetch_assoc();

        if (!$alat_baru) {
            echo "Alat baru tidak ditemukan.";
            exit;
        }

        if ($jumlah_baru > $alat_baru['stok']) {
            echo "Stok alat baru tidak mencukupi.";
            exit;
        }

        // Kurangi stok alat baru
        $conn->query(
            "UPDATE alat
             SET stok = stok - $jumlah_baru
             WHERE id_alat = '$id_alat_baru'"
        );
    }

    // Update data detail peminjaman
    $query = "UPDATE detail_peminjaman SET
              id_peminjaman = '$id_peminjaman',
              id_alat = '$id_alat_baru',
              jumlah = '$jumlah_baru'
              WHERE id_detail = '$id'";

    $data = $conn->query($query);

    if ($data) {
        header("Location: detail_peminjaman.php");
        exit;
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

// Ambil data terbaru untuk ditampilkan di form
$query = "SELECT * FROM detail_peminjaman
          WHERE id_detail = '$id'";

$data = $conn->query($query);
$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Detail Peminjaman</title>

    <link rel="stylesheet" href="../../../public/css/style.css">

    <style>
        form {
            max-width: 500px;
        }

        form label {
            font-weight: bold;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #555;
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="header">
            <h1>Edit Detail Peminjaman</h1>
            <p>Ubah data detail peminjaman alat</p>
        </div>

        <div class="menu">

            <form method="POST">

                <label>ID Peminjaman</label><br>

                <input type="number" name="id_peminjaman" value="<?= $row['id_peminjaman']; ?>" required>

                <br><br>

                <label>ID Alat</label><br>

                <input type="number" name="id_alat" value="<?= $row['id_alat']; ?>" required>

                <br><br>

                <label>Jumlah</label><br>

                <input type="number" name="jumlah" value="<?= $row['jumlah']; ?>" min="1" required>

                <br><br>

                <button type="submit" name="update">
                    Update
                </button>

            </form>

            <br>

            <a href="detail_peminjaman.php">
                Kembali ke Data Detail Peminjaman
            </a>

        </div>

    </div>

</body>

</html>