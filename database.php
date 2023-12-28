<?php
$host = "localhost";
$username = "root";
$password = "272727";
$database = "phpmvc";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
