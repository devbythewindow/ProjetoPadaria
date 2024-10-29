<?php
session_start();
    $conn = mysqli_connect("localhost", "root", "", "projetopadaria");
    
    $sql = "SELECT * 
                FROM produto";
    $result = $conn -> query($sql);
    if($result -> num_rows > 0){
        $data = array();
        while($row = $result -> fetch_assoc()){
            $data[] = $row;
        }
        echo json_encode($data);
    }

?>