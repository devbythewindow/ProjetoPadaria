let produtos = [];
const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

const massasPaesWrapper = document.querySelector('#carousel-massas-paes .swiper-wrapper');
const salgadosWrapper = document.querySelector('#carousel-salgados .swiper-wrapper');
const docesBolosWrapper = document.querySelector('#carousel-doces-bolos .swiper-wrapper');
const sopasWrapper = document.querySelector('#carousel-sopas-caldos .swiper-wrapper');


function salvarCarrinho() {
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
}

function carregarProdutos() {
    return fetch('/ProjetoPadaria/src/Models/Product.php')
        .then(response => {
            console.log('Resposta do servidor:', response);
            if (!response.ok) {
                throw new Error('Erro na resposta do servidor: ' + response.status);
            }
            return response.json();
        })

        .then(data => {
            console.log('Dados recebidos:', data);
            if (!Array.isArray(data)) {
                throw new Error('Dados inválidos recebidos do servidor');
            }
            produtos = data;
            exibirProdutos(produtos);
        })
        .catch(error => {
            console.error('Erro ao carregar produtos:', error);
            mostrarMensagemErro('Erro ao carregar produtos. Por favor, tente novamente.');
        });
}

// Em product-list.js, modifique a parte do event listener do carrinho:

// cart.js
document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cart-icon');
    const closeCartBtn = document.getElementById('close-cart');

    if (cartIcon) {
        cartIcon.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const cartSidebar = document.getElementById('cart-sidebar');
            if (cartSidebar.classList.contains('open')) {
                closeCart();
            } else {
                openCart();
            }
        });
    }

    if (closeCartBtn) {
        closeCartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            closeCart();
        });
    }

    const savedCart = localStorage.getItem('carrinho');
    if (savedCart) {
        carrinho.push(...JSON.parse(savedCart));
        atualizarCarrinho();
    }
});

function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function exibirProdutos(produtos) {
    if(!produtos || produtos.length === 0) {
        console.log('Nenhum produto para exibir');
        return;
    }
    
    console.log('Exibindo produtos:', produtos);

    // Verificar se todos os elementos necessários existem
    if (!massasPaesWrapper || !salgadosWrapper || !docesBolosWrapper || !sopasWrapper) {
        console.error('Elementos do carrossel não encontrados');
        return;
    }
    
    // Limpar os containers
    massasPaesWrapper.innerHTML = '';
    salgadosWrapper.innerHTML = '';
    docesBolosWrapper.innerHTML = '';
    sopasWrapper.innerHTML = '';

    const categorias = {
        "Massas e Pães": massasPaesWrapper,
        "Salgados": salgadosWrapper,
        "Doces e Bolos": docesBolosWrapper,
        "Sopas e Caldos": sopasWrapper
    };

    produtos.forEach(produto => {
        if (categorias[produto.categoria]) {
            const slide = document.createElement('div');
            slide.classList.add('swiper-slide');
            
            slide.innerHTML = `
                <div class="product-card">
                    <div class="product-card-header">
                        <h3>${escapeHTML(produto.nome)}</h3>
                        <p class="price">R$ ${parseFloat(produto.preco).toFixed(2)}</p>
                    </div>
                    <p class="description">${escapeHTML(produto.descricao)}</p>
                    <button class="add-to-cart" data-product-id="${produto.id}">
                        <span>Adicionar</span>
                    </button>
                </div>
            `;
            
            categorias[produto.categoria].appendChild(slide);
        }
    });

    // Inicializar os Swipers após adicionar os produtos
    inicializarSwipers();
}

function inicializarSwipers() {
    const swiperConfig = {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 40,
            },
        }
    };

    new Swiper('#carousel-massas-paes', swiperConfig);
    new Swiper('#carousel-salgados', swiperConfig);
    new Swiper('#carousel-doces-bolos', swiperConfig);
    new Swiper('#carousel-sopas-caldos', swiperConfig);
}
function adicionarAoCarrinho(produtoId) {
    const produto = produtos.find(p => p.id === produtoId);
    if (produto) {
        const itemExistente = carrinho.find(item => item.id === produtoId);
        if (itemExistente) {
            itemExistente.quantidade = (itemExistente.quantidade || 1) + 1;
        } else {
            carrinho.push({...produto, quantidade: 1});
        }
        salvarCarrinho();
        atualizarCarrinho();
        atualizarContadorCarrinho();
        NotificationManager.success('Produto adicionado ao carrinho!');
    }
}

