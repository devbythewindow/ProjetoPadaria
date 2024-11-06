<?php
// Inicie a sessão
session_start();

// Inclua o arquivo de configuração do banco de dados
require_once __DIR__ . '/../../config/config.php'; // Ajuste o caminho conforme necessário
require_once __DIR__ . '/../../php/conexao.php'; // Inclua seu arquivo de conexão

header('Content-Type: application/json');

// Verifique se a requisição é um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture os dados do produto
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    // Validação básica
    if (empty($nome) || empty($preco) || empty($descricao)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
        exit;
    }

    // Verifique se o produto já existe
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE nome = ?");
    $stmt->execute([$nome]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Produto já existe.']);
        exit;
    }

    // Adicione o produto ao banco de dados
    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$nome, $preco, $descricao])) {
        echo json_encode(['success' => true, 'message' => 'Produto adicionado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar produto.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>