fetch("../php/produtos.php")
.then(resposta => resposta.json())
.then(json => carregaElementosNaPagina(json))
.catch(console.error("Não carrega!"));

function carregaElementosNaPagina(json) {
    let table = document.createElement('table');

    let th1 = document.createElement('th');
    th1.innerHTML = "Número"
    table.appendChild(th1)

    let th2 = document.createElement('th');
    th2.innerHTML = "Nome"
    table.appendChild(th2)

    let th3 = document.createElement('th');
    th3.innerHTML = "Preço"
    table.appendChild(th3)

    let th4 = document.createElement('th');
    th4.innerHTML = "Descrição"
    table.appendChild(th4)

    let th5 = document.createElement('th');
    th5.innerHTML = "Ação"
    table.appendChild(th5)

for(let produto of json) {
    let tr = document.createElement('tr');

    let td1 = document.createElement('td');
    td1.innerHTML = produto.id_produto;
    tr.appendChild(td1);

    let td2 = document.createElement('td');
    td2.innerHTML = produto.nome;
    tr.appendChild(td2);

    let td3 = document.createElement('td');
    td3.innerHTML = produto.preco;
    tr.appendChild(td3);

    let td4 = document.createElement('td');
    td4.innerHTML = produto.descricao;
    tr.appendChild(td4);

    let td5 = document.createElement('td');
    td5.innerHTML = `<button class="visualizar" onclick="redirectEditar(${produto.id_produto})">Editar</button>`;
    tr.appendChild(td5);

    let td6 = document.createElement('td');
    td6.innerHTML =  `<button class="excluir" onclick="redirectExcluirProduto(${produto.id_produto})">Excluir</button>`;
    tr.appendChild(td6);

    table.appendChild(tr);
}

    let resultado = document.querySelector('#resultado');
    resultado.appendChild(table);
}

function redirectEditar(id){
    window.location.href = "../php/editarProduto.php?id="+id;
    localStorage.setItem('id', id)
}

function redirectExcluirProduto(id){
    localStorage.setItem('id', id)      
    var resposta = confirm("Deseja remover esse registro?");
    if (resposta == true) {
        window.location.href = "../php/excluirProduto.php?id="+id;
    }

}
