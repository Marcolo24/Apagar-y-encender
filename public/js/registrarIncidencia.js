document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('incidenciaForm');
    const modal = document.getElementById('registrarIncidenciaModal');
    const bootstrapModal = new bootstrap.Modal(modal);
    const filtrosForm = document.getElementById('filtrosForm');
    const estadoSelect = document.getElementById('estado');
    const ordenSelect = document.getElementById('orden');
    const ocultarResueltasCheckbox = document.getElementById('ocultar_resueltas');

    const tituloInput = document.getElementById('titulo');
    const descripcionInput = document.getElementById('descripcion');
    const subcategoriaSelect = document.getElementById('id_subcategoria');
    const categoriaInput = document.getElementById('categoria');

    const limpiarFiltrosBtn = document.getElementById('limpiarFiltros');

    function activarFilasIncidencia() {
        document.querySelectorAll('.fila-incidencia').forEach(fila => {
            fila.addEventListener('click', function () {
                window.location.href = this.dataset.href;
            });
    
            fila.addEventListener('mouseenter', function () {
                this.style.backgroundColor = '#f8f9fa';
            });
    
            fila.addEventListener('mouseleave', function () {
                this.style.backgroundColor = '';
            });
        });
    }
    

    tituloInput.addEventListener('blur', function () {
        const titulo = tituloInput.value.trim();
    
        const regex = /^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,()¡!¿?"'-]+$/;
    
        if (!titulo) {
            mostrarError(tituloInput, 'El título es obligatorio');
        } else if (!regex.test(titulo)) {
            mostrarError(tituloInput, 'El título contiene caracteres no permitidos');
        } else {
            limpiarError(tituloInput);
        }
    });    

    subcategoriaSelect.addEventListener('change', function() {
        const selectedOption = subcategoriaSelect.options[subcategoriaSelect.selectedIndex];
        const categoria = selectedOption.getAttribute('data-categoria');
        categoriaInput.value = categoria || '';
    });

    const mostrarError = (elemento, mensaje) => {
        let errorSpan = elemento.nextElementSibling;
        if (!errorSpan || !errorSpan.classList.contains('error-text')) {
            errorSpan = document.createElement('span');
            errorSpan.classList.add('error-text');
            errorSpan.style.color = 'red';
            errorSpan.style.fontSize = '0.9em';
            errorSpan.style.display = 'block';
            elemento.parentNode.appendChild(errorSpan);
        }
        errorSpan.textContent = mensaje;
    };

    const limpiarError = (elemento) => {
        let errorSpan = elemento.nextElementSibling;
        if (errorSpan && errorSpan.classList.contains('error-text')) {
            errorSpan.textContent = '';
        }
    };

    descripcionInput.addEventListener('blur', function() {
        const descripcion = descripcionInput.value.trim();
        if (!descripcion) {
            mostrarError(descripcionInput, 'La descripción es obligatoria');
        } else if (descripcion.length < 10) {
            mostrarError(descripcionInput, 'La descripción debe tener al menos 10 caracteres');
        } else {
            limpiarError(descripcionInput);
        }
    });

    // Función para añadir event listeners a una fila
    function addRowEventListeners(row) {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });

        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });

        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    }

    // Añadir event listeners a las filas existentes
    /*document.querySelectorAll('.fila-incidencia').forEach(fila => {
        addRowEventListeners(fila);
    });*/
    activarFilasIncidencia();


    // Manejo del formulario de incidencia
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success !== false) {
                // Crear nueva fila para la tabla
                const newRow = document.createElement('tr');
                newRow.className = 'fila-incidencia';
                newRow.dataset.href = `/incidencias/${data.incidencia.id}/detalle`;
                newRow.style.cursor = 'pointer';
                
                newRow.dataset.href = `/incidencias/${data.incidencia.id}/detalle`;

                newRow.innerHTML = `
                    <td>${data.incidencia.titulo}</td>
                    <td>${data.incidencia.descripcion}</td>
                    <td>${data.incidencia.prioridad.nombre}</td>
                    <td>${data.incidencia.fecha_inicio}</td>
                    <td>--</td>
                    <td>${data.incidencia.estado.nombre}</td>
                    <td onclick="event.stopPropagation();">
                        <button 
                            class="btn btn-primary btn-sm cerrar-incidencia-btn disabled"
                            data-id="${data.incidencia.id}"
                            data-estado="${data.incidencia.estado.nombre}"
                        >
                            Cerrar
                        </button>
                    </td>
                `;                

                // Añadir event listeners a la nueva fila
                addRowEventListeners(newRow);

                // Añadir la nueva fila al principio de la tabla
                document.querySelector('table tbody').prepend(newRow);

                // Limpiar el formulario y cerrar el modal
                form.reset();
                bootstrapModal.hide();

                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Incidencia creada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ha ocurrido un error al crear la incidencia'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ha ocurrido un error al crear la incidencia'
            });
        });
    });

    // Función para limpiar filtros
    const limpiarFiltros = (e) => {
        e.preventDefault();
        
        // Resetear valores de los filtros
        estadoSelect.value = '';
        ordenSelect.value = 'desc';
        ocultarResueltasCheckbox.checked = false;

        // Realizar petición AJAX con filtros limpios
        fetch('/dashboard/cliente/filtrar', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('incidencias-tbody').innerHTML = html;
            
            activarFilasIncidencia();

            // Actualizar la URL sin recargar la página
            const newUrl = window.location.pathname;
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    // Añadir event listener para el botón de limpiar
    if (limpiarFiltrosBtn) {
        limpiarFiltrosBtn.addEventListener('click', limpiarFiltros);
    }

    const applyFilters = () => {
        const params = new URLSearchParams(new FormData(filtrosForm)).toString();
        const url = `/dashboard/cliente/filtrar?${params}`;

        console.log('URL:', url);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('incidencias-tbody').innerHTML = html;
            
            activarFilasIncidencia();

            // Actualizar la URL sin recargar la página
            const newUrl = `${window.location.pathname}?${params}`;
            window.history.pushState({}, '', newUrl);
        })
        .catch(error => console.error('Error:', error));
    };

    estadoSelect.addEventListener('change', applyFilters);
    ordenSelect.addEventListener('change', applyFilters);
    ocultarResueltasCheckbox.addEventListener('change', applyFilters);
});