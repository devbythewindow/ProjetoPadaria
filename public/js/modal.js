// modal.js

// Obtém o modal
var modal = document.getElementById("addProductModal");

// Obtém o botão que abre o modal
var btn = document.getElementById("openModalBtn");

// Obtém o elemento <span> que fecha o modal
var span = document.getElementsByClassName("close")[0];

// Abre o modal
btn.onclick = function() {
    modal.style.display = "block";
}

// Fecha o modal
span.onclick = function() {
    modal.style.display = "none";
}

// Fecha o modal ao clicar fora
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

                alert('Produto atualizado com sucesso!');
            } else {
                throw new Error(data.message || 'Erro ao atualizar produto');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao atualizar produto: ' + error.message);
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
});