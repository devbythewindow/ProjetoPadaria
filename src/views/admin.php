<?php 
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    require 'conexao.php';
    require_once 'validationhelper.php';
}
    $msg = '';

    // Verificação do token CSRF para todas as requisições POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch');
    }
}
    // Processamento das ações POST (adicionar, editar, excluir)
    if (isset($_POST['add'])) {
        $nome = $_POST['nome'] ?? '';
        $preco = $_POST['preco'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
    }
        $erros = validar_produto($nome, $preco, $descricao);
        if (empty($erros)) {
            $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
            $stmt->bind_param("sds", $nome, $preco, $descricao);
            $msg = $stmt->execute() ? "Produto adicionado com sucesso!" : "Erro ao adicionar produto: " . $stmt->error;
            $stmt->close();
         else {
            $msg = implode("<br>", $erros);
        }
    } elseif (isset($_POST['edit'])) {
        // Lógica para editar produto
        // ...
    } elseif (isset($_POST['delete'])) {
        // Lógica para excluir produto
        // ...
    }


// Paginação
$_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

$stmt = $conn->prepare("SELECT * FROM produtos LIMIT ?, ?");
$stmt->bind_param("ii", $inicio, $por_pagina);
$stmt->execute();
$result = $stmt->get_result();

$total_result = $conn->query("SELECT COUNT(*) FROM produtos");
$total_produtos = $total_result->fetch_row()[0];
$total_paginas = ceil($total_produtos / $por_pagina);
}

// Limpar cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: 0");

// Usuário autenticado?
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}

require 'conexao.php';

$msg = ''; // Mensagens de acerto e erro

// Adicionar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    if (!empty($nome) && !empty($preco) && !empty($descricao)) {
        $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $nome, $preco, $descricao);

        $msg = $stmt->execute() ? "Produto adicionado com sucesso!" : "Erro ao adicionar produto: " . $stmt->error;
        $stmt->close();
    } else {
        $msg = "Preencha todos os campos para adicionar um produto.";
    }
}

// Editar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    if (!empty($id) && !empty($nome) && !empty($preco) && !empty($descricao)) {
        $stmt = $conn->prepare("UPDATE produtos SET nome=?, preco=?, descricao=? WHERE id=?");
        $stmt->bind_param("sdsi", $nome, $preco, $descricao, $id);

        $msg = $stmt->execute() ? "Produto atualizado com sucesso!" : "Erro ao atualizar produto: " . $stmt->error;
        $stmt->close();
    } else {
        $msg = "Preencha todos os campos para editar o produto.";
    }
}

// Excluir produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'] ?? '';
    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM produtos WHERE id=?");
        $stmt->bind_param("i", $id);

        $msg = $stmt->execute() ? "Produto excluído com sucesso!" : "Erro ao excluir produto: " . $stmt->error;
        $stmt->close();
    } else {
        $msg = "ID do produto não foi encontrado para exclusão.";
    }
}

for ($i = 1; $i <= $total_paginas; $i++) {
    echo "<a href='?pagina=$i'>$i</a> ";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Produtos</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script>
        function habilitarEdicao(index) {
            const inputs = document.querySelectorAll(`.input-${index}`);
            inputs.forEach(input => {
                input.disabled = false; // Habilitar o campo
                input.classList.add('input-edicao'); // Adiciona a classe de destaque
            });

            // Habilita e estiliza o input de nome e preço:
            const nomeInput = document.querySelectorAll(`input[name="nome"]`)[index];
            const precoInput = document.querySelectorAll(`input[name="preco"]`)[index];

            if (nomeInput) {
                nomeInput.classList.add('input-edicao');
                nomeInput.disabled = false;
            }

            if (precoInput) {
                precoInput.classList.add('input-edicao');
                precoInput.disabled = false;
            }
        }
    </script>
</head>
<body>
    <h1>Gerenciar Produtos</h1>

    <?php if (!empty($msg)): ?>
        <p><?php echo htmlspecialchars($msg); ?></p>

    <h2>Adicionar Produto</h2>
    <form method="POST">

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
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
        <tr>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
                
            <td><?php echo htmlspecialchars($produto['id']); ?></td>
            <td>
                <input type="text" name="nome" class="input-<?php echo $index; ?>" value="<?php echo htmlspecialchars($produto['nome']); ?>" required disabled>
            </td>
            <td>
                <input type="number" name="preco" class="input-<?php echo $index; ?>" value="<?php echo htmlspecialchars($produto['preco']); ?>" step="0.01" required disabled>
            </td>
            <td>
                <textarea name="descricao" class="input-<?php echo $index; ?>" required disabled><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </td>
            
            <td>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($produto['id']); ?>">
                <button type="button" onclick="habilitarEdicao(<?php echo $index; ?>)">Editar</button>
                <button type="submit" name="edit" class="salvar-<?php echo $index; ?>" style="display:none;">Salvar</button>
                <button type="submit" name="delete" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
            </td>
        </form>
        <?php endforeach; ?>
    </table>

    <div>
        <a href="logout.php"><button>Sair</button></a>
        <a href="../index.php"><button>Voltar para o Início</button></a>
    </div>
</body>
</html>
