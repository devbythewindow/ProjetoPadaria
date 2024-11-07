// modal.js

// Funções para o modal de adicionar produto
function setupAddProductModal() {
    const modal = document.getElementById("addProductModal");
    const btn = document.getElementById("checkout-btn");
    const span = document.getElementsByClassName("close-cart")[0];

    if (btn) {
        btn.onclick = function() {
            modal.style.display = "block";
        }
    } else {
        console.error("Botão para abrir o modal não encontrado.");
    }

    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    } else {
        console.error("Elemento de fechamento do modal não encontrado.");
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

// Funções para gerenciamento de produtos (admin)
function setupProductManagement() {
    const table = document.querySelector('.admin-section table');
    if (!table) return;

    table.addEventListener('click', function(e) {
        const target = e.target;
        const row = target.closest('tr');

        if (target.classList.contains('edit-btn')) {
            handleEdit(row, target);
        } else if (target.classList.contains('delete-btn')) {
            handleDelete(row);
        } else if (target.classList.contains('view-stock-btn')) {
            handleViewStock(row);
        }
    });
}

function handleEdit(row, button) {
    const inputs = row.querySelectorAll('.editable');
    const isEditing = button.textContent === 'Editar';

    inputs.forEach(input => {
        input.readOnly = !isEditing;
        input.style.backgroundColor = isEditing ? '#fffacd' : '';
    });

    button.textContent = isEditing ? 'Salvar' : 'Editar';

    if (!isEditing) {
        saveChanges(row);
    }
}

function handleDelete(row) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        const productId = row.dataset.id;
        deleteProduct(productId);
    }
}

function handleViewStock(row) {
    const productId = row.dataset.id;
    alert('Visualizar estoque do produto ' + productId);
}

function saveChanges(row) {
    const productId = row.dataset.id;
    const nome = row.querySelector('input[name="nome"]').value;
    const preco = row.querySelector('input[name="preco"]').value;

    fetch('/ProjetoPadaria/src/controllers/update_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: productId, nome: nome, preco: preco })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na requisição');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const inputs = row.querySelectorAll('.editable');
            inputs.forEach(input => {
                input.readOnly = true;
                input.style.backgroundColor = '';
            });

            const editButton = row.querySelector('.edit-btn');
            if (editButton) {
                editButton.textContent = 'Editar';
            }

            NotificationManager.success('Produto atualizado com sucesso!');
        } else {
            throw new Error(data.message || 'Erro ao atualizar produto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        NotificationManager.error('Erro ao atualizar produto: ' + error.message);
    });
}

function deleteProduct(productId) {
    fetch('/ProjetoPadaria/src/controllers/deleteProduct.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: productId }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            NotificationManager.success('Produto excluído com sucesso!');
            location.reload();
        } else {
            NotificationManager.error('Erro ao excluir produto.');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        NotificationManager.error('Erro ao excluir produto.');
    });
}

// Funções para o modal de checkout
function setupCheckoutModal() {
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutModal = document.getElementById('checkout-modal');
    const closeModal = document.querySelector('.close-modal');
    const orderSummary = document.getElementById('order-summary');

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            if (cart.length === 0) {
                showEmptyCartError();
                return;
            }

            // Gerar o resumo do pedido
            let summary = '';
            let total = 0;
            cart.forEach(item => {
                const subtotal = item.preco * (item.quantidade || 1);
                total += subtotal;
                summary += `
                    <div class="order-item">
                        <span>${item.nome}</span>
                        <span>${item.quantidade}x</span>
                        <span>R$ ${subtotal.toFixed(2)}</span>
                    </div>
                `;
            });

            // Atualizar o resumo do pedido
            if (orderSummary) {
                orderSummary.innerHTML = `
                    ${summary}
                    <div class="order-total">
                        <strong>Total:</strong>
                        <span>R$ ${total.toFixed(2)}</span>
                    </div>
                `;
            }

            // Gerar ticket number
            const ticketNumber = document.getElementById('ticket-number');
            if (ticketNumber) {
                ticketNumber.textContent = `#${Math.random().toString(36).substr(2, 9).toUpperCase()}`;
            }

            // Gerar QR Code
            const qrcodeElement = document.getElementById('qrcode');
            if (qrcodeElement) {
                qrcodeElement.innerHTML = ''; // Limpar QR code anterior
                const qrcode = new QRCode(qrcodeElement, {
                    text: JSON.stringify({
                        total: total,
                        ticket: ticketNumber.textContent,
                        timestamp: new Date().getTime()
                    }),
                    width: 256,
                    height: 256
                });
            }

            checkoutModal.style.display = 'block';
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function() {
            checkoutModal.style.display = 'none';
        });
    }

    // Fechar modal ao clicar fora
    window.addEventListener('click', function(event) {
        if (event.target == checkoutModal) {
            checkoutModal.style.display = 'none';
        }
    });
}

function showEmptyCartError() {
    const cartItems = document.getElementById('cart-items');
    const errorMessage = document.createElement('div');
    errorMessage.className = 'empty-cart-error';
    errorMessage.innerHTML = `
        <div class="empty-cart-content">
            <i class="fas fa-shopping-cart empty-cart-icon"></i>
            <h3>Ops! Carrinho Vazio</h3>
            <p>Seu carrinho está mais vazio que padaria em fim de expediente!</p>
            <p>Que tal dar uma olhada em nossos produtos deliciosos?</p>
            <button onclick="closeCart()" class="continue-shopping-btn">
                <i class="fas fa-utensils"></i> Explorar Cardápio
            </button>
        </div>
    `;
    
    cartItems.innerHTML = '';
    cartItems.appendChild(errorMessage);

    // Atualiza o total para zero
    document.getElementById('cart-total').textContent = 'R$ 0,00';
}

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    setupAddProductModal();
    setupProductManagement();
    setupCheckoutModal();
});