document.addEventListener('DOMContentLoaded', function () {
    const closeModalBtn = document.getElementById('close-modal');
    const checkoutModal = document.getElementById('checkout-modal');
    const checkoutBtn = document.getElementById('checkout-btn');

// Função para abrir o modal
function openCheckoutModal() {
    checkoutModal.style.display = 'flex'; // Define o display como flex para centralizar
    generateQRCode(); // Gera o QR Code
    generateTicket(); // Gera o ticket
    renderCartItems(); // Renderiza os itens do carrinho
}

    // Evento para abrir o modal ao clicar no botão "Finalizar Compra"
    checkoutBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Previne o comportamento padrão do botão
        openCheckoutModal();
    });

    // Função para fechar o modal
    function closeCheckoutModal() {
        checkoutModal.style.display = 'none'; // Esconde o modal
    }

    // Evento para fechar o modal
    closeModalBtn.addEventListener('click', closeCheckoutModal);

    // Fecha o modal se o usuário clicar fora do conteúdo do modal
    checkoutModal.addEventListener('click', function (event) {
        if (event.target === checkoutModal) {
            closeCheckoutModal(); // Esconde o modal ao clicar fora do conteúdo
        }
    });

    // Função para gerar QR Code
    function generateQRCode() {
        const qrCodeContainer = document.getElementById('qr-code');
        qrCodeContainer.innerHTML = ''; // Limpa o conteúdo anterior
        new QRCode(qrCodeContainer, {
            text: "https://www.youtube.com/watch?v=6_hl8AB7Uf0",
            width: 128,
            height: 128,
        });
    }

    // Função para gerar um ticket
    function generateTicket() {
        const ticketNumber = Math.floor(Math.random() * 10000); // Gera um número aleatório para o ticket
        document.getElementById('generated-ticket').innerText = ticketNumber;
    }

function renderCartItems() {
    const cartItemsList = document.getElementById('checkout-cart-items');
    cartItemsList.innerHTML = ''; // Limpa a lista anterior
    let totalPrice = 0;

    // Obtém os dados do carrinho do localStorage
    const cartData = localStorage.getItem('carrinho');
    let cart = [];

    // Tenta analisar os dados do carrinho
    if (cartData) {
        try {
            cart = JSON.parse(cartData);
        } catch (error) {
            console.error("Erro ao analisar o carrinho do localStorage:", error);
            cart = []; // Se houver um erro, mantém o carrinho como um array vazio
        }
    }

    // Verifica se o carrinho está vazio
    if (cart.length === 0) {
        cartItemsList.innerHTML = '<li>Seu carrinho está vazio.</li>';
        document.getElementById('checkout-total-price').innerText = `R$ 0,00`;
        return;
    }

    // Renderiza os itens do carrinho
    cart.forEach(item => {
        const listItem = document.createElement('li');
        listItem.innerText = `${item.nome} - ${item.quantity} x R$ ${item.preco.toFixed(2)}`;
        cartItemsList.appendChild(listItem);
        totalPrice += item.quantity * item.preco;
    });

    document.getElementById('checkout-total-price').innerText = `R$ ${totalPrice.toFixed(2)}`;
}

    // Evento para enviar a avaliação
    document.getElementById('submit-feedback').addEventListener('click', function () {
        const rating = document.querySelectorAll('.rating i.filled').length; // Conta estrelas preenchidas
        const feedbackText = document.getElementById('feedback-text').value;
        const ticket = document.getElementById('generated-ticket').innerText;

        // Lógica para enviar os dados para o banco de dados
        console.log({
            pedido : 'Pedido Exemplo',
            ticket: ticket,
            estrelas: rating,
            comentario: feedbackText,
            data: new Date().toLocaleString(),
        });

        // Limpar o formulário após o envio
        document.getElementById('feedback-text').value = '';
        document.querySelectorAll('.rating i').forEach(star => star.classList.remove('filled'));
    });

    // Evento para preencher as estrelas ao passar o mouse
    document.querySelectorAll('.rating i').forEach(star => {
        star.addEventListener('mouseover', function () {
            const rating = this.getAttribute('data-rating');
            document.querySelectorAll('.rating i').forEach(star => {
                star.classList.remove('filled');
                if (star.getAttribute('data-rating') <= rating) {
                    star.classList.add('filled');
                }
            });
        });

        star.addEventListener('click', function () {
            const rating = this.getAttribute('data-rating');
            document.querySelectorAll('.rating i').forEach(star => {
                star.classList.remove('filled');
                if (star.getAttribute('data-rating') <= rating) {
                    star.classList.add('filled');
                }
            });
        });
    });
});