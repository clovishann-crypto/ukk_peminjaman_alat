<?php

include "../../config/connection.php";

$id = $_GET['id'];

// Cek status peminjaman
$query_cek = "SELECT status
              FROM peminjaman
              WHERE id_peminjaman = '$id'";

$data_cek = $conn->query($query_cek);
$row_cek = $data_cek->fetch_assoc();

if (!$row_cek) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

$status = $row_cek['status'];

// Kalau masih dipinjam, ambil detail untuk mengembalikan stok
if ($status == 'Dipinjam') {

    $query_detail = "SELECT id_alat, jumlah
                     FROM detail_peminjaman
                     WHERE id_peminjaman = '$id'";

    $data_detail = $conn->query($query_detail);

    while ($detail = $data_detail->fetch_assoc()) {

        $id_alat = $detail['id_alat'];
        $jumlah = $detail['jumlah'];

        $conn->query("UPDATE alat
                      SET stok = stok + $jumlah
                      WHERE id_alat = '$id_alat'");
    }
}

// Hapus transaksi terlebih dahulu
$query_transaksi = "DELETE transaksi
                    FROM transaksi
                    INNER JOIN pengembalian
                    ON transaksi.id_pengembalian = pengembalian.id_pengembalian
                    WHERE pengembalian.id_peminjaman = '$id'";

$data_transaksi = $conn->query($query_transaksi);

if (!$data_transaksi) {
    echo "Data transaksi gagal dihapus: " . $conn->error;
    exit;
}

// Hapus pengembalian
$query_pengembalian = "DELETE FROM pengembalian
                       WHERE id_peminjaman = '$id'";

$data_pengembalian = $conn->query($query_pengembalian);

if (!$data_pengembalian) {
    echo "Data pengembalian gagal dihapus: " . $conn->error;
    exit;
}

// Hapus detail peminjaman
$query_detail = "DELETE FROM detail_peminjaman
                 WHERE id_peminjaman = '$id'";

$data_detail = $conn->query($query_detail);

if (!$data_detail) {
    echo "Detail peminjaman gagal dihapus: " . $conn->error;
    exit;
}

// Terakhir hapus peminjaman
$query = "DELETE FROM peminjaman
          WHERE id_peminjaman = '$id'";

$data = $conn->query($query);

if ($data) {
    header("Location: peminjaman.php");
    exit;
} else {
    echo "Data peminjaman gagal dihapus: " . $conn->error;
}

?>