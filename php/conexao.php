<?php
// Definindo as credenciais do banco de dados
$host = 'localhost'; // ou '127.0.0.1'
$username = 'root'; // nome do usuário do MySQL
$password = ''; // senha do MySQL (geralmente é vazia no XAMPP)
$dbname = 'projetopadaria'; // nome do banco de dados

// Criando a conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
