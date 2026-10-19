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

$id = $_GET['id'];

if (isset($_POST['update'])) {

    $id_peminjaman = $_POST['id_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $kondisi_kembali = $_POST['kondisi_kembali'];
    $denda = $_POST['denda'];

    $query = "UPDATE pengembalian SET
              id_peminjaman = '$id_peminjaman',
              tanggal_pengembalian = '$tanggal_pengembalian',
              kondisi_kembali = '$kondisi_kembali',
              denda = '$denda'
              WHERE id_pengembalian = '$id'";

    $data = $conn->query($query);

    if ($data) {
        header("Location: pengembalian.php");
        exit;
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

$query = "SELECT * FROM pengembalian
          WHERE id_pengembalian = '$id'";

$data = $conn->query($query);

$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Data Pengembalian</title>

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

                    <a href="pengembalian.php" class="active">
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

                    <a href="../detail_peminjaman/detail_peminjaman.php">
                        Detail Peminjaman
                    </a>

                    <a href="pengembalian.php" class="active">
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

                    <h1>Edit Data Pengembalian</h1>

                    <p>Ubah data pengembalian alat</p>

                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>ID Peminjaman</label>

                    <input type="number" name="id_peminjaman" value="<?= $row['id_peminjaman']; ?>" required>


                    <label>Tanggal Pengembalian</label>

                    <input type="date" name="tanggal_pengembalian" value="<?= $row['tanggal_pengembalian']; ?>"
                        required>


                    <label>Kondisi Kembali</label>

                    <input type="text" name="kondisi_kembali" value="<?= $row['kondisi_kembali']; ?>" required>


                    <label>Denda</label>

                    <input type="number" name="denda" value="<?= $row['denda']; ?>" min="0" required>


                    <div class="form-actions">

                        <button type="submit" name="update" class="btn btn-primary">
                            Update
                        </button>

                        <a href="pengembalian.php" class="btn btn-muted">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>