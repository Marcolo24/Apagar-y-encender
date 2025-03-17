// funcion fetch para filtrar usuarios dentro del body de la tabla

let currentOrderBy = 'id';
let currentOrderDirection = 'asc';

function actualizarFlechas(columna) {
    // Reset all arrows
    document.querySelectorAll('th .d-flex p:last-child').forEach(arrow => {
        arrow.textContent = '↑↓';
    });

    // Set arrow for current column
    const th = document.querySelector(`th[onclick="ordenarTabla('${columna}')"]`);
    if (th) {
        const arrow = th.querySelector('.d-flex p:last-child');
        if (arrow) {
            arrow.textContent = currentOrderDirection === 'asc' ? '↑' : '↓';
        }
    }
}

function ordenarTabla(columna) {
    if (currentOrderBy === columna) {
        // Si se hace clic en la misma columna, cambiar la dirección
        currentOrderDirection = currentOrderDirection === 'asc' ? 'desc' : 'asc';
    } else {
        // Si se hace clic en una nueva columna, establecer orden ascendente
        currentOrderBy = columna;
        currentOrderDirection = 'asc';
    }

    actualizarFlechas(columna);
    buscarUsuarios();
}

function buscarUsuarios() {
    let nombre = document.getElementById('inputNombre').value;
    let email = document.getElementById('inputEmail').value;
    let sede = document.getElementById('inputSede').value;

    // Siempre incluir los parámetros de ordenamiento
    let url = `/dashboard/admin/buscar-usuarios?nombre=${nombre}&email=${email}&sede=${sede}&orderBy=${currentOrderBy}&orderDirection=${currentOrderDirection}`;
    let tabla = document.getElementById('tabla-usuarios');
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
                data.forEach(usuario => {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${usuario.id}</td>
                        <td>${usuario.name}</td>
                        <td>${usuario.apellidos}</td>
                        <td>${usuario.correo}</td>
                        <td>${usuario.sede.nombre}</td>
                        <td>${usuario.rol.nombre}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm rounded-3 me-2" data-id="${usuario.id}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm rounded-3" id="eliminarUsuario" data-id="${usuario.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    `;
                    tabla.appendChild(tr);
                });
            }
        });
}

function reiniciarFiltros() {
    // Reiniciar los campos de filtro
    document.getElementById('inputNombre').value = '';
    document.getElementById('inputEmail').value = '';
    document.getElementById('inputSede').value = '';

    // Reiniciar el ordenamiento
    currentOrderBy = 'id';
    currentOrderDirection = 'asc';

    // Reiniciar las flechas de ordenamiento
    document.querySelectorAll('th .d-flex p:last-child').forEach(arrow => {
        arrow.textContent = '↑↓';
    });

    // Actualizar la tabla
    buscarUsuarios();
}

// Agregar los event listeners
document.getElementById("inputNombre").addEventListener("input", buscarUsuarios);
document.getElementById("inputEmail").addEventListener("input", buscarUsuarios);
document.getElementById("inputSede").addEventListener("change", buscarUsuarios);
document.getElementById("btnReiniciarFiltros").addEventListener("click", reiniciarFiltros);