<?php

include "../../config/connection.php";

$id = $_GET['id'];

$query = "DELETE FROM pengembalian
          WHERE id_pengembalian = '$id'";

$data = $conn->query($query);

if ($data) {
    header("Location: pengembalian.php");
    exit;
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>