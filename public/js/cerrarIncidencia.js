document.addEventListener('DOMContentLoaded', function() {
    const botonesClose = document.querySelectorAll('.cerrar-incidencia-btn');
    
    botonesClose.forEach(boton => {
        boton.addEventListener('click', function() {
            const id = this.dataset.id;
            const estado = this.dataset.estado;
            
            if (estado !== 'Resuelta') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La incidencia debe estar resuelta para poder cerrarla'
                });
                return;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres cerrar esta incidencia?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/incidencias/cerrar/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Cerrada!',
                                'La incidencia ha sido cerrada correctamente.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                data.message || 'Ha ocurrido un error al cerrar la incidencia',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error',
                            'Ha ocurrido un error al cerrar la incidencia',
                            'error'
                        );
                    });
                }
            });
        });
    });
});
