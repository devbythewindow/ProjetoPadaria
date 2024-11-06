<?php
require_once __DIR__ . '/../config/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>