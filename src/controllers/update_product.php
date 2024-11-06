<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../php/conexao.php';

header('Content-Type: application/json');

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Recebe os dados do POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        throw new Exception('Dados inválidos');
    }

    // Verifica campos obrigatórios
    if (!isset($data['id']) || !isset($data['nome']) || !isset($data['preco'])) {
        throw new Exception('Campos obrigatórios faltando');
    }

    // Sanitiza os dados
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_var($data['nome'], FILTER_SANITIZE_STRING);
    $preco = filter_var($data['preco'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Prepara e executa a query
    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
    $stmt->bind_param("sdi", $nome, $preco, $id);
    
    if (!$stmt->execute()) {
        throw new Exception("Erro ao atualizar produto");
    }

    $stmt->close();
    $conn->close();

    // Retorna sucesso
    echo json_encode([
        'success' => true, 
        'message' => 'Produto atualizado com sucesso'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}