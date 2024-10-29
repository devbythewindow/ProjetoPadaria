<?php
    $conn = mysqli_connect("localhost", "root", "", "projeto");

    $id = trim($_GET["id"]);
    
    $sql1 = "DELETE FROM produto WHERE id_produto = $id";

    $result = $conn -> query($sql1);
    if ($result){
        echo '<script>alert("Produto excluído com sucesso!!"); 
        window.location.href = "main.php"</script>';    
    }
?>