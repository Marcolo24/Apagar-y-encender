document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('incidenciaForm');
    const modal = document.getElementById('registrarIncidenciaModal');
    const bootstrapModal = new bootstrap.Modal(modal);

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
                newRow.innerHTML = `
                    <td>${data.incidencia.titulo}</td>
                    <td>${data.incidencia.descripcion}</td>
                    <td>${data.incidencia.prioridad.nombre}</td>
                    <td>${data.incidencia.fecha_inicio}</td>
                    <td>--</td>
                    <td>${data.incidencia.estado.nombre}</td>
                    <td>
                        <button 
                            class="btn btn-primary cerrar-incidencia-btn disabled"
                            data-id="${data.incidencia.id}"
                            data-estado="${data.incidencia.estado.nombre}"
                        >
                            Cerrar
                        </button>
                    </td>
                `;

                // Añadir la nueva fila a la tabla
                document.querySelector('table tbody').prepend(newRow);

                // Limpiar el formulario
                form.reset();

                // Cerrar el modal
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
});