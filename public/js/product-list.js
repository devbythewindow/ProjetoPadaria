document.addEventListener('DOMContentLoaded', function() {
    const cartIcon = document.getElementById('cart-icon'); // ID do ícone do carrinho
    const cartSidebar = document.getElementById('cart-sidebar'); // ID do sidebar do carrinho
    const closeModal = document.getElementById('close-cart'); // ID do botão de fechar o carrinho
    const cartItemsContainer = document.getElementById('cart-items'); // ID do container de itens do carrinho
    const cartCountElement = document.querySelector('.cart-count'); // ID do elemento que mostra a quantidade no carrinho

    // Verifica se os elementos foram encontrados
    if (!cartIcon || !cartSidebar || !closeModal || !cartItemsContainer || !cartCountElement) {
        console.error("Um ou mais elementos não foram encontrados. Verifique os IDs.");
        return; // Sai da função se algum elemento não for encontrado
    }

    // Adiciona o evento de clique ao ícone do carrinho
    cartIcon.addEventListener('click', function() {
        if (cartSidebar.classList.contains('open')) {
            closeCart(); // Fecha o carrinho se estiver aberto
        } else {
            openCart(); // Abre o carrinho se estiver fechado
        }
    });

    // Adiciona o evento de clique ao botão de fechar
    closeModal.addEventListener('click', closeCart);

    // Função para abrir o carrinho
    function openCart() {
        cartSidebar.classList.add('open'); // Adiciona a classe open para mostrar o carrinho
        renderCartItems(); // Renderiza os itens do carrinho
    }

    // Função para fechar o carrinho
    function closeCart() {
        cartSidebar.classList.remove('open'); // Remove a classe open para esconder o carrinho
    }

    // Função para renderizar os itens do carrinho
    function renderCartItems() {
        if (!cartItemsContainer) {
            console.error("Elemento 'cart-items' não encontrado");
            return; // Sai da função se o elemento não for encontrado
        }

        if (typeof(Storage) === "undefined") {
            console.error("localStorage não está disponível neste ambiente.");
            return; // Sai da função se localStorage não estiver disponível
        }

        const cartData = localStorage.getItem('carrinho');
        let cart = []; // Inicializa o carrinho como um array vazio

        // Tenta analisar os dados do carrinho
        if (cartData) {
            try {
                cart = JSON.parse(cartData);
                if (!Array.isArray(cart)) {
                    console.error("Dados do carrinho não são um array. Inicializando como array vazio.");
                    cart = []; // Se não for um array, redefine como array vazio
                }
            } catch (error) {
                console.error("Erro ao analisar o carrinho do localStorage:", error);
                cart = []; // Se houver um erro, mantém o carrinho como um array vazio
            }
        }

        // Limpa o conteúdo anterior
        cartItemsContainer.innerHTML = ''; 

        // Verifica se o carrinho está vazio
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>Seu carrinho está vazio.</p>';
            updateTotal([]); // Passa um array vazio para updateTotal
            updateCartCount(); // Atualiza o contador do carrinho
            return;
        }

        // Renderiza os itens do carrinho
        cart.forEach(item => {
            const itemPrice = item.preco != null ? item.preco : 0; // Se preco for null, usa 0
            const itemQuantity = item.quantity != null ? item.quantity : 0; // Se quantity for null, usa 0
            const itemTotal = itemPrice * itemQuantity; // Calcula o total do item

            const itemElement = document.createElement('div');
            itemElement.classList.add('cart-item');
            itemElement.innerHTML = `
                <div class="cart-item-header">
                    <span class="item-name">${item.nome}</span>
                    <span class="item-price">R$ ${itemPrice.toFixed(2)}</span>
                </div>
                <div class="cart-item-details">
                    <span class="quantity-controls">
                        <button class="decrease" data-id="${item.id}">-</button>
                        <input type="number" value="${itemQuantity}" min="1" class="quantity-input" data-id="${item.id}">
                        <button class="increase" data-id="${item.id}">+</button>
                    </span>
                    <span class="item-total">R$ ${itemTotal.toFixed(2)}</span>
                </div>
            `;
            cartItemsContainer.appendChild(itemElement); // Adiciona o item ao container
        });

        // Atualiza o total geral do carrinho
        updateTotal(cart); // Passa o cart para updateTotal
        updateCartCount(); // Atualiza o contador do carrinho
        setupQuantityControls(); // Chama a função para configurar os botões de aumentar e diminuir
    }

    // Função para atualizar o total do carrinho
    function updateTotal(cart) {
        const totalElement = document.getElementById('total-price');
        if (!totalElement) {
            console.error("Elemento 'total-price' não encontrado");
            return; // Sai da função se o elemento não for encontrado
        }

        if (!Array.isArray(cart)) {
            console.error("O carrinho não é um array. Inicializando total como 0.");
            totalElement.innerText = `Total: R$ 0.00`;
            return;
        }

        const total = cart.reduce((acc, item) => {
            const itemPrice = item.preco || 0;
            const itemQuantity = item.quantity || 0;
            return acc + (itemPrice * itemQuantity);
        }, 0);

        totalElement.innerText = `Total: R$ ${total.toFixed(2)}`;
    }

    // Função para configurar os controles de quantidade
    function setupQuantityControls() {
        cartItemsContainer.removeEventListener('click', handleQuantityChange);
        cartItemsContainer.addEventListener('click', handleQuantityChange);
    }

    // Função para lidar com a mudança de quantidade
    function handleQuantityChange(event) {
        if (event.target.classList.contains('decrease')) {
            updateQuantity(event.target.dataset.id, -1); // Diminui a quantidade
        } else if (event.target.classList.contains('increase')) {
            updateQuantity(event.target.dataset.id, 1); // Aumenta a quantidade
        }
    }

    // Função para atualizar a quantidade dos itens no carrinho
    function updateQuantity(id, change) {
        const cart = JSON.parse(localStorage.getItem('carrinho')) || [];
        const item = cart.find(item => item.id == id);
        if (item) {
            item.quantity += change; // Atualiza a quantidade
            if (item.quantity <= 0) {
                const index = cart.indexOf(item);
                cart.splice(index, 1); // Remove o item se a quantidade for 0
            }
            localStorage.setItem('carrinho', JSON.stringify(cart)); // Atualiza o localStorage com o novo carrinho
            renderCartItems(); // Re-renderiza os itens do carrinho
            updateCartCount(); // Atualiza o contador após a mudança
        }
    }

    // Adiciona listeners para os botões "Adicionar ao Carrinho"
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.id;
            const itemName = this.dataset.name;
            const itemPrice = parseFloat(this.dataset.price);
            const cart = JSON.parse(localStorage.getItem('carrinho')) || [];
            const existingItem = cart.find(item => item.id == itemId);

            if (existingItem) {
                existingItem.quantity += 1; // Aumenta a quantidade se o item já estiver no carrinho
            } else {
                cart.push({ id: itemId, nome: itemName, preco: itemPrice, quantity: 1 }); // Adiciona novo item
            }

            localStorage.setItem('carrinho', JSON.stringify(cart)); // Atualiza o localStorage
            renderCartItems(); // Re-renderiza os itens do carrinho
            updateCartCount(); // Atualiza o contador após adicionar um item
        });
    });

    // Função para atualizar o contador do carrinho

        function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('carrinho')) || [];
        const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0); // Soma todas as quantidades
        if (cartCountElement) {
            cartCountElement.textContent = totalItems; // Atualiza o texto do contador
            console.log(`Contador do carrinho atualizado: ${totalItems}`); // Mensagem de depuração
        } else {
            console.error("Elemento 'cart-count' não encontrado.");
        }
    }

    // Função para zerar o carrinho
    function zerarCarrinho() {
        localStorage.removeItem('carrinho'); // Remove o carrinho do localStorage
        renderCartItems(); // Chama a função renderCartItems
        console.log("Carrinho zerado com sucesso!"); // Mensagem de confirmação no console
    }

    // Adiciona o evento de clique ao botão "Zerar Carrinho"
    const zerarCarrinhoBtn = document.getElementById('zerar-carrinho-btn');
    if (zerarCarrinhoBtn) {
        zerarCarrinhoBtn.addEventListener('click', function() {
            zerarCarrinho(); // Chama a função para zerar o carrinho
        });
    }

    // Atualiza o contador ao carregar a página
    updateCartCount(); // Chama a função para garantir que o contador esteja atualizado
});