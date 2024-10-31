<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'projetopadaria';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
