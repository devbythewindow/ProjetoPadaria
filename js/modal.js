let produtoParaExcluir; // Variável para armazenar o ID do produto a ser excluído

// Função para abrir o modal
function abrirModal(id) {
    produtoParaExcluir = id; // Armazena o ID do produto
    document.getElementById('modal').style.display = 'flex';
}

// Função para fechar o modal
function fecharModal() {
    document.getElementById('modal').style.display = 'none';
}

// Espera a página carregar para adicionar o event listener
document.addEventListener('DOMContentLoaded', function() {
    // Confirmar exclusão do produto
    document.getElementById('confirmar-exclusao').addEventListener('click', function() {
        if (produtoParaExcluir) {
            document.getElementById('delete-form-' + produtoParaExcluir).submit(); // Envia o formulário de exclusão
            fecharModal(); // Fecha o modal
        }
    });
});
