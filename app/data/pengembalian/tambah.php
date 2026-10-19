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

// Ambil ID dari tombol Selesai
$id_peminjaman = $_GET['id_peminjaman'] ?? '';

if ($id_peminjaman == '') {
    echo "ID peminjaman tidak ditemukan.";
    exit;
}

// Ambil data peminjaman
$query_peminjaman = "SELECT *
                     FROM peminjaman
                     WHERE id_peminjaman = '$id_peminjaman'";

$data_peminjaman = $conn->query($query_peminjaman);
$peminjaman = $data_peminjaman->fetch_assoc();

if (!$peminjaman) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

// Harus berstatus Dipinjam
if ($peminjaman['status'] != 'Dipinjam') {
    echo "Peminjaman harus berstatus Dipinjam.";
    exit;
}

if (isset($_POST['simpan'])) {

    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $kondisi_kembali = $_POST['kondisi_kembali'];
    $denda = $_POST['denda'];

    // Cek apakah sudah ada pengembalian
    $cek_pengembalian = $conn->query(
        "SELECT * FROM pengembalian
         WHERE id_peminjaman = '$id_peminjaman'"
    );

    if ($cek_pengembalian->num_rows > 0) {
        echo "Peminjaman ini sudah dikembalikan.";
        exit;
    }

    // Simpan data pengembalian
    $query = "INSERT INTO pengembalian
              (id_peminjaman, tanggal_pengembalian, kondisi_kembali, denda)
              VALUES
              ('$id_peminjaman', '$tanggal_pengembalian', '$kondisi_kembali', '$denda')";

    $data = $conn->query($query);

    if ($data) {

        $id_pengembalian = $conn->insert_id;

        // Ambil detail alat
        $query_detail = "SELECT id_alat, jumlah
                         FROM detail_peminjaman
                         WHERE id_peminjaman = '$id_peminjaman'";

        $data_detail = $conn->query($query_detail);

        // Kembalikan stok
        while ($detail = $data_detail->fetch_assoc()) {

            $id_alat = $detail['id_alat'];
            $jumlah = $detail['jumlah'];

            $conn->query(
                "UPDATE alat
                 SET stok = stok + $jumlah
                 WHERE id_alat = '$id_alat'"
            );
        }

        // Ubah status peminjaman menjadi Selesai
        $query_status = "UPDATE peminjaman
                         SET status = 'Selesai'
                         WHERE id_peminjaman = '$id_peminjaman'";

        $data_status = $conn->query($query_status);

        if ($data_status) {

            // Buat transaksi otomatis
            $query_transaksi = "INSERT INTO transaksi
                    (id_pengembalian, tanggal_transaksi, jumlah_bayar, metode_pembayaran, status)
                    VALUES
                    ('$id_pengembalian', '$tanggal_pengembalian', '$denda', 'Cash', 'Belum Bayar')";

            $data_transaksi = $conn->query($query_transaksi);

            if ($data_transaksi) {
                header("Location: pengembalian.php");
                exit;
            } else {
                echo "Transaksi gagal dibuat: " . $conn->error;
            }

        } else {
            echo "Status peminjaman gagal diubah: " . $conn->error;
        }

    } else {
        echo "Data pengembalian gagal ditambahkan: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Data Pengembalian</title>

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

                    <h1>Data Pengembalian</h1>

                    <p>Proses pengembalian alat</p>

                </div>

            </div>


            <div class="card">

                <form method="POST">

                    <label>ID Peminjaman</label>

                    <input type="number" value="<?= $id_peminjaman; ?>" readonly>


                    <label>Tanggal Pengembalian</label>

                    <input type="date" name="tanggal_pengembalian" value="<?= date('Y-m-d'); ?>" required>


                    <label>Kondisi Kembali</label>

                    <input type="text" name="kondisi_kembali" required>


                    <label>Denda</label>

                    <input type="number" name="denda" value="0" min="0" required>


                    <div class="form-actions">

                        <button type="submit" name="simpan" class="btn btn-success">
                            Simpan
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