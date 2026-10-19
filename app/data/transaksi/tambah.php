<?php

session_start();

include "../../config/connection.php";

// Cek role Admin atau Petugas
if (
    !isset($_SESSION['role']) ||
    ($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Petugas')
) {
    header("Location: ../../../public/login.php");
    exit;
}

$query_pengembalian = "SELECT pengembalian.*, user.nama_user
                       FROM pengembalian
                       INNER JOIN peminjaman
                       ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
                       INNER JOIN user
                       ON peminjaman.id_user = user.id_user";

$data_pengembalian = $conn->query($query_pengembalian);

if (isset($_POST['simpan'])) {

    $id_pengembalian = $_POST['id_pengembalian'];
    $tanggal_transaksi = $_POST['tanggal_transaksi'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $status = $_POST['status'];

    $query = "INSERT INTO transaksi
              (id_pengembalian, tanggal_transaksi, jumlah_bayar, metode_pembayaran, status)
              VALUES
              ('$id_pengembalian', '$tanggal_transaksi', '$jumlah_bayar', '$metode_pembayaran', '$status')";

    $data = $conn->query($query);

    if ($data) {
        header("Location: transaksi.php");
        exit;
    } else {
        echo "Data gagal ditambahkan: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Tambah Data Transaksi</title>

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


        <!-- MAIN -->
        <main class="main">

            <div class="topbar">

                <div>

                    <h1>Tambah Data Transaksi</h1>

                    <p>Tambah data transaksi pembayaran</p>

                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>ID Pengembalian</label>

                    <select name="id_pengembalian" required>

                        <option value="">
                            -- Pilih Pengembalian --
                        </option>

                        <?php while ($pengembalian = $data_pengembalian->fetch_assoc()) { ?>

                            <option value="<?= $pengembalian['id_pengembalian']; ?>">

                                <?= $pengembalian['id_pengembalian']; ?>
                                - <?= $pengembalian['nama_user']; ?>

                            </option>

                        <?php } ?>

                    </select>


                    <label>Tanggal Transaksi</label>

                    <input type="date" name="tanggal_transaksi" required>


                    <label>Jumlah Bayar</label>

                    <input type="number" name="jumlah_bayar" min="0" required>


                    <label>Metode Pembayaran</label>

                    <select name="metode_pembayaran" required>

                        <option value="">
                            -- Pilih Metode Pembayaran --
                        </option>

                        <option value="Cash">
                            Cash
                        </option>

                        <option value="Transfer">
                            Transfer
                        </option>

                    </select>


                    <label>Status</label>

                    <select name="status" required>

                        <option value="">
                            -- Pilih Status --
                        </option>

                        <option value="Belum Bayar">
                            Belum Bayar
                        </option>

                        <option value="Lunas">
                            Lunas
                        </option>

                    </select>


                    <div class="form-actions">

                        <button type="submit" name="simpan" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="transaksi.php" class="btn btn-muted">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>