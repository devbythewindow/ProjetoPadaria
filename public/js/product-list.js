let produtos = [];
const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

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

document.getElementById('cart-icon').addEventListener('click', function() {
    const cartSidebar = document.getElementById('cart-sidebar');
    if (cartSidebar.classList.contains('open')) {
        closeCart(); // Fecha o carrinho se ele estiver aberto
    } else {
        openCart(); // Abre o carrinho se ele estiver fechado
    }
});

function mostrarMensagemErro(mensagem) {
    console.error(mensagem);
    const mensagemErro = document.createElement('div');
    mensagemErro.classList.add('erro');
    mensagemErro.textContent = mensagem;
    document.body.appendChild(mensagemErro);
}

function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Função para exibir produtos por categoria
function exibirProdutos(produtos) {
    if(!produtos || produtos.length === 0) {
        console.log('Nenhum produto para exibir');
        return;
    }
    
    console.log('Exibindo produtos:', produtos);
    
    // Obter referências aos elementos
document.addEventListener('DOMContentLoaded', function () {
    const swiperMassas = new Swiper('#carousel-massas-paes', swiperConfig);
    const swiperSalgados = new Swiper('#carousel-salgados', swiperConfig);
    const swiperDoces = new Swiper('#carousel-doces-bolos', swiperConfig);
});
    // Verificar se todos os elementos necessários existem
    if (!massasPaesWrapper || !salgadosWrapper || !docesBolosWrapper) {
        console.error('Elementos do carrossel não encontrados');
        return;
    }
    
    // Limpar os containers
    massasPaesWrapper.innerHTML = '';
    salgadosWrapper.innerHTML = '';
    docesBolosWrapper.innerHTML = '';

    const categorias = {
        "Massas e Pães": massasPaesWrapper,
        "Salgados": salgadosWrapper,
        "Doces e Bolos": docesBolosWrapper
    };

    produtos.forEach(produto => {
        if (categorias[produto.categoria]) {
            const slide = document.createElement('div');
            slide.classList.add('swiper-slide');
            
            const produtoDiv = document.createElement('div');
            produtoDiv.classList.add('product');
            
            produtoDiv.innerHTML = `
                <div class="product-card">
                    <div class="product-card-header">
                        <h3>${escapeHTML(produto.nome)}</h3>
                        <p class="price">R$ ${parseFloat(produto.preco).toFixed(2)}</p>
                    </div>
                    <p class="description">${escapeHTML(produto.descricao)}</p>
                    <button class="add-to-cart" onclick="adicionarAoCarrinho(${produto.id})">
                        <span>Adicionar</span>
                    </button>
                </div>
            `;
            
            slide.appendChild(produtoDiv);
            categorias[produto.categoria].appendChild(slide);
        }
    });

    // Inicializar os Swipers após adicionar os produtos
    inicializarSwipers();
}

function inicializarSwipers() {
    const swiperConfigs = {
        slidesPerView: 3,
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 10
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    };

    new Swiper('#carousel-massas-paes', swiperConfigs);
    new Swiper('#carousel-salgados', swiperConfigs);
    new Swiper('#carousel-doces-bolos', swiperConfigs);
}

// Função para adicionar produto ao carrinho
function adicionarAoCarrinho(produtoId) {
    const produto = produtos.find(p => p.id === produtoId);
    if (produto) {
        const itemExistente = carrinho.find(item => item.id === produtoId);
        if (itemExistente) {
            itemExistente.quantidade = (itemExistente.quantidade || 1) + 1;
        } else {
            produto.quantidade = 1;
            carrinho.push(produto);
        }
        salvarCarrinho();
        atualizarCarrinho();
        
        const button = document.querySelector(`button[onclick="adicionarAoCarrinho(${produtoId})"]`);
        button.classList.add('added');
        setTimeout(() => {
            button.classList.remove('added');
        }, 1000);

        atualizarContadorCarrinho();
    }
}

function atualizarContadorCarrinho() {
    const contador = document.querySelector('.cart-count');
    if (contador) {
        const totalItens = carrinho.reduce((sum, item) => sum + (item.quantidade || 1), 0);
        contador.textContent = totalItens;
    }
}

// Função para atualizar o carrinho
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
}function alterarQuantidade(index, delta) {
    if (!carrinho[index]) return;
    
    carrinho[index].quantidade = (carrinho[index].quantidade || 1) + delta;
    
    if (carrinho[index].quantidade <= 0) {
        // Remove o item do carrinho
        carrinho.splice(index, 1);
    }
    
    salvarCarrinho();
    atualizarCarrinho();
}

// Função para remover produto do carrinho
function removerDoCarrinho(index) {
    carrinho.splice(index, 1);
    salvarCarrinho ();
    atualizarCarrinho();
}

// Função para finalizar a compra
function checkout() {
    if (carrinho.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    openCheckoutModal();

    // Calcular o total corretamente
    const total = carrinho.reduce((sum, item) => {
        return sum + (item.preco * (item.quantidade || 1));
    }, 0);
    
    if (confirm(`Total da compra: R$ ${total.toFixed(2)}\nDeseja finalizar a compra?`)) {
        carrinho.length = 0;
        salvarCarrinho();
        atualizarCarrinho();
        alert('Compra finalizada com sucesso!');
    }
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', async function() {
    try {
        await carregarProdutos();
    } catch (error) {
        console.error('Erro ao inicializar:', error);
        mostrarMensagemErro('Erro ao inicializar a página. Por favor, recarregue.');
    }
});