<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "projetopadaria");

$sql = "SELECT id, nome, preco, descricao FROM produto";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>
