<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllProducts() {
        $stmt = $this->db->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct($nome, $preco, $descricao) {
        $stmt = $this->db->prepare("INSERT INTO products (name, price, descricao ) VALUES (?, ?, ?)");
        return $stmt->execute([$nome, $preco, $descricao]);
    }

    // Métodos similares para updateProduct e deleteProduct
}$product = new Product();
echo json_encode($product->getAllProducts);