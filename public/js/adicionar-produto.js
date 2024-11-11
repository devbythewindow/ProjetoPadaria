// Atualiza o carrinho
function atualizarCarrinho() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');

    let cart = JSON.parse(localStorage.getItem('carrinho')) || [];
    
    // Limpa os itens atuais
    cartItemsContainer.innerHTML = ''; 
    let total = 0;

    cart.forEach((produto, index) => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');
        itemElement.innerHTML = `
            <div class="cart-item-header">
                <span class="item-name">${escapeHTML(produto.nome)}</span>
                <span class="delete-item" data-index="${index}">Remover</span>
            </div>
            <div class="cart-item-details">
                <div class="price-info">
                    <span class="unit-price">R$ ${parseFloat(produto.preco).toFixed(2)}</span>
                    <span class="quantity-controls">
                        <button class="decrease-quantity" data-index="${index}">-</button>
                        <input type="number" value="${produto.quantidade}" readonly />
                        <button class="increase-quantity" data-index="${index}">+</button>
                    </span>
                </div>
                <span class="item-total">R$ ${(produto.preco * produto.quantidade).toFixed(2)}</span>
            </div>
        `;
        cartItemsContainer.appendChild(itemElement);
        total += produto.preco * produto.quantidade;
    });

    cartTotal.textContent = `R$ ${total.toFixed(2)}`; // Atualiza o total do carrinho
}

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('decrease-quantity')) {
        const index = event.target.dataset.index;
        updateQuantity(index, -1);
    } else if (event.target.classList.contains('increase-quantity')) {
        const index = event.target.dataset.index;
        updateQuantity(index, 1); 
    }
});

function updateQuantity(index, change) {
    let cart = JSON.parse(localStorage.getItem('carrinho')) || [];
    const item = cart[index];
    if (item) {
        item.quantidade += change;
        if (item.quantidade <= 0) {
            cart.splice(index, 1); // Remove o item se a quantidade for 0
        }
        localStorage.setItem('carrinho', JSON.stringify(cart)); // Atualiza o localStorage
        atualizarCarrinho(); 
    }
}

function toggleEdit(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const inputs = row.querySelectorAll('.editable');

    inputs.forEach(input => {
        input.readOnly = !input.readOnly; // Alterna o estado de leitura
    });

    const select = row.querySelector('select');
    if (select) {
        select.disabled = !select.disabled; // Alterna o estado do select
    }

    const editButton = row.querySelector('.edit-btn');
    editButton.textContent = editButton.textContent === 'Editar' ? 'Salvar' : 'Editar'; // Altera o texto do botão
}