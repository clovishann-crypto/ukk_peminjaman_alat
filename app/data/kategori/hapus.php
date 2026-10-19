<?php

include "../../config/connection.php";

$id = $_GET['id'];

$query = "DELETE FROM kategori
          WHERE id_kategori = '$id'";

$data = $conn->query($query);

if ($data) {
    header("Location: kategori.php");
    exit;
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>