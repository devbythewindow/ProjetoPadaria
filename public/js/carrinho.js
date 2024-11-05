let produtos = [];

const carrinho = []; // Inicializa o carrinho

function carregarProdutos() {
    // Corrigindo o caminho do fetch
    fetch('ProjetoPadaria/src/Models/Product.php')
        .then(response => {
            if (!response.ok) {
                console.log('Bem Sucedido:', data)
                throw new Error('Erro na resposta do servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data); // Para debug
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

function mostrarMensagemErro(mensagem) {
    console.error(mensagem); // Para debug
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
// Adicione a função escapeHTML
function escapeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Certifique-se de que 'produtos' está definido no escopo global
document.addEventListener('DOMContentLoaded', function() {
    carregarProdutos() .then();
    if (typeof produtos !== 'undefined' && Array.isArray(produtos)) {
        exibirProdutos(produtos); // Chama a função para exibir os produtos
    } else {
        console.error('Erro: a variável produtos não está definida.');
    }
});

// Função para exibir produtos por categoria
function exibirProdutos(produtos) {
    if(!produtos || produtos.length === 0) {
        console.log('Nenhum produto para exibir')
        return;
    }
    console.log('Exibindo produtos:', produtos);
    const categorias = {
        "Massas e Pães": document.getElementById('carousel-massas-paes'),
        "Salgados": document.getElementById('carousel-salgados'),
        "Doces e Bolos": document.getElementById('carousel-doces-bolos')
    };
    for (let categoria in categorias) {
        if (!categorias[categoria]) {
            console.error(`Elemento para categoria ${categoria} não encontrado`);
            return;
        }
    }

    produtos.forEach((produto, index) => {
        const produtoDiv = document.createElement('div');
        produtoDiv.classList.add('product');
        
        produtoDiv.innerHTML = `
            <h3>${escapeHTML(produto.nome)}</h3>
            <p>Preço: R$ ${parseFloat(produto.preco).toFixed(2)}</p>
            <p>Descrição: ${escapeHTML(produto.descricao)}</p>
        `;

        // Criar o botão separadamente
        const btn = document.createElement('button');
        btn.textContent = 'Adicionar ao Carrinho';
        // Adicionar o evento usando addEventListener
        btn.addEventListener('click', () => adicionarAoCarrinho(index));
        produtoDiv.appendChild(btn);

        // Adiciona o produto à categoria correspondente
        if (categorias[produto.categoria]) {
            categorias[produto.categoria].appendChild(produtoDiv);
        }
    });
}

// Função para adicionar produto ao carrinho
function adicionarAoCarrinho(index) {
    if (produtos && produtos[index]) {
        carrinho.push(produtos[index]); 
        atualizarCarrinho();
    } else {
        console.error(`Erro: Produto com índice ${index} não encontrado.`);
    }
}

// Função para atualizar o carrinho
function atualizarCarrinho() {
    const listaCarrinho = document.getElementById('cart-items');
    if (!listaCarrinho) {
        console.error('Erro: elemento #cart-items não encontrado.');
        return;
    }

    listaCarrinho.innerHTML = '';
    let total = 0;

    carrinho.forEach((item, index) => {
        const itemLi = document.createElement('li');
        itemLi.innerHTML = `${item.nome} - R$ ${parseFloat(item.preco).toFixed(2)} <button onclick="removerDoCarrinho(${index})">Remover</button>`;
        listaCarrinho.appendChild(itemLi);
        total += parseFloat(item.preco);
    });

    const totalPriceEl = document.getElementById('total-price');
    if (totalPriceEl) {
        totalPriceEl.textContent = `Total: R$ ${total.toFixed(2)}`;
    } else {
        console.error('Erro: elemento #total-price não encontrado.');
    }
}

// Função para remover produto do carrinho
function removerDoCarrinho(index) {
    carrinho.splice(index, 1); // Remove o item do carrinho
    atualizarCarrinho(); // Atualiza a exibição do carrinho
}

function checkout() {
    if (carrinho.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    
    const total = carrinho.reduce((soma, item) => soma + parseFloat(item.preco), 0);
    alert(`Total da compra: R$ ${total.toFixed(2)}`);
    carrinho.length = 0;
    atualizarCarrinho();
}

document.addEventListener('DOMContentLoaded', carregarProdutos);