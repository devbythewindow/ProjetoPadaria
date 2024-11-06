<?php 
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: ../src/views/admin.php');
        exit();
    } else {
        $error_message = 'Usuário ou senha inválidos.';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Café dos Alunos</title>
    <link rel="stylesheet" href="../css/admin.css">
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