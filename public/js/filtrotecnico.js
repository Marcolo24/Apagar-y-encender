// Variables para el ordenamiento
let currentOrderBy = 'id';
let currentOrderDirection = 'asc';

// Función para actualizar las flechas de ordenamiento
function actualizarFlechas(columna) {
    document.querySelectorAll('th .d-flex p:last-child').forEach(arrow => {
        arrow.textContent = '↑↓';
    });

    const th = document.querySelector(`th[onclick="ordenarTabla('${columna}')"]`);
    if (th) {
        const arrow = th.querySelector('.d-flex p:last-child');
        if (arrow) {
            arrow.textContent = currentOrderDirection === 'asc' ? '↑' : '↓';
        }
    }
}

// Función para ordenar la tabla
function ordenarTabla(columna) {
    if (currentOrderBy === columna) {
        currentOrderDirection = currentOrderDirection === 'asc' ? 'desc' : 'asc';
    } else {
        currentOrderBy = columna;
        currentOrderDirection = 'asc';
    }

    actualizarFlechas(columna);
    buscarIncidencias();
}

// Función para buscar incidencias
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

// Función para reiniciar los filtros
function reiniciarFiltros() {
    document.getElementById('inputNombre').value = '';
    document.getElementById('inputEstado').value = '';
    document.getElementById('inputPrioridad').value = '';

    currentOrderBy = 'id';
    currentOrderDirection = 'asc';

    document.querySelectorAll('th .d-flex p:last-child').forEach(arrow => {
        arrow.textContent = '↑↓';
    });

    buscarIncidencias();
}

// Ejemplo para filtrarUsuarios.js
document.addEventListener("DOMContentLoaded", function() {
    const inputNombre = document.getElementById("inputNombre");
    const inputEstado = document.getElementById("inputEstado");
    const inputPrioridad = document.getElementById("inputPrioridad");
    const btnReiniciarFiltros = document.getElementById("btnReiniciarFiltros");

    if (inputNombre) {
        inputNombre.addEventListener("input", buscarIncidencias);
    }
    if (inputEstado) {
        inputEstado.addEventListener("change", buscarIncidencias);
    }
    if (inputPrioridad) {
        inputPrioridad.addEventListener("change", buscarIncidencias);
    }
    if (btnReiniciarFiltros) {
        btnReiniciarFiltros.addEventListener("click", reiniciarFiltros);
    }
});
