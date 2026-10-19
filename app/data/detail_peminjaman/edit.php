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

                    <a href="detail_peminjaman.php" class="active">
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

                    <a href="../peminjaman/peminjaman.php">
                        Data Peminjaman
                    </a>

                    <a href="detail_peminjaman.php" class="active">
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

                    <h1>Edit Detail Peminjaman</h1>

                    <p>Ubah data detail peminjaman alat</p>

                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>ID Peminjaman</label>

                    <input type="number" name="id_peminjaman" value="<?= $row['id_peminjaman']; ?>" required>


                    <label>ID Alat</label>

                    <input type="number" name="id_alat" value="<?= $row['id_alat']; ?>" required>


                    <label>Jumlah</label>

                    <input type="number" name="jumlah" value="<?= $row['jumlah']; ?>" min="1" required>


                    <div class="form-actions">

                        <button type="submit" name="update" class="btn btn-primary">
                            Update
                        </button>

                        <a href="detail_peminjaman.php" class="btn btn-muted">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>