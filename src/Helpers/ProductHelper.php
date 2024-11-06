<?php
class ProductHelper {
    public static function exibirProdutosPorCategoria($categoria, $conn, $carouselId) {
        $sql = "SELECT id, nome, preco, descricao FROM produtos WHERE categoria = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $categoria);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<div class="swiper-section">';
        echo "<h3>$categoria</h3>";
        echo '<div class="swiper-container" id="'.$carouselId.'">';
        echo '<div class="swiper-wrapper">';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="swiper-slide">';
                echo '<div class="product-card">';
                echo '<h4>' . htmlspecialchars($row["nome"]) . '</h4>';
                echo '<p class="description">' . htmlspecialchars($row["descricao"]) . '</p>';
                echo '<p class="price">R$ ' . number_format($row["preco"], 2, ',', '.') . '</p>';
                echo '<button class="add-to-cart" onclick="adicionarAoCarrinho(' . $row["id"] . ')">Adicionar ao Carrinho</button>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="swiper-slide"><p>Nenhum produto encontrado nesta categoria.</p></div>';
        }

        echo '</div>';
        echo '<div class="swiper-pagination"></div>';
        echo '<div class="swiper-button-next"></div>';
        echo '<div class="swiper-button-prev"></div>';
        echo '</div>';
        echo '</div>';

        $stmt->close();
    }
}
?>