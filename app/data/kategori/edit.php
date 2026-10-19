<?php

session_start();

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'Admin'
) {
    header("Location: ../../../public/login.php");
    exit;
}

include "../../config/connection.php";

$id = $_GET['id'];

if (isset($_POST['update'])) {

    $nama_kategori = $_POST['nama_kategori'];

    $query = "UPDATE kategori SET
              nama_kategori = '$nama_kategori'
              WHERE id_kategori = '$id'";

    $data = $conn->query($query);

    if ($data) {
        header("Location: kategori.php");
        exit;
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

$query = "SELECT * FROM kategori WHERE id_kategori = '$id'";

$data = $conn->query($query);

$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Data Kategori</title>

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

                <a href="../../views/admin.php">
                    Dashboard
                </a>

                <a href="../alat/alat.php">
                    Data Alat
                </a>

                <a href="kategori.php" class="active">
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

                <a href="../../../public/logout.php" class="logout-menu">
                    Logout
                </a>

            </div>

        </aside>


        <!-- KONTEN UTAMA -->
        <main class="main">

            <div class="topbar">

                <h1>Edit Data Kategori</h1>

            </div>


            <div class="card">

                <h2 style="margin-top:0;font-size:18px;">
                    Ubah Data Kategori
                </h2>

                <p style="color:#64748b;font-size:13px;">
                    Ubah nama kategori yang dipilih.
                </p>


                <form method="POST">

                    <label>Nama Kategori</label>

                    <input type="text" name="nama_kategori" value="<?= $row['nama_kategori']; ?>" required>


                    <div class="form-actions">

                        <button type="submit" name="update" class="btn btn-primary">
                            Update
                        </button>

                        <a href="kategori.php" class="btn btn-muted">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>