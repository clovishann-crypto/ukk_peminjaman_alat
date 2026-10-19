<?php
session_start();
include "../app/config/connection.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM user WHERE username = ?"
    );
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $data = $stmt->get_result();

    if ($data->num_rows > 0) {
        $user = $data->fetch_assoc();

        // Memeriksa password hash atau password lama
        $password_benar = password_verify(
            $password,
            $user['password']
        );

        // Mendukung akun lama yang password-nya belum di-hash
        if (!$password_benar && $password === $user['password']) {
            $password_benar = true;

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $update = $conn->prepare(
                "UPDATE user SET password = ? WHERE id_user = ?"
            );
            $update->bind_param(
                "si",
                $password_hash,
                $user['id_user']
            );
            $update->execute();
        }

        if ($password_benar) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama_user'] = $user['nama_user'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'Admin') {
                header("Location: ../app/views/admin.php");
                exit;
            } elseif ($user['role'] == 'Petugas') {
                header("Location: ../app/views/petugas.php");
                exit;
            } elseif ($user['role'] == 'Peminjam') {
                header("Location: ../app/views/peminjam.php");
                exit;
            }
        } else {
            $pesan = "Username atau password salah!";
        }
    } else {
        $pesan = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css?v=2">

    <style>
        .login-box {
            max-width: 400px;
            margin: 60px auto;
        }

        .login-box .header {
            text-align: center;
        }

        .login-box .menu {
            padding: 30px;
        }

        .login-box form {
            max-width: 100%;
        }

        .login-box form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box form button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-box form button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
<div class="container login-box">
    <div class="header">
        <h1>Login</h1>
        <p>Sistem Peminjaman Alat</p>
    </div>

    <div class="menu">
        <?php
        if (isset($pesan)) {
            echo "<p style='color:red; text-align:center;'>"
                . htmlspecialchars($pesan) .
                "</p>";
        }

        if (isset($_GET['register']) &&
            $_GET['register'] == 'berhasil') {
            echo "<p style='color:green; text-align:center;'>"
                . "Pendaftaran berhasil. Silakan login."
                . "</p>";
        }
        ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <br><br>

            <label>Password</label>
            <input type="password" name="password" required>
            <br><br>

            <button type="submit" name="login">Login</button>
        </form>

        <p style="text-align:center; margin-top:20px;">
            Belum punya akun?
            <a href="register.php">Register di sini</a>
        </p>
    </div>
</div>
</body>
</html>