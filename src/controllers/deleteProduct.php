<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../php/conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        throw new Exception('ID do produto não fornecido.');
    }

    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Produto excluído com sucesso!']);
    } else {
        throw new Exception('Produto não encontrado ou já excluído.');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}