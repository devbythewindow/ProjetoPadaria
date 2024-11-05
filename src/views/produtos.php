<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "projetopadaria");

$sql = "SELECT id, nome, preco, descricao FROM produto";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
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

        <?php
        require 'php/conexao.php';

        class ProdutosController {
            private $conn;
            
            public function __construct($conn) {
                $this->conn = $conn;
            }
            
            public function getProdutos() {
                $stmt = $this->conn->prepare("SELECT id, nome, preco, descricao, categoria FROM produtos");
                $stmt->execute();
                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }
        }
        
        // Uso
        $produtosController = new ProdutosController($conn);
        $produtos = $produtosController->getProdutos();
        header('Content-Type: application/json');
        echo json_encode($produtos);

        function exibirProdutosPorCategoria($categoria, $conn, $carouselId) {
            echo "<h3>$categoria</h3>";
            echo '<div class="swiper-container" id="'.$carouselId.'">';
            echo '<div class="swiper-wrapper">';
        
            $sql = "SELECT id, nome, preco, descricao FROM produtos WHERE categoria = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $categoria);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="swiper-slide">';
                    echo '<div class="product">';
                    echo '<h4>' . htmlspecialchars($row["nome"]) . '</h4>';
                    echo '<p>Descrição: ' . htmlspecialchars($row["descricao"]) . '</p>';
                    echo '<p>Preço: R$ ' . number_format($row["preco"], 2, ',', '.') . '</p>';
                    echo '<button onclick="adicionarAoCarrinho(' . $row["id"] . ')">Adicionar ao Carrinho</button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum produto encontrado nesta categoria.</p>";
            }
        
            echo '</div>';
            echo '<div class="swiper-button-next"></div>';
            echo '<div class="swiper-button-prev"></div>';
            echo '</div>';
            echo '<hr class="divider">';
        }

        exibirProdutosPorCategoria("Massas e Pães", $conn, "carousel-massas-paes");
        exibirProdutosPorCategoria("Salgados", $conn, "carousel-salgados");
        exibirProdutosPorCategoria("Doces e Bolos", $conn, "carousel-doces-bolos");

        $conn->close();
        ?>

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
    document.addEventListener('DOMContentLoaded', function () {
    const swiperMassas = new Swiper('#carousel-massas-paes', {
        slidesPerView: 3,
        spaceBetween: 20,
        navigation: {
            nextEl: '#carousel-massas-paes .swiper-button-next',
            prevEl: '#carousel-massas-paes .swiper-button-prev'
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });

    const swiperSalgados = new Swiper('#carousel-salgados', {
        slidesPerView: 3,
        spaceBetween: 20,
        navigation: {
            nextEl: '#carousel-salgados .swiper-button-next',
            prevEl: '#carousel-salgados .swiper-button-prev'
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });


    const swiperDoces = new Swiper('#carousel-doces-bolos', {
        slidesPerView: 3,
        spaceBetween: 20,
        navigation: {
            nextEl: '#carousel-doces-bolos .swiper-button-next',
            prevEl: '#carousel-doces-bolos .swiper-button-prev'
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
});
</script>
<script>
    const produtos = <?php echo json_encode($produtos); ?>;
</script>
</body>
</html>
