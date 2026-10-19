<?php
session_start();

include "../app/config/connection.php";

$pesan = "";
$jenis_pesan = "";

if (isset($_POST['register'])) {

    $nama_user = trim($_POST['nama_user']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    if ($nama_user == "" || $username == "") {

        $pesan = "Nama dan username wajib diisi!";
        $jenis_pesan = "error";

    } elseif ($password !== $konfirmasi) {

        $pesan = "Konfirmasi password tidak sama!";
        $jenis_pesan = "error";

    } elseif (strlen($password) < 5) {

        $pesan = "Password minimal 5 karakter!";
        $jenis_pesan = "error";

    } else {

        $cek = $conn->prepare(
            "SELECT id_user FROM user WHERE username = ?"
        );
        $cek->bind_param("s", $username);
        $cek->execute();

        $hasil = $cek->get_result();

        if ($hasil->num_rows > 0) {

            $pesan = "Username sudah digunakan!";
            $jenis_pesan = "error";

        } else {

            $hash_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $role = "Peminjam";

            $simpan = $conn->prepare(
                "INSERT INTO user (nama_user, username, password, role)
                 VALUES (?, ?, ?, ?)"
            );

            $simpan->bind_param(
                "ssss",
                $nama_user,
                $username,
                $hash_password,
                $role
            );

            if ($simpan->execute()) {
                header("Location: login.php?register=berhasil");
                exit;
            } else {
                $pesan = "Registrasi gagal!";
                $jenis_pesan = "error";
            }

            $simpan->close();
        }

        $cek->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Peminjam</title>

    <link rel="stylesheet" href="css/style.css?v=2">

    <style>
        .register-box {
            max-width: 400px;
            margin: 50px auto;
        }

        .register-box .header {
            text-align: center;
        }

        .register-box .menu {
            padding: 25px;
        }

        .register-box input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        .register-box button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            cursor: pointer;
        }

        .pesan {
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid black;
        }

        .error {
            background: #ff4757;
            color: white;
        }

        .link-login {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container register-box">

    <div class="header">
        <h1>Register</h1>
        <p>Sistem Peminjaman Alat</p>
    </div>

    <div class="menu">

        <h2>Daftar Akun Peminjam</h2>

        <?php if ($pesan != "") { ?>
            <div class="pesan <?= $jenis_pesan; ?>">
                <?= htmlspecialchars($pesan); ?>
            </div>
        <?php } ?>

        <form method="POST">

            <label>Nama Lengkap</label>
            <input type="text" name="nama_user" maxlength="100" required>

            <br><br>

            <label>Username</label>
            <input type="text" name="username" maxlength="50" required>

            <br><br>

            <label>Password</label>
            <input type="password" name="password" minlength="5" required>

            <br><br>

            <label>Konfirmasi Password</label>
            <input type="password" name="konfirmasi_password" minlength="5" required>

            <button type="submit" name="register">Register</button>

        </form>

        <div class="link-login">
            Sudah punya akun?
            <a href="login.php">Login di sini</a>
        </div>

    </div>

</div>

</body>
</html>