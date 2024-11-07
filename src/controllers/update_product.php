<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../php/conexao.php'; // Inclua sua conexão com o banco de dados

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Dados inválidos');
        }

        if (!isset($data['id'], $data['nome'], $data['preco'], $data['categoria'])) {
    throw new Exception('Campos obrigatórios faltando');
}

$id = $data['id'];
$nome = $data['nome'];
$preco = $data['preco'];
$categoria = $data['categoria'];

$query = "UPDATE produtos SET nome = ?, preco = ?, categoria = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $nome, $preco, $categoria, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
        } else {
            throw new Exception('Erro ao atualizar produto.');
        }

        $stmt->close();
    } else {
        throw new Exception('Método não permitido');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>