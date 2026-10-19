<?php

include "../../config/connection.php";

$id = $_GET['id'];

$query = "DELETE FROM alat WHERE id_alat = $id";

$data = $conn->query($query);

if ($data) {
    header("Location: alat.php");
    exit;
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>