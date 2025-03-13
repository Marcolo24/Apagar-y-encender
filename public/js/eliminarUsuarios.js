// al clicar el boton, saldra un swal y ya esta
document.addEventListener('DOMContentLoaded', function() {
    const eliminarUsuario = document.getElementById('eliminarUsuario');
    if (eliminarUsuario) {
        eliminarUsuario.addEventListener('click', function(event) {
            event.preventDefault();

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
                    window.location.href = eliminarUsuario.href;
                }
            });
        });
    }
});
