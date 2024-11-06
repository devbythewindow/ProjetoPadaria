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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="public/css/modal.css">

</head>
<body>
<header>
    <div class="header-content">
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-mug-hot"></i>
                <h1>Café dos Alunos</h1>
            </div>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#produtos">Produtos</a></li>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="#contato">Contato</a></li>
                <li><a href="php/login.php">Login</a></li>
            </ul>
        </nav>
    </div>
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
    $sql = "SELECT COUNT(*) FROM produtos WHERE categoria = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_row()[0];

    if ($count > 0) {
        echo "<h3>$categoria</h3>";
        echo '<div class="swiper" id="'.$carouselId.'">';
        echo '<div class="swiper-wrapper">';
        
        // Buscar e exibir produtos
        $sql = "SELECT id, nome, preco, descricao FROM produtos WHERE categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            echo '<div class="swiper-slide">';
            echo '<div class="product-card">';
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
        
        echo '</div>';
        // Adicionar elementos de navegação e paginação
        echo '<div class="swiper-pagination"></div>';
        echo '<div class="swiper-button-next"></div>';
        echo '<div class="swiper-button-prev"></div>';
        echo '</div>';
    }
}
            
                $sql = "SELECT id, nome, preco, descricao FROM produtos WHERE categoria = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $categoria);
                $stmt->execute();
                $result = $stmt->get_result();
            
                
                echo '</div>';
                echo '<div class="swiper-pagination"></div>';
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
                echo '</div>';
            

            // Chamada das funções para cada categoria
            exibirProdutosPorCategoria("Massas e Pães", $conn, "carousel-massas-paes");
            exibirProdutosPorCategoria("Salgados", $conn, "carousel-salgados");
            exibirProdutosPorCategoria("Doces e Bolos", $conn, "carousel-doces-bolos");
            // Adicione esta função no seu arquivo adicionar-produto.js
            function handleError(error) {
            console.error('Error:', error);
            NotificationManager.error('Ocorreu um erro: ' + error.message);
            ?>
}

// E use-a nas suas chamadas fetch
.catch(error => handleError(error));
            ?>
        </section>

<footer>
    <div class="footer-content">
        <!-- Seção Sobre Nós -->
        <div class="footer-section about-us">
            <h3>Sobre Nós</h3>
            <ul>
                <li><a href="#">Nossa História</a></li>
                <li><a href="#">Nossa Equipe</a></li>
                <li><a href="#">Nossas Lojas</a></li>
                <li><a href="#">Trabalhe Conosco</a></li>
                <li><a href="#">Política de Privacidade</a></li>
                <li><a href="#">Termos de Uso</a></li>
            </ul>
        </div>

        <!-- Seção Contato -->
        <div class="footer-section contact">
            <h3>Contato</h3>
            <ul>
                <li><i class="fas fa-phone"></i> (11) 1234-5678</li>
                <li><i class="fas fa-envelope"></i> contato@cafedosalunos.com</li>
                <li><i class="fas fa-map-marker-alt"></i> Rua dos Estudantes, 123</li>
                <li><i class="fas fa-clock"></i> Seg-Sex: 7h às 22h</li>
                <li><i class="fas fa-clock"></i> Sáb-Dom: 8h às 20h</li>
            </ul>
        </div>

        <!-- Seção Redes Sociais -->
        <div class="footer-section social">
            <h3>Redes Sociais</h3>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <!-- Seção Newsletter -->
        <div class="footer-section newsletter">
            <h3>Newsletter</h3>
            <p>Receba nossas novidades e promoções!</p>
            <form class="newsletter-form">
                <input type="email" placeholder="Seu melhor e-mail">
                <button type="submit">Inscrever</button>
            </form>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="footer-bottom">
        <div class="footer-info">
            <p>CNPJ: 00.000.000/0000-00</p>
            <p>Café dos Alunos © 2023 - Todos os direitos reservados</p>
        </div>
        <div class="payment-methods">
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-cc-mastercard"></i>
            <i class="fab fa-cc-amex"></i>
            <i class="fab fa-pix"></i>
        </div>
    </div>
</footer>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const swiperConfig = {
        slidesPerView: 1,
        spaceBetween: 15,
        loop: false,
        watchOverflow: true, // Desativa o Swiper se não houver slides suficientes
        observer: true, // Atualiza o Swiper quando elementos são modificados
        observeParents: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
            dynamicBullets: true, // Melhor visual para muitos slides
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
                slidesPerView: 4, // Reduzido para 4 para melhor consistência
                spaceBetween: 30,
            }
        },
        // Adicionar lazy loading para melhor performance
        lazy: {
            loadPrevNext: true,
        },
    };

    // Inicializar Swipers com um pequeno delay para garantir que o DOM está pronto
    setTimeout(() => {
        const swipers = document.querySelectorAll('.swiper');
        swipers.forEach(swiperElement => {
            new Swiper(swiperElement, swiperConfig);
        });
    }, 100);
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