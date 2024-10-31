<?php 
session_start();

// Defina as credenciais do administrador
$admin_username = 'admin';
$admin_password = 'senha123'; // Substitua por uma senha segura

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica se as credenciais estão corretas
    if ($username === $admin_username && $password === $admin_password) {
        // Se as credenciais estiverem corretas, define a variável de sessão
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php'); // Redireciona para a página admin
        exit();
    } else {
        echo 'Usuário ou senha inválidos.';
    }
}

// Verifica se o usuário é o admin
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if (!$isAdmin) {
    // Se não for admin, exibe o formulário de login
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Admin - Café dos Alunos</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { width: 300px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
            .button-container { margin-top: 20px; }
            .button-container button {
                background-color: blue; 
                color: white; 
                padding: 10px; 
                border: none; 
                border-radius: 5px; 
                cursor: pointer; 
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Login Admin</h2>
            <form method="POST" action="">
                <div>
                    <label for="username">Usuário:</label>
                    <input type="text" name="username" required>
                </div>
                <div>
                    <label for="password">Senha:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit">Entrar</button>
            </form>

            <div class="button-container">
                <a href="index.html" style="text-decoration: none;">
                    <button>Voltar para o Início</button>
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Se o usuário for admin, redireciona para a página de administração
header('Location: admin.php');
exit();
?>
