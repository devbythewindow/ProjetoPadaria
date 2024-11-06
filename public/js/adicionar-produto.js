// modal.js

// Obtém o modal
var modal = document.getElementById("addProductModal");

// Obtém o botão que abre o modal
var btn = document.getElementById("openModalBtn");

// Obtém o elemento <span> que fecha o modal
var span = document.getElementsByClassName("close")[0];

// Quando o usuário clica no botão, abre o modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// Quando o usuário clica no <span> (x), fecha o modal
span.onclick = function() {
    modal.style.display = "none";
}

// Quando o usuário clica fora do modal, fecha-o
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }


// Obtém o modal
var modal = document.getElementById("addProductModal");

// Obtém o botão que abre o modal
var btn = document.getElementById("openModalBtn");

// Obtém o elemento <span> que fecha o modal
var span = document.getElementsByClassName("close")[0];

// Quando o usuário clica no botão, abre o modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// Quando o usuário clica no <span> (x), fecha o modal
span.onclick = function() {
    modal.style.display = "none";
}

// Quando o usuário clica fora do modal, fecha-o
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Obtém o modal
var modal = document.getElementById("addProductModal");

// Obtém o botão que abre o modal
var btn = document.getElementById("openModalBtn");

// Obtém o elemento <span> que fecha o modal
var span = document.getElementsByClassName("close")[0];

// Quando o usuário clica no botão, abre o modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// Quando o usuário clica no <span> (x), fecha o modal
span.onclick = function() {
    modal.style.display = "none";
}

// Quando o usuário clica fora do modal, fecha-o
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('.admin-section table');
    
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
    
    function handleEdit(row, button) {
        const inputs = row.querySelectorAll('.editable');
        const isEditing = button.textContent === 'Editar';
        
        inputs.forEach(input => {
            input.readOnly = !isEditing;
            if (isEditing) {
                input.style.backgroundColor = '#fffacd';
            } else {
                input.style.backgroundColor = '';
            }
        });
        
        button.textContent = isEditing ? 'Salvar' : 'Editar';
        
        if (!isEditing) {
            // Aqui você implementaria a lógica para salvar as alterações
            saveChanges(row);
        }
    }
    
    function handleDelete(row) {
        if (confirm('Tem certeza que deseja excluir este produto?')) {
            // Aqui você implementaria a lógica para excluir o produto
            const productId = row.dataset.id;
            deleteProduct(productId);
        }
    }
    
    function handleViewStock(row) {
        const productId = row.dataset.id;
        // Aqui você implementaria a lógica para visualizar o estoque
        viewStock(productId);
    }
    
function saveChanges(row) {
    const productId = row.dataset.id;
    const nome = row.querySelector('input[name="nome"]').value;
    const preco = row.querySelector('input[name="preco"]').value;
    
    fetch('/ProjetoPadaria/src/controllers/update_product.php', {  // Corrija o caminho do endpoint
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: productId,
            nome: nome,
            preco: preco
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na requisição');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Atualiza a interface do usuário
            const inputs = row.querySelectorAll('.editable');
            inputs.forEach(input => {
                input.readOnly = true;
                input.style.backgroundColor = '';
            });
            
            // Atualiza o botão
            const editButton = row.querySelector('.edit-btn');
            if (editButton) {
                editButton.textContent = 'Editar';
            }

            // Mostra mensagem de sucesso
            alert('Produto atualizado com sucesso!');
        } else {
            throw new Error(data.message || 'Erro ao atualizar produto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao atualizar produto: ' + error.message);
        
        // Reverte as mudanças visuais em caso de erro
        const inputs = row.querySelectorAll('.editable');
        inputs.forEach(input => {
            input.readOnly = true;
            input.style.backgroundColor = '';
        });
    });
}
function deleteProduct(productId) {
    fetch('src/controllers/deleteProduct.php', {
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
    
    function viewStock(productId) {
        // Aqui você implementaria a lógica para exibir o estoque
        // Por exemplo, abrir um modal com as informações do estoque
        alert('Visualizar estoque do produto ' + productId);
    }
});

document.addEventListener('DOMContentLoaded', function() {
var modal = document.getElementById("addProductModal");
var btn = document.getElementById("openModalBtn");
var span = document.getElementsByClassName("close")[0];
    // Abre o modal apenas quando o botão é clicado
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Fecha o modal quando clicar no X
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Fecha o modal quando clicar fora dele
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});

// Adicione esta função no início do arquivo
function closeModal() {
    const modal = document.getElementById("addProductModal");
    if (modal) {
        modal.style.display = "none";
    }
}

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
        const isEditing = editBtn.textContent === 'Editar';

        if (isEditing) {
            // Entrar no modo de edição
            inputs.forEach(input => {
                input.readOnly = false;
                input.style.backgroundColor = '#fff3cd';
            });
            editBtn.textContent = 'Salvar';
        } else {
            // Salvar alterações
            const productId = row.dataset.id;
            const data = {
                id: productId,
                nome: row.querySelector('input[name="nome"]').value,
                preco: row.querySelector('input[name="preco"]').value
            };

            saveChanges(data, row, inputs, editBtn);
        }
    }

    function saveChanges(data, row, inputs, editBtn) {
        fetch('/ ProjetoPadaria/src/controllers/update_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationManager.success('Produto atualizado com sucesso!');
                inputs.forEach(input => {
                    input.readOnly = true;
                    input.style.backgroundColor = '';
                });
                editBtn.textContent = 'Editar';
            } else {
                NotificationManager.error(data.message || 'Erro ao atualizar produto');
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
            fetch(`/ProjetoPadaria/src/controllers/deleteProduct.php?id=${productId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
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
                NotificationManager.error('Erro ao excluir produto');
            });
        }
    }
});

    // Gerenciar produtos existentes
    const table = document.querySelector('.admin-section table');
    
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
        const isEditing = editBtn.textContent === 'Editar';

        if (isEditing) {
            // Entrar no modo de edição
            inputs.forEach(input => {
                input.readOnly = false;
                input.style.backgroundColor = '#fff3cd';
            });
            editBtn.textContent = 'Salvar';
            editBtn.classList.add('saving');
        } else {
            // Salvar alterações
            const productId = row.dataset.id;
            const data = {
                id: productId,
                nome: row.querySelector('input[name="nome"]').value,
                preco: row.querySelector('input[name="preco"]').value
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
                    input.readOnly = true;
                    input.style.backgroundColor = '';
                });
                editBtn.textContent = 'Editar';
                editBtn.classList.remove('saving');
            } else {
                throw new Error(result.message || 'Erro ao atualizar produto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            NotificationManager.error('Erro ao atualizar produto');
            inputs.forEach(input => input.readOnly = true);
            editBtn.textContent = 'Editar';
            editBtn.classList.remove('saving');
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    NotificationManager.success('Produto excluído com sucesso!');
                    row.remove();
                } else {
                    throw new Error(data.message || 'Erro ao excluir produto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                NotificationManager.error('Erro ao excluir produto');
            });
        }
    }
};