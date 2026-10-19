<?php

include "../../config/connection.php";

$id = $_GET['id'];

$query = "DELETE FROM transaksi
          WHERE id_transaksi = '$id'";

$data = $conn->query($query);

if ($data) {
    header("Location: transaksi.php");
    exit;
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>