document.addEventListener("DOMContentLoaded", function() {
    // Asignar eventos a los botones de acción
    asignarEventosBotones();
});

function asignarEventosBotones() {
    document.querySelectorAll('.btn-accion').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const id = this.closest('tr').getAttribute('data-id');
            realizarAccion(action, id);
        });
    });
}

function realizarAccion(action, id) {
    let method = 'GET';
    let url = `/incidencias/${action}/${id}`;

    if (action === 'resolver') {
        method = 'POST';
    }

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(url, {
        method: method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
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
            // Actualizar toda la tabla después de una acción exitosa
            buscarIncidencias();
        } else {
            console.error('Error al realizar la acción.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function buscarIncidencias() {
    let nombre = document.getElementById('inputNombre').value;
    let estado = document.getElementById('inputEstado').value;
    let prioridad = document.getElementById('inputPrioridad').value;

    let url = `/dashboard/tecnico/buscar-incidencias?nombre=${nombre}&estado=${estado}&prioridad=${prioridad}&orderBy=${currentOrderBy}&orderDirection=${currentOrderDirection}`;
    let tabla = document.getElementById('tabla-incidencias');
    let thead = tabla.parentElement.querySelector('thead');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            tabla.innerHTML = "";
            if (data.length === 0) {
                thead.style.display = 'none';
                let p = document.createElement('p');
                p.textContent = "No se encontraron resultados.";
                tabla.appendChild(p);
            } else {
                if (thead) {
                    thead.style.display = '';
                }
                data.forEach(incidencia => {
                    let tr = document.createElement('tr');
                    tr.setAttribute('data-id', incidencia.id);
                    tr.innerHTML = `
                        <td>${incidencia.titulo}</td>
                        <td>${incidencia.descripcion}</td>
                        <td>${incidencia.cliente.name ?? 'N/A'}</td>
                        <td>${incidencia.prioridad.nombre ?? 'N/A'}</td>
                        <td>${incidencia.estado.nombre ?? 'N/A'}</td>
                        <td>
                            ${incidencia.estado.nombre === 'Asignada' ? 
                                `<button class="btn-accion" data-action="empezar" style="color: blue;">Empezar</button>` : 
                                incidencia.estado.nombre === 'En trabajo' ? 
                                `<button class="btn-accion" data-action="resolver" style="color: green;">Resolver</button>` : 
                                incidencia.estado.nombre === 'Resuelta' ? 
                                `<p>Resuelta</p>` : 
                                ''}
                        </td>
                    `;
                    tabla.appendChild(tr);
                });
                // Reasignar eventos a los nuevos botones
                asignarEventosBotones();
            }
        });
}
