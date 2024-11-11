<?php
// Inicie a sessão
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclua o arquivo de configuração do banco de dados
require_once __DIR__ . '/../../config/config.php'; // Ajuste o caminho conforme necessário
require_once __DIR__ . '/../../php/conexao.php'; // Inclua seu arquivo de conexão

header('Content-Type: application/json');

// Verifique se a requisição é um POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

// Capture os dados do produto
$nome = trim($_POST['nome']);
$preco = trim($_POST['preco']);
$descricao = trim($_POST['descricao']);
$categoria = trim($_POST['categoria']); // Capture a categoria

// Validação básica
if (empty($nome) || empty($preco) || empty($descricao) || empty($categoria)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
    exit;
}

// Verifique se o preço é um número válido
if (!is_numeric($preco) || $preco <= 0) {
    echo json_encode(['success' => false, 'message' => 'O preço deve ser um número positivo.']);
    exit;
}

// Verifique se o produto já existe
$stmt = $conn->prepare("SELECT * FROM produtos WHERE nome = ?");
$stmt->bind_param("s", $nome); // Use bind_param para evitar SQL Injection
$stmt->execute();
$result = $stmt->get_result(); // Use get_result() para obter o resultado da consulta

if ($result->num_rows > 0) { // Use num_rows para verificar o número de linhas
    echo json_encode(['success' => false, 'message' => 'Produto já existe.']);
    exit;
}

// Adicione o produto ao banco de dados
$stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao, categoria) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sdss", $nome, $preco, $descricao, $categoria); // Bind os parâmetros

try {
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produto adicionado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar produto.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar produto: ' . $e->getMessage()]);
}

// Fecha a declaração
$stmt->close();
$conn->close(); // Fecha a conexão
?>