function atualizarCarrinho() {
    const listaCarrinho = document.getElementById('cart-items');
    if (!listaCarrinho) return;

    listaCarrinho.innerHTML = '';
    let total = 0;

    carrinho.forEach((item, index) => {
        const quantidade = item.quantidade || 1;
        const precoUnitario = parseFloat(item.preco);
        const subtotal = precoUnitario * quantidade;
        
        total += subtotal;

        const itemDiv = document.createElement('div');
        itemDiv.classList.add('cart-item');
        itemDiv.innerHTML = `
            <div class="cart-item-header">
                <span class="item-name">${escapeHTML(item.nome)}</span>
                <i class="fas fa-trash delete-item" onclick="removerDoCarrinho(${index})"></i>
            </div>
            <div class="cart-item-details">
                <div class="price-info">
                    <span class="unit-price">R$ ${precoUnitario.toFixed(2)}</span>
                    <div class="quantity-controls">
                        <button onclick="alterarQuantidade(${index}, -1)">-</button>
                        <input type="number" min="1" value="${quantidade}" 
                               onchange="atualizarQuantidadeManual(${index}, this.value)">
                        <button onclick="alterarQuantidade(${index}, 1)">+</button>
                    </div>
                </div>
                <span class="item-total">R$ ${subtotal.toFixed(2)}</span>
            </div>
        `;
        listaCarrinho.appendChild(itemDiv);
    });

    const totalElement = document.getElementById('cart-total');
    if (totalElement) {
        totalElement.textContent = `R$ ${total.toFixed(2)}`;
    }

    atualizarContadorCarrinho();
}

function atualizarContadorCarrinho() {
    const contador = document.querySelector('.cart-count');
    if (contador) {
        const totalItens = carrinho.reduce((sum, item) => sum + (item.quantidade || 1), 0);
        contador.textContent = totalItens;
    }
}

function removerDoCarrinho(index) {
    carrinho.splice(index, 1);
    salvarCarrinho();
    atualizarCarrinho();
}

function alterarQuantidade(index, delta) {
    if (!carrinho[index]) return;
    
    carrinho[index].quantidade = (carrinho[index].quantidade || 1) + delta;
    
    if (carrinho[index].quantidade <= 0) {
        carrinho.splice(index, 1);
    }
    
    salvarCarrinho();
    atualizarCarrinho();
}

function atualizarQuantidadeManual(index, novaQuantidade) {
    if (!carrinho[index]) return;
    
    const quantidade = parseInt(novaQuantidade);
    if (quantidade < 1) {
        carrinho[index].quantidade = 1;
    } else {
        carrinho[index].quantidade = quantidade;
    }
    
    salvarCarrinho();
    atualizarCarrinho();
}

function checkout() {
    if (carrinho.length === 0) {
        NotificationManager.error('Seu carrinho está vazio!');
        return;
    }

    const checkoutModal = document.getElementById('checkout-modal');
    if (checkoutModal) {
        const orderSummary = document.getElementById('order-summary');
        if (orderSummary) {
            let summary = '';
            let total = 0;
            carrinho.forEach(item => {
                const subtotal = item.preco * (item.quantidade || 1);
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
        }

        const ticketNumber = document.getElementById('ticket-number');
        if (ticketNumber) {
            ticketNumber.textContent = `#${Math.random().toString(36).substr(2, 9).toUpperCase()}`;
        }

        const qrcodeElement = document.getElementById('qrcode');
        if (qrcodeElement) {
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
        }

        checkoutModal.style.display = 'block';
    }
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', async function() {
    try {
        await carregarProdutos();
        
        // Adicionar event listener para os botões "Adicionar ao Carrinho"
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                adicionarAoCarrinho(parseInt(productId));
            });
        });

        // Carregar o carrinho do localStorage
        const savedCart = localStorage.getItem('carrinho');
        if (savedCart) {
            carrinho = JSON.parse(savedCart);
            atualizarCarrinho();
        }

        // Adicionar event listener para o botão de checkout
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', checkout);
        }

    } catch (error) {
        console.error('Erro ao inicializar:', error);
        NotificationManager.error('Erro ao inicializar a página. Por favor, recarregue.');
    }
});

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', async function() {
    try {
        await carregarProdutos();
        
        // Adicionar event listener para os botões "Adicionar ao Carrinho"
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                adicionarAoCarrinho(parseInt(productId));
            });
        });

    } catch (error) {
        console.error('Erro ao inicializar:', error);
        mostrarMensagemErro('Erro ao inicializar a página. Por favor, recarregue.');
    }
});