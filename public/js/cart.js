let cart = [];

function addToCart(productId, name, price) {
    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ id: productId, name, price, quantity: 1 });
    }
    updateCart();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCart();
}

function updateQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = newQuantity;
        updateCart();
    }
}

function updateCart() {
    const cartItems = document.getElementById('cart-items');
    const cartTotal = document.getElementById('cart-total');
    const cartCount = document.querySelector('.cart-count');

    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');
        itemElement.innerHTML = `
            <span class="cart-item-remove" onclick="removeFromCart(${item.id})">&times;</span>
            <span>${item.name}</span>
            <span>R$ ${item.price.toFixed(2)}</span>
            <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${item.id}, this.value)">
        `;
        cartItems.appendChild(itemElement);
        total += item.price * item.quantity;
    });

    cartTotal.textContent = `R$ ${total.toFixed(2)}`;
    cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);

    localStorage.setItem('cart', JSON.stringify(cart));
}

function openCart() {
    document.getElementById('cart-sidebar').classList.add('open');
}

document.addEventListener('DOMContentLoaded', function() {
    const closeCartButton = document.getElementById('close-cart');
    const cartSidebar = document.getElementById('cart-sidebar');

    if (closeCartButton && cartSidebar) {
        closeCartButton.addEventListener('click', function() {
            cartSidebar.classList.remove('open');
        });
    }
});

function closeCart() {
    console.log('Fechando o carrinho');
    document.getElementById('cart-sidebar').classList.remove('open');
}

document.getElementById('cart-icon').addEventListener('click', openCart);
document.getElementById('close-cart').addEventListener('click', closeCart);
document.getElementById('checkout-btn').addEventListener('click', function() {
    openCheckoutModal(); // Chama a função que abre o modal
});

// Carregar o carrinho do localStorage quando a página carrega
document.addEventListener('DOMContentLoaded', () => {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCart();
    }
});

