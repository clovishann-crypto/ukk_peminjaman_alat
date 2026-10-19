<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if (isset($_POST['simpan'])) {

    $id_user = $_POST['id_user'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_rencana_kembali = $_POST['tanggal_rencana_kembali'];
    $status = $_POST['status'];

    $query = "INSERT INTO peminjaman
              (id_user, tanggal_peminjaman, tanggal_rencana_kembali, status)
              VALUES
              ('$id_user', '$tanggal_peminjaman', '$tanggal_rencana_kembali', '$status')";

    $data = $conn->query($query);

    if ($data) {
        header("Location: peminjaman.php");
        exit;
    } else {
        echo "Data gagal ditambahkan: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Tambah Data Peminjaman</title>

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

                    <a href="peminjaman.php" class="active">
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

                    <a href="../alat/alat.php">
                        Data Alat
                    </a>

                    <a href="peminjaman.php" class="active">
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


        <!-- MAIN -->
        <main class="main">

            <div class="topbar">

                <div>

                    <h1>Tambah Data Peminjaman</h1>

                    <p>Tambah data peminjaman alat</p>

                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>ID User</label>

                    <input type="number" name="id_user" required>


                    <label>Tanggal Peminjaman</label>

                    <input type="date" name="tanggal_peminjaman" required>


                    <label>Tanggal Rencana Kembali</label>

                    <input type="date" name="tanggal_rencana_kembali" required>


                    <label>Status</label>

                    <input type="text" name="status" required>


                    <div class="form-actions">

                        <button type="submit" name="simpan" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="peminjaman.php" class="btn btn-muted">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>