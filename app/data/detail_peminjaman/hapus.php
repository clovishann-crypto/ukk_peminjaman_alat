<?php

include "../../config/connection.php";

$id = $_GET['id'];

// Ambil data detail sebelum dihapus
$query_cek = "SELECT id_alat, jumlah
              FROM detail_peminjaman
              WHERE id_detail = '$id'";

$data_cek = $conn->query($query_cek);
$row = $data_cek->fetch_assoc();

if (!$row) {
    echo "Data detail tidak ditemukan.";
    exit;
}

$id_alat = $row['id_alat'];
$jumlah = $row['jumlah'];

// Hapus detail peminjaman
$query = "DELETE FROM detail_peminjaman
          WHERE id_detail = '$id'";

$data = $conn->query($query);

if ($data) {

    // Kembalikan stok alat
    $conn->query("UPDATE alat
                  SET stok = stok + $jumlah
                  WHERE id_alat = '$id_alat'");

    header("Location: detail_peminjaman.php");
    exit;

} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>