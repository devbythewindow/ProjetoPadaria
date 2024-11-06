<?php
session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../php/conexao.php';
require_once __DIR__ . '/../helpers/ValidationHelper.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../php/login.php');
    exit();
}

// Função de validação
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
        $sql = "SELECT p.*, e.quantidade as qtd_estoque 
                FROM produtos p 
                LEFT JOIN estoque e ON p.id = e.produto_id";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function adicionarProduto($nome, $preco, $descricao) {
        $stmt = $this->conn->prepare("INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $nome, $preco, $descricao);
        return $stmt->execute();
    }

    public function listarPedidos() {
        $sql = "SELECT p.*, COUNT(ip.id) as total_itens 
                FROM pedidos p 
                LEFT JOIN itens_pedido ip ON p.id = ip.pedido_id 
                GROUP BY p.id 
                ORDER BY p.data_pedido DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function atualizarStatusPedido($pedido_id, $novo_status) {
        $stmt = $this->conn->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $novo_status, $pedido_id);
        return $stmt->execute();
    }

    public function adicionarPromocao($produto_id, $desconto, $data_inicio, $data_fim) {
        $stmt = $this->conn->prepare("INSERT INTO promocoes (produto_id, desconto, data_inicio, data_fim) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $produto_id, $desconto, $data_inicio, $data_fim);
        return $stmt->execute();
    }

    public function atualizarEstoque($produto_id, $quantidade) {
        $stmt = $this->conn->prepare("INSERT INTO estoque (produto_id, quantidade) 
                                    VALUES (?, ?) 
                                    ON DUPLICATE KEY UPDATE quantidade = ?");
        $stmt->bind_param("iii", $produto_id, $quantidade, $quantidade);
        return $stmt->execute();
    }

    public function listarAvaliacoes() {
        $sql = "SELECT a.*, p.nome as produto_nome 
                FROM avaliacoes a 
                JOIN produtos p ON a.produto_id = p.id 
                ORDER BY a.data_avaliacao DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Inicializa o gerenciador
$adminManager = new AdminManager($conn);

// Gerar token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$msg = '';

// Verificação do token CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch');
    }
}

// Processamento das ações POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nome = $_POST['nome'] ?? '';
        $preco = $_POST['preco'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        
        $erros = validar_produto($nome, $preco, $descricao);
        if (empty($erros)) {
            if ($adminManager->adicionarProduto($nome, $preco, $descricao)) {
                $msg = "Produto adicionado com sucesso!";
            } else {
                $msg = "Erro ao adicionar produto.";
            }
        } else {
            $msg = implode("<br>", $erros);
        }
    }

    if (isset($_POST['atualizar_status_pedido'])) {
        $pedido_id = $_POST['pedido_id'];
        $novo_status = $_POST['novo_status'];
        if ($adminManager->atualizarStatusPedido($pedido_id, $novo_status)) {
            $msg = "Status do pedido atualizado com sucesso!";
        } else {
            $msg = "Erro ao atualizar status do pedido.";
        }
    }

    if (isset($_POST['adicionar_promocao'])) {
        $produto_id = $_POST['produto_id'];
        $desconto = $_POST['desconto'];
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];
        if ($adminManager->adicionarPromocao($produto_id, $desconto, $data_inicio, $data_fim)) {
            $msg = "Promoção adicionada com sucesso!";
        } else {
            $msg = "Erro ao adicionar promoção.";
        }
    }

    if (isset($_POST['atualizar_estoque'])) {
        $produto_id = $_POST['produto_id'];
        $quantidade = $_POST['quantidade'];
        if ($adminManager->atualizarEstoque($produto_id, $quantidade)) {
            $msg = "Estoque atualizado com sucesso!";
        } else {
            $msg = "Erro ao atualizar estoque.";
        }
    }
}

// Buscar dados para exibição
$produtos = $adminManager->listarProdutos();
$pedidos = $adminManager->listarPedidos();
$avaliacoes = $adminManager->listarAvaliacoes();
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


    <!-- Modal -->
 <!-- Modal -->
<div id="addProductModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Adicionar Novo Produto</h2>
        <form id="addProductForm" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
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
                    <button class="edit-btn">Editar</button>
                    <button class="delete-btn">Excluir</button>
                    <button class="view-stock-btn">Ver Estoque</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    <!-- Seção de Pedidos -->
    <div class="admin-section">
        <h2>Gerenciar Pedidos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?= htmlspecialchars($pedido['id']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                    <td class="status-<?= htmlspecialchars($pedido['status']) ?>"><?= htmlspecialchars($pedido['status']) ?></td>
                    <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                            <select name="novo_status">
                                <option value="pendente">Pendente</option>
                                <option value="em preparo">Em Preparo</option>
                                <option value="pronto">Pronto</option>
                                <option value="entregue">Entregue</option>
                            </select>
                            <button type="submit" name="atualizar_status_pedido">Atualizar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Seção de Avaliações -->
    <div class="admin-section">
        <h2>Avaliações</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Avaliação</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avaliacoes as $avaliacao): ?>
                <tr>
                    <td><?= htmlspecialchars($avaliacao['id']) ?></td>
                    <td><?= htmlspecialchars($avaliacao['produto_nome']) ?></td>
                    <td><?= htmlspecialchars($avaliacao['avaliacao']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($avaliacao['data_avaliacao'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="../../public/js/adicionar-produto.js" defer></script>
    <script src="../../public/js/notifications.js"></script>
</body>
</html>