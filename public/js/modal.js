function openCheckoutModal() {
    const modal = document.getElementById('checkout-modal');
    if (!modal) return;
    
    // Gera o QR Code
    const qrcodeDiv = document.getElementById('qrcode');
    qrcodeDiv.innerHTML = ''; // Limpa o conteúdo anterior
    
    // Calcula o total do carrinho
    const total = carrinho.reduce((sum, item) => {
        return sum + (item.preco * (item.quantidade || 1));
    }, 0);
    
    // Gera novo QR Code
    new QRCode(qrcodeDiv, {
        text: "https://www.youtube.com/watch?v=H-kxNBp4ja0",
        width: 128,
        height: 128
    });

    // Gera número do ticket
    const ticketNumber = `#${Math.random().toString(36).substr(2, 9).toUpperCase()}`;
    document.getElementById('ticket-number').textContent = ticketNumber;
    
    // Atualiza o resumo do pedido
    const orderSummary = document.getElementById('order-summary');
    orderSummary.innerHTML = carrinho.map(item => `
        <div class="order-item">
            <span>${item.nome} x${item.quantidade || 1}</span>
            <span>R$ ${(item.preco * (item.quantidade || 1)).toFixed(2)}</span>
        </div>
    `).join('');
    
    modal.style.display = 'block';
}

// Fecha o modal quando clicar no X
document.querySelector('.close-modal').addEventListener('click', function() {
    document.getElementById('checkout-modal').style.display = 'none';
});

// Fecha o modal quando clicar fora dele
window.addEventListener('click', function(event) {
    const modal = document.getElementById('checkout-modal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Sistema de avaliação
document.querySelectorAll('.rating i').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.querySelectorAll('.rating i').forEach(s => {
            // Inverte a lógica de comparação
            if (s.dataset.rating >= rating) {
                s.classList.remove('far');
                s.classList.add('fas');
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
            }
        });
    });
});
// Envio do feedback
document.getElementById('submit-feedback').addEventListener('click', function() {
    const rating = document.querySelectorAll('.rating i.fas').length;
    const feedback = document.getElementById('feedback-text').value;
    
    alert('Obrigado pelo seu feedback!');
    document.getElementById('checkout-modal').style.display = 'none';
    // Limpa o carrinho após o feedback
    carrinho.length = 0;
    salvarCarrinho();
    atualizarCarrinho();
});