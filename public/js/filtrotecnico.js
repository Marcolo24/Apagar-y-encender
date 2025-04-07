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

// Asignar eventos al cargar la página
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

    // Asignar eventos a los botones de acción
    asignarEventosBotones();
});