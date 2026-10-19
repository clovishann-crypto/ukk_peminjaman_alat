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

if (isset($_POST['simpan'])) {

    $nama_user = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "INSERT INTO user
              (nama_user, username, password, role)
              VALUES
              ('$nama_user', '$username', '$password', '$role')";

    $data = $conn->query($query);

    if ($data) {
        header("Location: user.php");
        exit;
    } else {
        echo "Data gagal ditambahkan: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Tambah Data User</title>

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

                <a href="../kategori/kategori.php">
                    Data Kategori
                </a>

                <a href="user.php" class="active">
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

                <h1>Tambah Data User</h1>

            </div>


            <div class="card">

                <h2 style="margin-top:0;font-size:18px;">
                    Tambah User Baru
                </h2>

                <p style="color:#64748b;font-size:13px;">
                    Tambahkan pengguna baru ke dalam sistem.
                </p>


                <form method="POST">

                    <label>Nama User</label>

                    <input type="text" name="nama_user" required>


                    <label>Username</label>

                    <input type="text" name="username" required>


                    <label>Password</label>

                    <input type="password" name="password" required>


                    <label>Role</label>

                    <select name="role" required>

                        <option value="">
                            -- Pilih Role --
                        </option>

                        <option value="Admin">
                            Admin
                        </option>

                        <option value="Petugas">
                            Petugas
                        </option>

                        <option value="Peminjam">
                            Peminjam
                        </option>

                    </select>


                    <div class="form-actions">

                        <button type="submit" name="simpan" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="user.php" class="btn btn-muted">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>