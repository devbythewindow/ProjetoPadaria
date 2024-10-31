<?php 
session_start();

// Adiciona cabeçalhos para evitar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verifica se o usuário já está autenticado
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php'); // Redireciona para a página admin
    exit();
}

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
        $error_message = 'Usuário ou senha inválidos.';
    }
}

// Se o usuário não for admin, exibe o formulário de login
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
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Admin</h2>
        <?php if (isset($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
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
            <a href="../index.php" style="text-decoration: none;">
                <button>Voltar para o Início</button>
            </a>
        </div>
    </div>
</body>
</html>
