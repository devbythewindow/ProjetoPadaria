<?php 
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';

// Adiciona produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    // Utilize prepared statements para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $nome, $preco, $descricao);
    $stmt->execute();
    $stmt->close();
}

// Edita produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];

    // Use prepared statements para evitar SQL Injection
    $stmt = $conn->prepare("UPDATE produtos SET nome=?, preco=?, descricao=? WHERE id=?");
    $stmt->bind_param("sdsi", $nome, $preco, $descricao, $id);
    $stmt->execute();
    $stmt->close();
}

// Exclui produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    // Use prepared statements para evitar SQL Injection
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
$produtos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Produtos</title>
    <script>
        function habilitarEdicao(index) {
            const inputs = document.querySelectorAll(`.input-${index}`);
            inputs.forEach(input => input.disabled = false);
        }
    </script>
</head>
<body>
    <h1>Gerenciar Produtos</h1>
    
    <h2>Adicionar Produto</h2>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome do Produto" required>
        <input type="number" name="preco" placeholder="Preço" step="0.01" required>
        <textarea name="descricao" placeholder="Descrição" required></textarea>
        <button type="submit" name="add">Adicionar Produto</button>
    </form>

    <h2>Lista de Produtos</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($produtos as $index => $produto): ?>
        <tr>
            <form method="POST">
                <td><?php echo $produto['id']; ?></td>
                <td><input type="text" name="nome" class="input-<?php echo $index; ?>" value="<?php echo htmlspecialchars($produto['nome']); ?>" required disabled></td>
                <td><input type="number" name="preco" class="input-<?php echo $index; ?>" value="<?php echo htmlspecialchars($produto['preco']); ?>" step="0.01" required disabled></td>
                <td><textarea name="descricao" class="input-<?php echo $index; ?>" required disabled><?php echo htmlspecialchars($produto['descricao']); ?></textarea></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                    <button type="button" onclick="habilitarEdicao(<?php echo $index; ?>)">Editar</button>
                    <button type="submit" name="edit">Salvar</button>
                    <button type="submit" name="delete" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                </td>
            </form>
        </tr>
        <?php endforeach; ?>
    </table>

    <div>
        <a href="../index.php"><button>Voltar para o Início</button></a>
    </div>
</body>
</html>
