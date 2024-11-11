<?php
require_once __DIR__ . '/../../php/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $nome = $data['nome'];
    $preco = $data['preco'];
    $categoria = $data['categoria'];

    $stmt = $conn->prepare("UPDATE produtos SET nome = ?, preco = ?, categoria = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $nome, $preco, $categoria, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produto atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar produto.']);
    }
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