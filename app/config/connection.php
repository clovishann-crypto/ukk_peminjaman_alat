<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "12rpl1_raihan";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>