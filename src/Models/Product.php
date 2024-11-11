<?php
require_once __DIR__ . '/../../src/database/database.php';
header('Content-Type: application/json');

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllProducts() {
        $stmt = $this->db->prepare("SELECT * FROM produtos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function addProduct($nome, $preco, $descricao) {
    // Certifique-se de que a tabela e os campos estão corretos
    $stmt = $this->db->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
    return $stmt->execute([$nome, $preco, $descricao]);
}

}

$produtos = new Product();
$allProducts = $produtos->getAllProducts();
echo json_encode($allProducts);