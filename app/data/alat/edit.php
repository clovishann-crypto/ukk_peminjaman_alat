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

$id = $_GET['id'];

// Ambil data alat
$query = "SELECT * FROM alat WHERE id_alat = '$id'";
$data = $conn->query($query);

$row = $data->fetch_assoc();

if (!$row) {
    echo "Data alat tidak ditemukan.";
    exit;
}

// Ambil data kategori
$query_kategori = "SELECT * FROM kategori";
$data_kategori = $conn->query($query_kategori);

if (isset($_POST['update'])) {

    $nama_alat = $_POST['nama_alat'];
    $id_kategori = $_POST['id_kategori'];
    $stok = $_POST['stok'];
    $kondisi = $_POST['kondisi'];

    $query = "UPDATE alat SET
              nama_alat = '$nama_alat',
              id_kategori = '$id_kategori',
              stok = '$stok',
              kondisi = '$kondisi'
              WHERE id_alat = '$id'";

    $data = $conn->query($query);

    if ($data) {
        header("Location: alat.php");
        exit;
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Data Alat</title>

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

                <a href="alat.php" class="active">
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

                <a href="../transaksi/transaksi.php">
                    Data Transaksi
                </a>

            <?php } else { ?>

                <a href="../../views/petugas.php">
                    Dashboard
                </a>

                <a href="alat.php" class="active">
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

            <h1>Edit Data Alat</h1>

        </div>


        <div class="card">

            <h2 style="margin-top:0;font-size:18px;">
                Ubah Data Alat
            </h2>

            <p style="color:#64748b;font-size:13px;">
                Ubah informasi alat yang dipilih.
            </p>


            <form method="POST">

                <label>Nama Alat</label>

                <input
                    type="text"
                    name="nama_alat"
                    value="<?= $row['nama_alat']; ?>"
                    required
                >


                <label>Kategori</label>

                <select name="id_kategori" required>

                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <?php while ($kategori = $data_kategori->fetch_assoc()) { ?>

                        <option
                            value="<?= $kategori['id_kategori']; ?>"
                            <?php if ($kategori['id_kategori'] == $row['id_kategori']) { ?>
                                selected
                            <?php } ?>
                        >
                            <?= $kategori['nama_kategori']; ?>
                        </option>

                    <?php } ?>

                </select>


                <label>Stok</label>

                <input
                    type="number"
                    name="stok"
                    value="<?= $row['stok']; ?>"
                    required
                >


                <label>Kondisi</label>

                <input
                    type="text"
                    name="kondisi"
                    value="<?= $row['kondisi']; ?>"
                    required
                >


                <div class="form-actions">

                    <button
                        type="submit"
                        name="update"
                        class="btn btn-primary"
                    >
                        Update
                    </button>

                    <a
                        href="alat.php"
                        class="btn btn-muted"
                    >
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </main>

</div>

</body>

</html>