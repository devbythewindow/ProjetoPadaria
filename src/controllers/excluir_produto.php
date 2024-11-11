<?php
session_start();
error_log("Requisição recebida em deleteProduct.php");
error_log("Dados recebidos: " . file_get_contents('php://input'));
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../php/conexao.php';

header('Content-Type: application/json');

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

try {
    // Lê os dados da requisição
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica se o ID do produto foi fornecido
    if (!isset($data['id'])) {
        http_response_code(400);
        throw new Exception('ID do produto não fornecido.');
    }

    // Sanitize e valida o ID do produto
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    if (!is_numeric($id) || $id <= 0) {
        http_response_code(400);
        throw new Exception('ID do produto inválido.');
    }

    // Lógica para excluir o produto do banco de dados
    $query = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        throw new Exception('Erro ao preparar a consulta: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);

    // Executa a consulta
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Produto excluído com sucesso!']);
        } else {
            throw new Exception('Produto não encontrado ou já excluído.');
        }
    } else {
        throw new Exception('Erro ao excluir produto: ' . $stmt->error);
    }

    // Fecha o statement
    $stmt->close();
} catch (Exception $e) {
    error_log("Erro: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
} finally {
    // Fecha a conexão com o banco de dados
    $conn->close();
}