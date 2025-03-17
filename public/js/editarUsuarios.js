document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', async function(event) {
        const editarUsuario = event.target.closest('.btn-warning');
        if (editarUsuario) {
            event.preventDefault();
            const userId = editarUsuario.dataset.id;

            // Obtener los datos del usuario de la fila actual
            const fila = editarUsuario.closest('tr');
            const nombre = fila.children[1].textContent;
            const apellidos = fila.children[2].textContent;
            const email = fila.children[3].textContent;
            const sede = fila.children[4].textContent;
            const rol = fila.children[5].textContent;

            const { value: formValues } = await Swal.fire({
                title: 'Editar Usuario',
                html: `
                    <div class="mb-3">
                        <label for="swal-name" class="form-label">Nombre:</label>
                        <input id="swal-name" class="form-control" placeholder="Nombre" value="${nombre}" required>
                    </div>
                    <div class="mb-3">
                        <label for="swal-apellidos" class="form-label">Apellidos:</label>
                        <input id="swal-apellidos" class="form-control" placeholder="Apellidos" value="${apellidos}" required>
                    </div>
                    <div class="mb-3">
                        <label for="swal-email" class="form-label">Email:</label>
                        <input id="swal-email" class="form-control" placeholder="Email" value="${email}" required>
                    </div>
                    <div class="mb-3">
                        <label for="swal-sede" class="form-label">Sede:</label>
                        <select id="swal-sede" class="form-control" required>
                            <option value="">Selecciona una sede</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="swal-rol" class="form-label">Rol:</label>
                        <select id="swal-rol" class="form-control" required>
                            <option value="">Selecciona un rol</option>
                        </select>
                    </div>
                `,
                customClass: {
                    popup: 'swal2-popup-custom',
                    container: 'swal2-container-custom',
                    content: 'swal2-content-custom'
                },
                width: '32em',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                didOpen: () => {
                    // Cargar las sedes y roles mediante una petición AJAX
                    fetch('/dashboard/admin/get-sedes-roles')
                        .then(response => response.json())
                        .then(data => {
                            const sedeSelect = document.getElementById('swal-sede');
                            const rolSelect = document.getElementById('swal-rol');

                            // Llenar select de sedes
                            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                            data.sedes.forEach(s => {
                                sedeSelect.innerHTML += `
                                    <option value="${s.id}" ${s.nombre === sede ? 'selected' : ''}>
                                        ${s.nombre}
                                    </option>`;
                            });

                            // Llenar select de roles
                            rolSelect.innerHTML = '<option value="">Selecciona un rol</option>';
                            data.roles.forEach(r => {
                                rolSelect.innerHTML += `
                                    <option value="${r.id}" ${r.nombre === rol ? 'selected' : ''}>
                                        ${r.nombre}
                                    </option>`;
                            });
                        });
                },
                preConfirm: () => {
                    const name = document.getElementById('swal-name').value;
                    const apellidos = document.getElementById('swal-apellidos').value;
                    const email = document.getElementById('swal-email').value;
                    const sede = document.getElementById('swal-sede').value;
                    const rol = document.getElementById('swal-rol').value;

                    // Validación
                    if (!name || !apellidos || !email || !sede || !rol) {
                        Swal.showValidationMessage('Todos los campos son obligatorios');
                        return false;
                    }

                    return {
                        name,
                        apellidos,
                        email,
                        sede,
                        rol
                    }
                }
            });

            if (formValues) {
                try {
                    const response = await fetch(`/dashboard/admin/editar-usuario/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(formValues)
                    });

                    const data = await response.json();

                    if (data.success) {
                        await Swal.fire(
                            '¡Actualizado!',
                            'El usuario ha sido actualizado correctamente.',
                            'success'
                        );
                        // Recargar la tabla de usuarios
                        buscarUsuarios();
                    } else {
                        throw new Error(data.message || 'Error al actualizar el usuario');
                    }
                } catch (error) {
                    Swal.fire(
                        'Error!',
                        error.message || 'Hubo un problema al actualizar el usuario.',
                        'error'
                    );
                }
            }
        }
    });
});