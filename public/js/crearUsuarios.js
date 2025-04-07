document.addEventListener('DOMContentLoaded', function() {
    const btnCrearUsuario = document.getElementById('btnCrearUsuario');

    btnCrearUsuario.addEventListener('click', async function() {
        const { value: formValues } = await Swal.fire({
            title: 'Crear Nuevo Usuario',
            html: `
                <div class="mb-3">
                    <label for="swal-name" class="form-label">Nombre:</label>
                    <input id="swal-name" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="mb-3">
                    <label for="swal-apellidos" class="form-label">Apellidos:</label>
                    <input id="swal-apellidos" class="form-control" placeholder="Apellidos" required>
                </div>
                <div class="mb-3">
                    <label for="swal-email" class="form-label">Email:</label>
                    <input id="swal-email" class="form-control" placeholder="ejemplo@gmail.com" required>
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
                <div class="mb-3">
                    <label for="swal-password" class="form-label">Contraseña:</label>
                    <input id="swal-password" type="password" class="form-control" placeholder="Contraseña" required>
                </div>
            `,
            customClass: {
                popup: 'swal2-popup-custom',
                container: 'swal2-container-custom',
                content: 'swal2-content-custom'
            },
            width: '32em',
            showCancelButton: true,
            confirmButtonText: 'Crear',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                // Cargar las sedes y roles mediante una petición AJAX
                fetch('/dashboard/admin/get-sedes-roles')
                    .then(response => response.json())
                    .then(data => {
                        const sedeSelect = document.getElementById('swal-sede');
                        const rolSelect = document.getElementById('swal-rol');

                        // Llenar select de sedes
                        data.sedes.forEach(s => {
                            sedeSelect.innerHTML += `
                                <option value="${s.id}">${s.nombre}</option>`;
                        });

                        // Llenar select de roles
                        data.roles.forEach(r => {
                            rolSelect.innerHTML += `
                                <option value="${r.id}">${r.nombre}</option>`;
                        });
                    });
            },
            preConfirm: () => {
                const name = document.getElementById('swal-name').value;
                const apellidos = document.getElementById('swal-apellidos').value;
                const email = document.getElementById('swal-email').value;
                const sede = document.getElementById('swal-sede').value;
                const rol = document.getElementById('swal-rol').value;
                const password = document.getElementById('swal-password').value;

                // Validación de campos vacíos
                if (!name || !apellidos || !email || !sede || !rol || !password) {
                    Swal.showValidationMessage('Todos los campos son obligatorios');
                    return false;
                }

                // Validación del formato de correo electrónico
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailRegex.test(email)) {
                    Swal.showValidationMessage('El correo debe tener un formato válido (ejemplo@dominio.com)');
                    return false;
                }

                // Validación de contraseña (mínimo 6 caracteres)
                if (password.length < 6) {
                    Swal.showValidationMessage('La contraseña debe tener al menos 6 caracteres');
                    return false;
                }

                return {
                    name,
                    apellidos,
                    email,
                    sede,
                    rol,
                    password
                }
            }
        });

        if (formValues) {
            try {
                const response = await fetch('/dashboard/admin/crear-usuario', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(formValues)
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire(
                        '¡Creado!',
                        'El usuario ha sido creado correctamente.',
                        'success'
                    );
                    // Recargar la tabla de usuarios
                    buscarUsuarios();
                } else {
                    throw new Error(data.message || 'Error al crear el usuario');
                }
            } catch (error) {
                Swal.fire(
                    'Error!',
                    error.message || 'Hubo un problema al crear el usuario.',
                    'error'
                );
            }
        }
    });
});