document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.btn-accion').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const id = this.closest('tr').getAttribute('data-id');
            realizarAccion(action, id);
        });
    });
});

function realizarAccion(action, id) {
    fetch(`/incidencias/${action}/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            buscarIncidencias();
        } else {
            alert('Error al realizar la acción.');
        }
    })
    .catch(error => console.error('Error:', error));
}
