<?php

include "../../config/connection.php";

$id = $_GET['id'];

// Cek peminjaman
$query = "SELECT status
          FROM peminjaman
          WHERE id_peminjaman = '$id'";

$data = $conn->query($query);
$row = $data->fetch_assoc();

if (!$row) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

// Hanya peminjaman yang sedang Dipinjam yang bisa dikembalikan
if ($row['status'] != 'Dipinjam') {
    echo "Peminjaman harus berstatus Dipinjam.";
    exit;
}

// Arahkan ke form pengembalian
header("Location: ../pengembalian/tambah.php?id_peminjaman=$id");
exit;

?>