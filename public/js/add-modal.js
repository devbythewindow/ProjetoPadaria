document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("addProductModal");
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = modal ? modal.querySelector(".close") : null; // Verifica se o modal existe
    const addProductForm = document.getElementById("addProductForm");

    // Verifica se todos os elementos necessários estão presentes
    if (!modal || !openModalBtn || !closeModalBtn || !addProductForm) {
        console.error("Um ou mais elementos não foram encontrados. Verifique os IDs.");
        return; // Sai da função se algum elemento não for encontrado
    }

    // Função para abrir o modal
    openModalBtn.onclick = function() {
        modal.style.display = "block";
    }

    // Função para fechar o modal
    function closeModal() {
        modal.style.display = "none";
        addProductForm.reset(); // Limpa o formulário ao fechar o modal
    }

    // Função para fechar o modal ao clicar no botão de fechar
    if (closeModalBtn) {
        closeModalBtn.onclick = closeModal;
    }

    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }

    // Manipular envio do formulário
    addProductForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Impede o envio padrão do formulário

        const formData = new FormData(addProductForm); // Coleta os dados do formulário
        formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value); // Adiciona o token CSRF

        try {
            const response = await fetch('/ProjetoPadaria/src/controllers/add_product.php', {
                method: 'POST',
                body: formData
            });

            // Log da resposta para verificar o que está sendo retornado
            const textResponse = await response.text(); // Obtém a resposta como texto
            console.log('Resposta do servidor:', textResponse); // Exibe a resposta no console

            // Verifica se a resposta foi bem-sucedida
            if (!response.ok) {
                console.error('Erro na resposta do servidor:', response.status, response.statusText);
                throw new Error('Erro na resposta do servidor: ' + response.statusText);
            }

            // Verifica se a resposta é um JSON válido
            try {
                const data = JSON.parse(textResponse); // Tenta analisar a resposta como JSON
                if (data.success) {
                    NotificationManager.success('Produto adicionado com sucesso!');
                    closeModal(); // Fecha o modal
                    await atualizarTabelaProdutos(); // Atualiza a tabela de produtos
                } else {
                    NotificationManager.error('Erro ao adicionar produto: ' + data.message);
                }
            } catch (jsonError) {
                console.error('Erro ao analisar JSON:', jsonError);
                NotificationManager.error('Erro ao processar a resposta do servidor. Resposta recebida: ' + textResponse);
            }
        } catch (error) {
            console.error('Erro:', error);
            NotificationManager.error('Erro ao adicionar produto. Tente novamente.');
        }
    });

    // Função para atualizar a tabela de produtos
    async function atualizarTabelaProdutos() {
        try {
            const response = await fetch('/ProjetoPadaria/src/views/produtos.php'); // Ajuste o caminho conforme necessário
            if (!response.ok) {
                throw new Error('Erro na rede: ' + response.statusText);
            }

            const produtos = await response.json();
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = ''; // Limpa a tabela existente

            produtos.forEach(produto => {
                const tr = document.createElement('tr');
                tr.setAttribute('data-id', produto.id);
                tr.innerHTML = `
                    <td>${produto.id}</td>
                    <td><input type="text" class="editable" name="nome" value="${produto.nome}" readonly></td>
                    <td><input type="number" class="editable" name="preco" value="${parseFloat(produto.preco).toFixed(2)}" readonly></td>
                    <td>
                        <select class="editable" name="categoria" disabled>
                            <option value="Massas e Pães" ${produto.categoria === 'Massas e Pães' ? 'selected' : ''}>Massas e Pães</option>
                            <option value="Salgados" ${ produto.categoria === 'Salgados' ? 'selected' : ''}>Salgados</option>
                            <option value="Doces e Bolos" ${produto.categoria === 'Doces e Bolos' ? 'selected' : ''}>Doces e Bolos</option>
                            <option value="Sopas e Caldos" ${produto.categoria === 'Sopas e Caldos' ? 'selected' : ''}>Sopas e Caldos</option>
                            <option value="Bebidas" ${produto.categoria === 'Bebidas' ? 'selected' : ''}>Bebidas</option>
                        </select>
                    </td>
                    <td>
                        <button class="edit-btn">Editar</button>
                        <button class="delete-btn">Excluir</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } catch (error) {
            console.error('Erro ao atualizar a tabela de produtos:', error);
        }
    }
});