<?php
// session_start inicia a sessão
session_start();
//conexão
$conexao = mysqli_connect("localhost", "root", "", "projeto");

if (!$conexao) {
    echo "Error: Falha ao conectar-se com o banco de dados MySQL.";   
    exit;
}

echo "Sucesso: Sucesso ao conectar-se com a base de dados MySQL.";


// as variáveis login e senha recebem os dados digitados na página anterior
$email = $_POST['email'];
$senha = $_POST['senha'];
//consulta para verificar se o email e a senha conferem
	$sql = "SELECT * FROM usuario
				WHERE email = '$email' AND senha = '$senha'";
$result = mysqli_query($conexao,$sql);

if(mysqli_num_rows ($result) > 0 ){
	$row = mysqli_fetch_array($result);
    echo($row['nome']);
	$_SESSION['nome_usuario'] = $row['nome'];
	$_SESSION['id_usuario'] = $row['id_usuario'];
	header('location: main.php');	
}else{
	echo("Usuário ou senha inválido!!");
	unset ($_SESSION['email']);
	unset ($_SESSION['senha']);
	header('location: ../index.html');
	// removendo todas as sessões
	session_start();
	session_destroy();
	unset( $_SESSION );
}