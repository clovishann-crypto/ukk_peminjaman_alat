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

// Ambil data peminjaman
$query_lama = "SELECT * FROM peminjaman
               WHERE id_peminjaman = '$id'";

$data_lama = $conn->query($query_lama);
$lama = $data_lama->fetch_assoc();

if (!$lama) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

if (isset($_POST['update'])) {

    $id_user = $_POST['id_user'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_rencana_kembali = $_POST['tanggal_rencana_kembali'];
    $status_baru = $_POST['status'];

    $status_lama = $lama['status'];

    // Jika sudah selesai, tidak boleh diubah
    if ($status_lama == 'Selesai') {
        echo '<div class="alert alert-error">
            Peminjaman yang sudah selesai tidak dapat diubah.
          </div>';
        exit;
    }
    // Jika status menjadi Ditolak
    if ($status_baru == 'Ditolak' && $status_lama != 'Ditolak') {

        $query_detail = "SELECT id_alat, jumlah
                         FROM detail_peminjaman
                         WHERE id_peminjaman = '$id'";

        $data_detail = $conn->query($query_detail);

        // Kembalikan stok
        while ($detail = $data_detail->fetch_assoc()) {

            $id_alat = $detail['id_alat'];
            $jumlah = $detail['jumlah'];

            $conn->query("UPDATE alat
                          SET stok = stok + $jumlah
                          WHERE id_alat = '$id_alat'");
        }
    }

    // Jika dari Ditolak menjadi Dipinjam
    if ($status_lama == 'Ditolak' && $status_baru == 'Dipinjam') {

        $query_detail = "SELECT id_alat, jumlah
                         FROM detail_peminjaman
                         WHERE id_peminjaman = '$id'";

        $data_detail = $conn->query($query_detail);

        // Kurangi stok lagi
        while ($detail = $data_detail->fetch_assoc()) {

            $id_alat = $detail['id_alat'];
            $jumlah = $detail['jumlah'];

            $cek_stok = $conn->query(
                "SELECT stok FROM alat
                 WHERE id_alat = '$id_alat'"
            );

            $alat = $cek_stok->fetch_assoc();

            if (!$alat) {
                echo "Alat tidak ditemukan.";
                exit;
            }

            if ($jumlah > $alat['stok']) {
                echo "Stok alat tidak mencukupi.";
                exit;
            }

            $conn->query("UPDATE alat
                          SET stok = stok - $jumlah
                          WHERE id_alat = '$id_alat'");
        }
    }

    // Update data peminjaman
    $query = "UPDATE peminjaman SET
              id_user = '$id_user',
              tanggal_peminjaman = '$tanggal_peminjaman',
              tanggal_rencana_kembali = '$tanggal_rencana_kembali',
              status = '$status_baru'
              WHERE id_peminjaman = '$id'";

    $data = $conn->query($query);

    if ($data) {
        header("Location: peminjaman.php");
        exit;
    } else {
        echo "Data gagal diubah: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Data Peminjaman</title>

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
                    <h1>Edit Data Peminjaman</h1>
                    <p>Ubah data peminjaman alat</p>
                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>ID User</label>

                    <input type="number" name="id_user" value="<?= $lama['id_user']; ?>" required>


                    <label>Tanggal Peminjaman</label>

                    <input type="date" name="tanggal_peminjaman" value="<?= $lama['tanggal_peminjaman']; ?>" required>


                    <label>Tanggal Rencana Kembali</label>

                    <input type="date" name="tanggal_rencana_kembali" value="<?= $lama['tanggal_rencana_kembali']; ?>"
                        required>


                    <label>Status</label>

                    <select name="status" required>

                        <option value="Menunggu" <?= $lama['status'] == 'Menunggu' ? 'selected' : ''; ?>>
                            Menunggu
                        </option>

                        <option value="Dipinjam" <?= $lama['status'] == 'Dipinjam' ? 'selected' : ''; ?>>
                            Dipinjam
                        </option>

                        <option value="Ditolak" <?= $lama['status'] == 'Ditolak' ? 'selected' : ''; ?>>
                            Ditolak
                        </option>

                    </select>


                    <div class="form-actions">

                        <button type="submit" name="update" class="btn btn-primary">
                            Update
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