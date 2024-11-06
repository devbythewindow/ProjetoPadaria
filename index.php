<?php
require_once 'php/conexao.php';
require_once __DIR__ . '/src/helpers/ProductHelper.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café dos Alunos</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="public/css/modal.css">

</head>
<body>
<header>
    <nav>
        <div class="logo-container">
            <img src="src/img/logo.png" alt="logo" class="logo">
            <h1>Café dos Alunos</h1>
        </div>
        <ul class="nav-links">
            <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="#produtos"><i class="fas fa-store"></i> Produtos</a></li>
            <li><a href="#sobre"><i class="fas fa-info-circle"></i> Sobre</a></li>
            <li><a href="#contato"><i class="fas fa-envelope"></i> Contato</a></li>
            <li><a href="src/views/login.php"><i class="fas fa-user"></i> Login</a></li>
            <li>
        <div class="cart-icon" id="cart-icon">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">0</span>
        </div>
    </li>
</ul>
    </nav>
</header>

    <main>
        <section id="home" class="hero">
            <div class="hero-content">
                <h2>Bem-vindo ao Café dos Alunos</h2>
                <p>Descubra nossos deliciosos produtos!</p>
            </div>
        </section>

        <section id="produtos" class="produtos">
            <h2>Nossos Produtos</h2>

            <?php
function exibirProdutosPorCategoria($categoria, $conn, $carouselId) {
    echo "<h3>$categoria</h3>";
    echo '<div class="swiper" id="' . $carouselId . '">'; // Adicione o ID aqui
    echo '<div class="swiper-wrapper">';
    // ... resto do código existente ...
}
            
                $sql = "SELECT id, nome, preco, descricao FROM produtos WHERE categoria = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $categoria);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="swiper-slide">';
                        echo '<div class="product-card">';
                        // Novo div para agrupar nome e preço
                        echo '<div class="product-card-header">';
                        echo '<h4>' . htmlspecialchars($row["nome"]) . '</h4>';
                        echo '<p class="price">R$ ' . number_format($row["preco"], 2, ',', '.') . '</p>';
                        echo '</div>';
                        echo '<p class="description">' . htmlspecialchars($row["descricao"]) . '</p>';
                        echo '<button class="add-to-cart" onclick="adicionarAoCarrinho(' . $row["id"] . ')">';
                        echo '<span>Adicionar</span></button>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="swiper-slide"><p class="no-products">Nenhum produto encontrado nesta categoria.</p></div>';
                }
                
                echo '</div>';
                echo '<div class="swiper-pagination"></div>';
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
                echo '</div>';
            

            // Chamada das funções para cada categoria
            exibirProdutosPorCategoria("Massas e Pães", $conn, "carousel-massas-paes");
            exibirProdutosPorCategoria("Salgados", $conn, "carousel-salgados");
            exibirProdutosPorCategoria("Doces e Bolos", $conn, "carousel-doces-bolos");
            ?>
        </section>

        <section id="sobre" class="sobre">
            <h2>Sobre Nós</h2>
            <p>O Café dos Alunos é um lugar acolhedor onde você pode desfrutar de deliciosos produtos de padaria.</p>
        </section>

        <section id="contato" class="contato">
            <h2>Contato</h2>
            <p>Entre em contato conosco: contato@cafedosalunos.com</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Café dos Alunos. Todos os direitos reservados.</p>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperConfig = {
            slidesPerView: 1,
            spaceBetween: 15,
            loop: false,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 25,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                },
                1200: {
                    slidesPerView: 5,
                    spaceBetween: 30,
                }
            },
        };

        // Inicializa cada Swiper separadamente
        const swipers = document.querySelectorAll('.swiper');
        swipers.forEach(swiperElement => {
            new Swiper(swiperElement, swiperConfig);
        });
    });
    </script>
    <script>
        document.getElementById('cart-icon').addEventListener('click', function() {
    const cartSidebar = document.getElementById('cart-sidebar');
    if (cartSidebar.classList.contains('open')) {
        closeCart(); // Fecha o carrinho se ele estiver aberto
    } else {
        openCart(); // Abre o carrinho se ele estiver fechado
    }
});
    </script>
    <div class="cart-sidebar" id="cart-sidebar">
    <div class="cart-header">
        <h2>Carrinho</h2>
        <span class="close-cart" id="close-cart">&times;</span>
    </div>
    <div class="cart-items" id="cart-items">
        <!-- Itens do carrinho serão adicionados aqui dinamicamente -->
    </div>
    <div class="cart-total">
        <strong>Total:</strong> <span id="cart-total">R$ 0,00</span>
    </div>
    <button class="checkout-btn" id="checkout-btn">Finalizar Compra</button>
</div>
<div id="checkout-modal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-grid">
            <div class="qr-section">
                <h3>Para proceder, faça o pagamento do QR code abaixo</h3>
                <div id="qrcode"></div>
            </div>
            
            <div class="order-details">
                <h3>Seu Pedido</h3>
                <div id="order-summary"></div>
                <div class="ticket-info">
                    <h4>Seu Ticket</h4>
                    <span id="ticket-number"></span>
                </div>
                
                <div class="satisfaction-survey">
                    <h4>Avalie sua Experiência</h4>
                    <div class="rating">
                        <i class="far fa-star" data-rating="1"></i>
                        <i class="far fa-star" data-rating="2"></i>
                        <i class="far fa-star" data-rating="3"></i>
                        <i class="far fa-star" data-rating="4"></i>
                        <i class="far fa-star" data-rating="5"></i>
                    </div>
                    <textarea placeholder="Deixe seu comentário (opcional)" id="feedback-text"></textarea>
                    <button id="submit-feedback" class="submit-btn">Enviar Avaliação</button>
                </div>
            </div>
        </div>
    </div>
</div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- No final do body -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="public/js/product-list.js"></script>
    <script src="public/js/cart.js"></script>
    <script src="public/js/modal.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>