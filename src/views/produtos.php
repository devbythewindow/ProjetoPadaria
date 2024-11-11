<?php
require_once __DIR__ . '/../../php/conexao.php'; // Ajuste o caminho conforme necessário

header('Content-Type: application/json');

$sql = "SELECT id, nome, preco, descricao, categoria FROM produtos"; // Incluindo a categoria
$result = $conn->query($sql);

$produtos = [];
while ($row = $result->fetch_assoc()) {
    $produtos[] = $row;
}
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café dos Alunos</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
</head>
<body>
    <div class="container">
        <h1>Café dos Alunos</h1>

        <!-- Botão -->
        <div class="admin-button">
            <a href="php/login.php" style="text-decoration: none;">
                <button>Admin</button>
            </a>
        </div>

        <h2>Menu</h2>

        <div id="produtos-container"></div> <!-- Container para os produtos -->

        <h2>Carrinho</h2>
        <div class="cart">
            <ul id="cart-items"></ul>
            <p id="total-price">Total: R$ 0,00</p>
            <button onclick="checkout()">Finalizar Compra</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/carrinho.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Carregar produtos do banco de dados
        const produtos = <?php echo json_encode($produtos); ?>;

        // Verificar se produtos é um array
        console.log("Produtos carregados:", produtos);

        // Função para exibir produtos por categoria
        function exibirProdutosPorCategoria(categoria, produtos) {
            const container = document.getElementById('produtos-container');
            const filteredProdutos = produtos.filter(produto => produto.categoria === categoria);

            if (filteredProdutos.length > 0) {
                const carouselId = `carousel-${categoria.replace(/\s+/g, '-').toLowerCase()}`;
                let html = `<h3>${categoria}</h3><div class="swiper-container" id="${carouselId}"><div class="swiper-wrapper">`;

                filteredProdutos.forEach(produto => {
                    html += `
                        <div class="swiper-slide">
                            <div class="product">
                                <h4>${produto.nome}</h4>
                                <p>Descrição: ${produto.descricao}</p>
                                <p>Preço: R$ ${parseFloat(produto.preco).toFixed(2).replace('.', ',')}</p>
                                <button onclick="adicionarAoCarrinho(${produto.id})">Adicionar ao Carrinho</button>
                            </div>
                        </div>`;
                });

                html += `</div>
                         <div class="swiper-button-next"></div>
                         <div class="swiper-button-prev"></div>
                         </div><hr class="divider">`;
                container.innerHTML += html;

                // Inicializar Swiper
                new Swiper(`#${carouselId}`, {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: `#${carouselId} .swiper-button-next`,
                        prevEl: `#${carouselId} .swiper-button-prev`
                    },
                    breakpoints: {
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 }
                    }
                });
            } else {
                container.innerHTML += `<p>Nenhum produto encontrado na categoria ${categoria}.</p>`;
            }
        }

        // Exibir produtos por categoria
        const categorias = ["Massas e Pães", "Salgados", "Doces e Bolos"];
        categorias.forEach(categoria => exibirProdutosPorCategoria(categoria, produtos));
    </script>
</body>
</html>