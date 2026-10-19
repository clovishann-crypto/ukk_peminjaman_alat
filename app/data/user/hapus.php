<?php

include "../../config/connection.php";

$id = $_GET['id'];

$query = "DELETE FROM user
          WHERE id_user = '$id'";

$data = $conn->query($query);

if ($data) {
    header("Location: user.php");
    exit;
} else {
    echo "Data gagal dihapus: " . $conn->error;
}

?>