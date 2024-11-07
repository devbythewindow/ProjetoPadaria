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
    <title>Login Administrativo - Café dos Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/ProjetoPadaria/public/css/login.css">        
    </style>
</head>
<body>
    <div class="login-container">
        <div class="restricted-badge">
            <i class="fas fa-shield-alt"></i>
            Área Restrita
        </div>

        <div class="login-header">
            <i class="fas fa-user-shield"></i>
            <h1>Acesso Administrativo</h1>
            <p>Bem-vindo à área administrativa do Café dos Alunos. Este acesso é exclusivo para administradores autorizados.</p>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Usuário</label>
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" required 
                       placeholder="Digite seu usuário administrativo">
            </div>

            <div class="form-group">
                <label for="password">Senha</label>
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" required 
                       placeholder="Digite sua senha">
            </div>

            <button type="submit" class="login-button">
                <i class="fas fa-sign-in-alt"></i>
                Acessar Painel
            </button>
        </form>

        <div class="security-info">
            <i class="fas fa-info-circle"></i>
            Esta é uma área segura. Todas as tentativas de acesso são monitoradas.
        </div>

        <div class="back-button">
            <a href="../index.php">
                <i class="fas fa-arrow-left"></i>
                Voltar para a Loja
            </a>
        </div>
    </div>
</body>
</html>