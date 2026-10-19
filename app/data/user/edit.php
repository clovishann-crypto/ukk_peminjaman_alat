<?php

session_start();

include "../../config/connection.php";

// Cek role Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../../../public/login.php");
    exit;
}

$id = $_GET['id'];

if (isset($_POST['update'])) {

    $nama_user = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "UPDATE user SET
              nama_user = '$nama_user',
              username = '$username',
              password = '$password',
              role = '$role'
              WHERE id_user = '$id'";

    $data = $conn->query($query);

    if ($data) {
        header("Location: user.php");
        exit;
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

$query = "SELECT * FROM user
          WHERE id_user = '$id'";

$data = $conn->query($query);

$row = $data->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Data User</title>

    <link rel="stylesheet" href="/ProjectUKK/public/css/style.css?v=2">

</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <div class="sidebar-header">
                <h2>Sistem Peminjaman</h2>
                <div class="role">Admin</div>
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


        <!-- MAIN -->
        <main class="main">

            <div class="topbar">

                <div>
                    <h1>Edit Data User</h1>
                    <p>Ubah data pengguna</p>
                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>Nama User</label>

                    <input type="text" name="nama_user" value="<?= $row['nama_user']; ?>" required>


                    <label>Username</label>

                    <input type="text" name="username" value="<?= $row['username']; ?>" required>


                    <label>Password</label>

                    <input type="text" name="password" value="<?= $row['password']; ?>" required>


                    <label>Role</label>

                    <select name="role" required>

                        <option value="Admin" <?= $row['role'] == 'Admin' ? 'selected' : ''; ?>>
                            Admin
                        </option>

                        <option value="Petugas" <?= $row['role'] == 'Petugas' ? 'selected' : ''; ?>>
                            Petugas
                        </option>

                        <option value="Peminjam" <?= $row['role'] == 'Peminjam' ? 'selected' : ''; ?>>
                            Peminjam
                        </option>

                    </select>


                    <div class="form-actions">

                        <button type="submit" name="update" class="btn btn-primary">
                            Update
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