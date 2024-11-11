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
    <link rel="stylesheet" href="<?= SITE_URL ?>/public/css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#produtos"><i class="fas fa-bread-slice"></i> Produtos</a></li>
                <li><a href="#sobre"><i class="fas fa-info-circle"></i> Sobre</a></li>
                <li><a href="#contato"><i class="fas fa-envelope"></i> Contato</a></li>
                <li class="admin-login"><a href="php/login.php"><i class="fas fa-user-shield"></i> Admin</a></li>
                <li>
                    <div class="cart-icon" id="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cart-count">0</span>
                    </div>
                </li>
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
        <div id="checkout-modal" class="checkout-modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal" id="close-modal">&times;</span>
        <h2>Selecione o modo de pagamento</h2>
        <div class="payment-methods">
            <div class="payment-option" id="cash-option">
                <i class="fas fa-money-bill-wave"></i>
                <p>Apresente seu dinheiro no balcão e valide seu ticket.</p>
            </div>
            <div class="payment-option" id="qr-code-option">
                <i class="fas fa-qrcode"></i>
                <p>Pagamento via QR Code</p>
                <div id="qr-code"></div>
            </div>
        </div>
        <div class="cart-summary">
            <h3>Produtos do Carrinho</h3>
            <ul id="checkout-cart-items"></ul>
            <p>Total: <span id="checkout-total-price">R$ 0,00</span></p>
            <h3>Ticket</h3>
            <p id="ticket-number">Ticket #: <span id="generated-ticket"></span></p>
            <h3>Avalie seu pedido</h3>
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
                if (!$stmt->execute()) {
                    echo "Erro ao executar a consulta: " . $stmt->error;
                    return;
                }
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="swiper-slide">';
                    echo '<div class="product-card">';
                    echo '<div class="product-card-header">';
                    echo '<h4>' . htmlspecialchars($row["nome"]) . '</h4>';
                    echo '<p class="price">R$ ' . number_format($row["preco"], 2, ',', '.') . '</p>';
                    echo '</div>';
                    echo '<p class="description">' . htmlspecialchars($row["descricao"]) . '</p>';
                    echo '<button class="add-to-cart" data-id="' . $row["id"] . '" data-name="' . htmlspecialchars ($row["nome"]) . '" data-price="' . $row["preco"] . '">Adicionar ao Carrinho</button>';
                    echo '</div>'; // Fecha product-card
                    echo '</div>'; // Fecha swiper-slide
                }
                
                echo '</div>'; // Fecha swiper-wrapper
                // Adicionar elementos de navegação e paginação
                echo '<div class="swiper-pagination"></div>';
                echo '<div class="swiper-button-next"></div>';
                echo '<div class="swiper-button-prev"></div>';
                echo '</div>'; // Fecha swiper
            } else {
                echo "<h3>Nenhum produto encontrado na categoria $categoria.</h3>";
            }
        }

        // Chamada das funções para cada categoria
        exibirProdutosPorCategoria("Massas e Pães", $conn, "carousel-massas-paes");
        exibirProdutosPorCategoria("Salgados", $conn, "carousel-salgados");
        exibirProdutosPorCategoria("Doces e Bolos", $conn, "carousel-doces-bolos");
        exibirProdutosPorCategoria("Sopas e Caldos", $conn, "carousel-sopas-caldos");
        exibirProdutosPorCategoria("Bebidas", $conn, "carousel-bebidas");        
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
                    <li><i class="fas fa-phone"></i> (85) 9416-8351</li>
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
            watchOverflow: true,
            observer: true,
            observeParents: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl : ".swiper-button-next",
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
                    slidesPerView: 4,
                    spaceBetween: 30,
                }
            },
            lazy: {
                loadPrevNext: true,
            },
        };

        const swipers = document.querySelectorAll('.swiper');
        swipers.forEach(swiperElement => {
            new Swiper(swiperElement, swiperConfig);
        });
    });
    </script>

    <div class="carrinho-container" id="cart-sidebar">
        <button id="zerar-carrinho-btn">Zerar Carrinho</button>
        <div class="cart-header">
            <h2>Carrinho</h2>
            <span class="close-cart" id="close-cart">&times;</span>
        </div>
        <div class="cart-items" id="cart-items">
            <!-- Itens do carrinho serão adicionados aqui -->
        </div>
        <div class="cart-total">
            <strong>Total:</strong> <span id="total-price">R$ 0,00</span>
        </div>
        <button class="checkout-btn" id="checkout-btn">Finalizar Compra</button>
    </div>

    <div id="notification-container"></div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="public/js/product-list.js"></script>
    <script src="public/js/notifications.js"></script>
    <script src="public/js/checkout.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>