document.addEventListener("DOMContentLoaded", function() {
    // Asignar eventos a los botones de acción
    asignarEventosBotones();
});

function asignarEventosBotones() {
    document.querySelectorAll('.btn-accion').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const id = this.closest('tr').getAttribute('data-id');
            realizarAccion(action, id, this);
        });
    });
}

function realizarAccion(action, id, button) {
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
            // Actualizar el estado de la tabla
            buscarIncidencias(); // Llama a la función que actualiza la tabla
        } else {
            console.error('Error al realizar la acción.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function actualizarBotones(action, button) {
    const tdAccion = button.closest('td');
    if (action === 'empezar') {
        // Cambiar el botón "Empezar" por "Resolver" y "Enviar Mensaje"
        tdAccion.innerHTML = `
            <button class="btn-accion" data-action="resolver" style="color: green;">Resolver</button>
            <button class="btn-accion" data-action="mensaje" style="color: orange;">Enviar Mensaje</button>
        `;
    } else if (action === 'resolver') {
        // Opcional: Cambiar el estado o eliminar botones si es necesario
        tdAccion.innerHTML = `<span>Resuelta</span>`;
    }
    // Reasignar eventos a los nuevos botones
    asignarEventosBotones();
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
                    tr.innerHTML = `
                        <td>${incidencia.titulo}</td>
                        <td>${incidencia.descripcion}</td>
                        <td>${incidencia.cliente.name ?? 'N/A'}</td>
                        <td>${incidencia.prioridad.nombre ?? 'N/A'}</td>
                        <td>${incidencia.estado.nombre ?? 'N/A'}</td>
                        <td>
                            <!-- Aquí puedes agregar los botones de acción -->
                        </td>
                    `;
                    tabla.appendChild(tr);
                });
            }
        });
}
