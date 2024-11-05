<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café dos Alunos - Carrinho</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Seu Carrinho</h1>
        <nav>
            <ul>
                <li><a href="<?= SITE_URL ?>">Continuar Comprando</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (empty($cartItems)): ?>
            <p>Seu carrinho está vazio.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= SecurityHelper::escapeHTML($item['name']) ?></td>
                            <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                            <td>
                                <form action="<?= SITE_URL ?>/index.php?action=removeFromCart" method="post">
                                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= SecurityHelper::generateCSRFToken() ?>">
                                    <button type="submit">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <td>R$ <?= number_format($total, 2, ',', '.') ?></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <form action="<?= SITE_URL ?>/index.php?action=checkout" method="post">
                <input type="hidden" name="csrf_token" value="<?= SecurityHelper::generateCSRFToken() ?>">
                <button type="submit">Finalizar Compra</button>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2023 Café dos Alunos. Todos os direitos reservados.</p>
    </footer>

    <script src="/js/cart.js"></script>
</body>
</html>