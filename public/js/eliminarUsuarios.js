// al clicar el boton, saldra un swal y ya esta
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(event) {
        const eliminarUsuario = event.target.closest('#eliminarUsuario');
        if (eliminarUsuario) {
            event.preventDefault();
            const userId = eliminarUsuario.dataset.id;

            Swal.fire({
                title: '¿Estás seguro?',
                text: 'No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/dashboard/admin/eliminar-usuario/${userId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Eliminado!',
                                    'El usuario ha sido eliminado.',
                                    'success'
                                ).then(() => {
                                    // Recargar la tabla de usuarios
                                    buscarUsuarios();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'No se pudo eliminar el usuario.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                'Hubo un problema al eliminar el usuario.',
                                'error'
                            );
                        });
                }
            });
        }
    });
});