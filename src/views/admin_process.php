<?php
// admin_process.php
class AdminProcess {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function addProduct($nome, $preco, $descricao) {
        $stmt = $this->conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
        return $stmt->execute([$nome, $preco, $descricao]);
    }
    
    public function editProduct($id, $nome, $preco, $descricao) {
        $stmt = $this->conn->prepare("UPDATE produtos SET nome=?, preco=?, descricao=? WHERE id=?");
        return $stmt->execute([$nome, $preco, $descricao, $id]);
    }
    
    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id=?");
        return $stmt->execute([$id]);
    }
}