// cart.js

let cart = JSON.parse(localStorage.getItem('carrinho')) || [];

function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const cartCount = document.querySelector('.cart-count');

    if (!cartItems || !cartTotal || !cartCount) return;

    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');
        itemElement.innerHTML = `
            <span>${item.nome}</span>
            <span>R$ ${parseFloat(item.preco).toFixed(2)}</span>
            <div class="quantity-controls">
                <button onclick="alterarQuantidade(${index}, -1)">-</button>
                <span>${item.quantidade}</span>
                <button onclick="alterarQuantidade(${index}, 1)">+</button>
            </div>
            <button onclick="removerDoCarrinho(${index})">Remover</button>
        `;
        cartItems.appendChild(itemElement);
        total += item.preco * item.quantidade;
    });

    cartTotal.textContent = `R$ ${total.toFixed(2)}`;
    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantidade, 0);

    localStorage.setItem('carrinho', JSON.stringify(cart));
}

function alterarQuantidade(index, delta) {
    if (cart[index]) {
        cart[index].quantidade = Math.max(1, cart[index].quantidade + delta);
        updateCart();
    }
}

function removerDoCarrinho(index) {
    cart.splice(index, 1);
    updateCart();
}

function openCart() {
    document.getElementById('cart-sidebar').classList.add('open');
}

function closeCart() {
    document.getElementById('cart-sidebar').classList.remove('open');
}

function checkout() {
    if (cart.length === 0) {
        NotificationManager.error('Seu carrinho está vazio!');
        return;
    }

    const checkoutModal = document.getElementById('checkout-modal');
    const orderSummary = document.getElementById('order-summary');
    let total = 0;

    let summary = '';
    cart.forEach(item => {
        const subtotal = item.preco * item.quantidade;
        total += subtotal;
        summary += `
            <div class="order-item">
                <span>${escapeHTML(item.nome)}</span>
                <span>${item.quantidade}x</span>
                <span>R$ ${subtotal.toFixed(2)}</span>
            </div>
        `;
    });

    summary += `
        <div class="order-total">
            <strong>Total:</strong>
            <span>R$ ${total.toFixed(2)}</span>
        </div>
    `;
    orderSummary.innerHTML = summary;

    const ticketNumber = document.getElementById('ticket-number');
    ticketNumber.textContent = `#${Math.random().toString(36).substr(2, 9).toUpperCase()}`;

    const qrcodeElement = document.getElementById('qrcode');
    qrcodeElement.innerHTML = '';
    new QRCode(qrcodeElement, {
        text: JSON.stringify({
            total: total,
            ticket: ticketNumber.textContent,
            timestamp: new Date().getTime()
        }),
        width: 256,
        height: 256
    });

    checkoutModal.style.display = 'block';
}

function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const closeCartBtn = document.getElementById('close-cart');
    const checkoutBtn = document.querySelector('.checkout-btn');
    const closeModalBtn = document.querySelector('.close-modal');
    const checkoutModal = document.getElementById('checkout-modal');
    const ratingStars = document.querySelectorAll('.rating .far');
    const submitFeedbackBtn = document.getElementById('submit-feedback');

    cartIcon.addEventListener('click', openCart);
    closeCartBtn.addEventListener('click', closeCart);
    checkoutBtn.addEventListener('click', checkout);

    closeModalBtn.addEventListener('click', () => {
        checkoutModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === checkoutModal) {
            checkoutModal.style.display = 'none';
        }
    });

    // Sistema de avaliação
    ratingStars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = star.getAttribute('data-rating');
            ratingStars.forEach(s => {
                s.classList.remove('fas');
                s.classList.add('far');
                if (s.getAttribute('data-rating') <= rating) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                }
            });
        });
    });

    submitFeedbackBtn.addEventListener('click', () => {
        const rating = document.querySelectorAll('.rating .fas').length;
        const feedback = document.getElementById('feedback-text').value;
        // Aqui você pode enviar a avaliação para o servidor
        console.log('Avaliação:', rating, 'Feedback:', feedback);
        NotificationManager.success('Obrigado pela sua avaliação!');
        checkoutModal.style.display = 'none';
    });

    updateCart();
});