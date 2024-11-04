const carrinho = []; // Inicializa o carrinho

// Certifique-se de que 'produtos' está definido no escopo global
document.addEventListener('DOMContentLoaded', function() {
    if (typeof produtos !== 'undefined' && Array.isArray(produtos)) {
        exibirProdutos(produtos); // Chama a função para exibir os produtos
    } else {
        console.error('Erro: a variável produtos não está definida.');
    }
});

// Função para exibir produtos por categoria
function exibirProdutos(produtos) {
    const categorias = {
        "Massas e Pães": document.getElementById('carousel-massas-paes'),
        "Salgados": document.getElementById('carousel-salgados'),
        "Doces e Bolos": document.getElementById('carousel-doces-bolos')
    };

    produtos.forEach((produto, index) => {
        const produtoDiv = document.createElement('div');
        produtoDiv.classList.add('product');
        
        produtoDiv.innerHTML = `
            <h3>${produto.nome}</h3>
            <p>Preço: R$ ${parseFloat(produto.preco).toFixed(2)}</p>
            <p>Descrição: ${produto.descricao}</p>
        `;

        // Criando o botão via JavaScript para garantir o escopo correto
        const btn = document.createElement('button');
        btn.textContent = 'Adicionar ao Carrinho';
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
        console.log('Adicionando produto:', produtos[index]);
        carrinho.push(produtos[index]); // Adiciona o produto ao carrinho
        atualizarCarrinho(); // Atualiza a exibição do carrinho
    } else {
        console.error(`Erro: Produto com índice ${index} não encontrado.`);
    }
}

// Função para atualizar o carrinho
function atualizarCarrinho() {
    console.log('Atualizando carrinho. Itens atuais:', carrinho);
    const listaCarrinho = document.getElementById('cart-items');
    if (!listaCarrinho) {
        console.error('Erro: elemento #cart-items não encontrado.');
        return;
    }

    listaCarrinho.innerHTML = ''; // Limpa a lista de itens
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

// Função para finalizar a compra
function checkout() {
    alert(`Total da compra: R$ ${carrinho.reduce((total, item) => total + parseFloat(item.preco), 0).toFixed(2)}`);
    carrinho.length = 0; // Limpa o carrinho
    atualizarCarrinho(); // Atualiza a exibição do carrinho
}
