// modal.js

document.addEventListener('DOMContentLoaded', function() {
    // Obtém o modal
    var modal = document.getElementById("addProductModal");
    var btn = document.getElementById("openModalBtn");
    var span = document.getElementsByClassName("close")[0];
    var form = document.getElementById("addProductForm");
    const table = document.querySelector('.admin-section table');

    // Abrir modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Fechar modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Manipular envio do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Impede o comportamento padrão do formulário
        const formData = new FormData(form);

        fetch('/ProjetoPadaria/src/controllers/add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationManager.success('Produto adicionado com sucesso!');
                modal.style.display = "none";
                form.reset();
                setTimeout(() => {
                    location.reload(); // Recarrega a página após adicionar o produto
                }, 1500);
            } else {
                NotificationManager.error(data.message || 'Erro ao adicionar produto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            NotificationManager.error('Erro ao adicionar produto');
        });
    });

    // Gerenciar produtos existentes
    table.addEventListener('click', function(e) {
        const target = e.target;
        const row = target.closest('tr');

        if (!row) return;

        if (target.classList.contains('edit-btn')) {
            handleEdit(row);
        } else if (target.classList.contains('delete-btn')) {
            handleDelete(row);
        }
    });

function handleEdit(row) {
    const inputs = row.querySelectorAll('.editable');
    const editBtn = row.querySelector('.edit-btn');
    const categorySelect = row.querySelector('select[name="categoria"]');
    const isEditing = editBtn.textContent === 'Editar';

    if (isEditing) {
        // Entrar no modo de edição
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.disabled = false; // Habilita o select
                input.style.pointerEvents = 'auto'; // Permite interação
            } else {
                input.readOnly = false;
            }
            input.style.backgroundColor = '#fff3cd';
        });
        editBtn.textContent = 'Salvar';
    } else {
        // Salvar alterações
        const productId = row.dataset.id;
        const nome = row.querySelector('input[name="nome"]').value;
        const preco = row.querySelector('input[name="preco"]').value;
        const categoria = categorySelect.value;

        // Verifique se os campos obrigatórios estão preenchidos
        if (!nome || !preco || !categoria) {
            NotificationManager.error('Campos obrigatórios faltando');
            return;
        }

        const data = {
            id: productId,
            nome: nome,
            preco: preco,
            categoria: categoria
        };

        saveChanges(data, row, inputs, editBtn);
    }
}

function saveChanges(data, row, inputs, editBtn) {
    fetch('/ProjetoPadaria/src/controllers/update_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            NotificationManager.success('Produto atualizado com sucesso!');
            inputs.forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.disabled = true; // Desabilita o select
                    input.style.pointerEvents = 'none'; // Impede interação
                } else {
                    input.readOnly = true;
                }
                input.style.backgroundColor = '';
            });
            editBtn.textContent = 'Editar';
        } else {
            NotificationManager.error(result.message || 'Erro ao atualizar produto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        NotificationManager.error('Erro ao atualizar produto');
    });
}

    function handleDelete(row) {
        const productId = row.dataset.id;

        if (confirm('Tem certeza que deseja excluir este produto?')) {
            fetch('/ProjetoPadaria/src/controllers/deleteProduct.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: productId })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(`Erro do servidor: ${response.status} ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    NotificationManager.success('Produto excluído com sucesso!');
                    row.remove();
                } else {
                    NotificationManager.error(data.message || 'Erro ao excluir produto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                NotificationManager.error(`Erro ao excluir produto: ${error.message}`);
            });
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("addProductModal");
    const btn = document.getElementById("openModalBtn");
    const span = document.getElementsByClassName("close")[0];
    const form = document.getElementById("addProductForm");

    // Abrir modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Fechar modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Fechar modal ao clicar fora
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Manipular envio do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Impede o comportamento padrão do formulário

        const formData = new FormData(form);

        fetch('/ProjetoPadaria/src/controllers/add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationManager.success('Produto adicionado com sucesso!');
                modal.style.display = "none";
                form.reset();
                setTimeout(() => {
                    location.reload(); // Recarrega a página após adicionar o produto
                }, 1500);
            } else {
                NotificationManager.error(data.message || 'Erro ao adicionar produto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            NotificationManager.error('Erro ao adicionar produto');
        });
    });



function handleDelete(row) {
    const productId = row.dataset.id;
    console.log('Tentando excluir produto ID:', productId);

    if (confirm('Tem certeza que deseja excluir este produto?')) {
        fetch('/ProjetoPadaria/src/controllers/deleteProduct.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: productId })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Erro do servidor: ${response.status} ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                NotificationManager.success('Produto excluído com sucesso!');
                row.remove();
            } else {
                NotificationManager.error(data.message || 'Erro ao excluir produto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            NotificationManager.error(`Erro ao excluir produto: ${error.message}`);
        });
    }
}
});


 function handleDelete(row) {
    const productId = row.dataset.id;
    console.log('ID do produto:', productId); // Verifique o ID do produto

    // Confirmação do usuário antes de excluir o produto
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        fetch('/ProjetoPadaria/src/controllers/deleteProduct.php', {
            method: 'POST', // Método da requisição
            headers: {
                'Content-Type': 'application/json', // Define o tipo de conteúdo como JSON
            },
            body: JSON.stringify({ id: productId }) // Envia o ID do produto no corpo da requisição
        })
        .then(response => {
            console.log('Response:', response); // Verifica a resposta do servidor
            if (!response.ok) {
                throw new Error('Network response was not ok'); // Lança um erro se a resposta não for ok
            }
            return response.json(); // Tenta converter a resposta para JSON
        })
        .then(data => {
            if (data.success) {
                NotificationManager.success('Produto excluído com sucesso!'); // Notifica sucesso
                row.remove(); // Remove a linha da tabela
            } else {
                NotificationManager.error(data.message || 'Erro ao excluir produto'); // Notifica erro
            }
        })
        .catch(error => {
            console.error('Error:', error); // Loga o erro no console
            NotificationManager.error('Erro ao excluir produto'); // Notifica erro genérico
        });
    }
}