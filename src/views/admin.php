<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../php/conexao.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../php/login.php');
    exit();
}

// Função de validação do produto
function validar_produto($nome, $preco, $descricao) {
    $erros = [];
    if (empty($nome)) {
        $erros[] = "Nome é obrigatório";
    }
    if (!is_numeric($preco) || $preco <= 0) {
        $erros[] = "Preço deve ser um número positivo";
    }
    if (empty($descricao)) {
        $erros[] = "Descrição é obrigatória";
    }
    return $erros;
}

class AdminManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function listarProdutos() {
        $sql = "SELECT * FROM produtos";
        $result = $this->conn->query($sql);
        if (!$result) {
            error_log("Erro ao listar produtos: " . $this->conn->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function adicionarProduto($nome, $preco, $descricao) {
        $stmt = $this->conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
        if (!$stmt) {
            error_log("Erro ao preparar a consulta: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("sds", $nome, $preco, $descricao);
        if (!$stmt->execute()) {
            error_log("Erro ao adicionar produto: " . $stmt->error);
            return false;
        }
        return true;
    }

    public function editarProduto($id, $nome, $preco, $descricao) {
        $stmt = $this->conn->prepare("UPDATE produtos SET nome = ?, preco = ?, descricao = ? WHERE id = ?");
        if (!$stmt) {
            error_log("Erro ao preparar a consulta: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("sdsi", $nome, $preco, $descricao, $id);
        return $stmt->execute();
    }

    public function excluirProduto($id) {
        $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id = ?");
        if (!$stmt) {
            error_log("Erro ao preparar a consulta: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

function processar_mensagem($erros, $adminManager, $nome, $preco, $descricao, $id = null) {
    if (empty($erros)) {
        if ($id) {
            if ($adminManager->editarProduto($id, $nome, $preco, $descricao)) {
                return "Produto atualizado com sucesso!";
            } else {
                return "Erro ao atualizar produto.";
            }
        } else {
            if ($adminManager->adicionarProduto($nome, $preco, $descricao)) {
                return "Produto adicionado com sucesso!";
            } else {
                return "Erro ao adicionar produto.";
            }
        }
    } else {
        return implode("<br >", $erros);
    }
}

// Inicializa o gerenciador
$adminManager = new AdminManager($conn);

// Gerar token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$msg = '';

// Verificação do token CSRF e recebimento de dados do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch');
    }

    if (isset($_POST['nome'])) {
        $nome = trim($_POST['nome']);
        $preco = $_POST['preco'];
        $descricao = trim($_POST['descricao']);
        $id = $_POST['id'] ?? null; // Captura o ID se estiver presente

        $erros = validar_produto($nome, $preco, $descricao);
        $msg = processar_mensagem($erros, $adminManager, $nome, $preco, $descricao, $id);
    }
}

// Buscar dados para exibição
$produtos = $adminManager->listarProdutos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gerenciar Produtos</title>
    <link rel="stylesheet" href="../../public/css/admin.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div id="notification-container"></div>
    <h1>Painel Administrativo</h1>
    
    <?php if (!empty($msg)): ?>
        <p class="message"><?php echo htmlspecialchars($msg); ?></p>
    <?php endif; ?>

<div id="addProductModal" class="add-modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Adicionar Novo Produto</h2>
        <form id="addProductForm" method="POST" action=".../controllers/add_product.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="id" id="productId" value="">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="preco">Preço:</label>
                <input type="number" id="preco" name="preco" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" required></textarea>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Selecione uma categoria</option>
                    <option value="Massas e Pães">Massas e Pães</option>
                    <option value="Salgados">Salgados</option>
                    <option value="Doces e Bolos">Doces e Bolos</option>
                    <option value="Sopas e Caldos">Sopas e Caldos</option>
                    <option value="Bebidas">Bebidas</option>

                </select>
            </div>
            <button type="submit" class="btn-primary">Adicionar Produto</button>
        </form>
    </div>
</div>

<!-- Seção de Produtos -->
<div class="admin-section">
    <div class="section-header">
        <h2>Produtos</h2>
        <button id="openModalBtn" class="btn-add">Adicionar Produto</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($produtos as $produto): ?>
<tr data-id="<?= htmlspecialchars($produto['id']) ?>">
    <td><?= htmlspecialchars($produto['id']) ?></td>
    <td><input type="text" class="editable" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" readonly></td>
    <td><input type="number" class="editable" name="preco" value="<?= number_format($produto['preco'], 2, '.', '') ?>" readonly></td>
    <td>
        <select class="editable" name="categoria" disabled>
            <option value="Massas e Pães" <?= $produto['categoria'] == 'Massas e Pães' ? 'selected' : '' ?>>Massas e Pães </option>
            <option value="Salgados" <?= $produto['categoria'] == 'Salgados' ? 'selected' : '' ?>>Salgados</option>
            <option value="Doces e Bolos" <?= $produto['categoria'] == 'Doces e Bolos' ? 'selected' : '' ?>>Doces e Bolos</option>
            <option value="Sopas e Caldos" <?= $produto['categoria'] == 'Sopas e Caldos' ? 'selected' : '' ?>>Sopas e Caldos</option>
        </select>
    </td>
<td>
    <button class="edit-btn" onclick="toggleEdit(<?= htmlspecialchars($produto['id']) ?>)">Editar</button>
    <button class="delete-btn" onclick="excluirProduto(<?= htmlspecialchars($produto['id']) ?>)">Excluir</button>
</td>
</tr>
<?php endforeach; ?>
        </tbody>
    </table>
</div>

    <script src="../../public/js/add-modal.js"></script>
    <script src="../../public/js/adicionar-produto.js" defer></script>
    <script src="../../public/js/notifications.js"></script>
    <script>
        function abrirModal(id, nome, preco, descricao) {
            document.getElementById('productId').value = id;
            document.getElementById('nome').value = nome;
            document.getElementById('preco').value = preco;
            document.getElementById('descricao').value = descricao;
            document.getElementById('addProductModal').style.display = 'block';
        }

        async function excluirProduto(id) {
    if (confirm("Você tem certeza que deseja excluir este produto?")) {
        try {
            const response = await fetch(`/ProjetoPadaria/src/controllers/excluir_produto.php`, {
                method: 'POST', // Usando POST em vez de DELETE
                headers: {
                    'Content-Type': 'application/json' // Define o tipo de conteúdo
                },
                body: JSON.stringify({ id: id }) // Enviando o ID do produto como JSON
            });

            if (!response.ok) {
                // Se a resposta não for OK, lança um erro
                throw new Error(`Erro ao excluir produto: ${response.status} ${response.statusText}`);
            }

            const result = await response.json(); // Espera pela resposta JSON
            if (result.success) {
                alert("Produto excluído com sucesso!");
                location.reload(); // Recarrega a página para atualizar a lista de produtos
            } else {
                alert(`Erro ao excluir produto: ${result.message}`);
            }
        } catch (error) {
            console.error("Erro:", error);
            alert("Ocorreu um erro ao tentar excluir o produto. Tente novamente mais tarde.");
        }
    }
}
    </script>
</body>
</html>