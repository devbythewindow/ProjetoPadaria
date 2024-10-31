<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café dos Alunos</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; }
        .product { border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; }
        .cart { margin-top: 20px; }
        .admin-button { 
            position: absolute; 
            top: 20px; 
            right: 20px; 
        }
        .admin-button button {
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
        <h1>Café dos Alunos</h1>

        <!-- Botão Admin -->
        <div class="admin-button">
            <a href="php/login.php" style="text-decoration: none;">
                <button>Admin</button>
            </a>
        </div>

        <h2>Menu</h2>
        <div id="product-list">
            <!-- Produtos adicionados no servidor -->
        </div>

        <h2>Carrinho</h2>
        <div class="cart">
            <ul id="cart-items"></ul>
            <p id="total-price">Total: R$ 0,00</p>
            <button onclick="checkout()">Finalizar Compra</button>
        </div>

        <?php
            // Conexão com o banco de dados
            require 'php/conexao.php';

            // Consulta para obter todos os produtos
            $sql = "SELECT id, nome, preco, descricao FROM produtos";
            $result = $conn->query($sql);

            // Array para armazenar os produtos para uso no JavaScript
            $produtos_js = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Adiciona o produto ao array de produtos
                    $produtos_js[] = [
                        "id" => $row["id"],
                        "nome" => $row["nome"],
                        "preco" => $row["preco"],
                        "descricao" => $row["descricao"]
                    ];
                }
            } else {
                echo "<p>Nenhum produto encontrado</p>";
            }

            $conn->close();
        ?>

    </div>

    <script>
        // Recebendo os produtos do PHP para o JavaScript
        const produtos = <?php echo json_encode($produtos_js); ?>;

        const carrinho = [];

        // Exibir produtos no menu
        const listaProdutos = document.getElementById('product-list');
        produtos.forEach((produto, index) => {
            const produtoDiv = document.createElement('div');
            produtoDiv.classList.add('product');
            produtoDiv.innerHTML = `
                <h3>${produto.nome}</h3>
                <p>Preço: R$ ${parseFloat(produto.preco).toFixed(2)}</p>
                <p>Descrição: ${produto.descricao}</p>
                <button onclick="adicionarAoCarrinho(${index})">Adicionar ao Carrinho</button>
            `;
            listaProdutos.appendChild(produtoDiv);
        });

        // Função para adicionar produto ao carrinho
        function adicionarAoCarrinho(index) {
            carrinho.push(produtos[index]);
            atualizarCarrinho();
        }

        // Função para atualizar o carrinho
        function atualizarCarrinho() {
            const listaCarrinho = document.getElementById('cart-items');
            listaCarrinho.innerHTML = '';
            let total = 0;
            carrinho.forEach((item, index) => {
                const itemLi = document.createElement('li');
                itemLi.innerHTML = `${item.nome} - R$ ${parseFloat(item.preco).toFixed(2)} <button onclick="removerDoCarrinho(${index})">Remover</button>`;
                listaCarrinho.appendChild(itemLi);
                total += parseFloat(item.preco);
            });
            document.getElementById('total-price').textContent = `Total: R$ ${total.toFixed(2)}`;
        }

        // Função para remover produto do carrinho
        function removerDoCarrinho(index) {
            carrinho.splice(index, 1);
            atualizarCarrinho();
        }

        // Função para finalizar a compra
        function checkout() {
            alert(`Total da compra: R$ ${carrinho.reduce((total, item) => total + parseFloat(item.preco), 0).toFixed(2)}`);
            carrinho.length = 0;
            atualizarCarrinho();
        }
    </script>
</body>
</html>
