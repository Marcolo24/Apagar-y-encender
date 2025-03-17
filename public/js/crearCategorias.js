document.addEventListener('DOMContentLoaded', function() {
    const btnCrearCategoria = document.getElementById('btnCrearCategoria');

    btnCrearCategoria.addEventListener('click', async function() {
        const { value: formValues } = await Swal.fire({
            title: 'Crear Categoría de Incidencia',
            html: `
                <div class="mb-3">
                    <label for="swal-tipo" class="form-label">Tipo:</label>
                    <select id="swal-tipo" class="form-control" required>
                        <option value="categoria">Categoría</option>
                        <option value="subcategoria">Subcategoría</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="swal-nombre" class="form-label">Nombre:</label>
                    <input id="swal-nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="mb-3" id="div-categoria" style="display: none;">
                    <label for="swal-categoria" class="form-label">Categoría padre:</label>
                    <select id="swal-categoria" class="form-control">
                        <option value="">Selecciona una categoría</option>
                    </select>
                </div>
            `,
            didOpen: async() => {
                // Cargar las categorías al abrir el modal
                try {
                    const response = await fetch('/dashboard/admin/get-categorias');
                    const data = await response.json();

                    if (data.success) {
                        const categoriaSelect = document.getElementById('swal-categoria');
                        categoriaSelect.innerHTML = '<option value="">Selecciona una categoría</option>';

                        data.categorias.forEach(categoria => {
                            categoriaSelect.innerHTML += `
                                <option value="${categoria.id}">${categoria.nombre}</option>
                            `;
                        });
                    }
                } catch (error) {
                    console.error('Error al cargar categorías:', error);
                }

                // Mostrar/ocultar selector de categoría padre
                document.getElementById('swal-tipo').addEventListener('change', function() {
                    const divCategoria = document.getElementById('div-categoria');
                    divCategoria.style.display = this.value === 'subcategoria' ? 'block' : 'none';

                    const categoriaSelect = document.getElementById('swal-categoria');
                    categoriaSelect.required = this.value === 'subcategoria';
                });
            },
            customClass: {
                popup: 'swal2-popup-custom',
                container: 'swal2-container-custom',
                content: 'swal2-content-custom'
            },
            width: '32em',
            showCancelButton: true,
            confirmButtonText: 'Crear',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const tipo = document.getElementById('swal-tipo').value;
                const nombre = document.getElementById('swal-nombre').value;
                const categoriaId = document.getElementById('swal-categoria').value;

                if (!nombre) {
                    Swal.showValidationMessage('El nombre es obligatorio');
                    return false;
                }

                if (tipo === 'subcategoria' && !categoriaId) {
                    Swal.showValidationMessage('Debe seleccionar una categoría padre');
                    return false;
                }

                return {
                    tipo,
                    nombre,
                    id_categoria: categoriaId || null
                }
            }
        });

        if (formValues) {
            try {
                const dataToSend = {
                    tipo: formValues.tipo,
                    nombre: formValues.nombre,
                    id_categoria: formValues.id_categoria
                };

                console.log('Enviando datos:', dataToSend); // Debug

                const response = await fetch('/dashboard/admin/crear-categoria', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(dataToSend)
                });

                const data = await response.json();
                console.log('Respuesta:', data); // Debug

                if (!response.ok) {
                    throw new Error(data.message || 'Error en la respuesta del servidor');
                }

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: '¡Creado!',
                        text: data.message
                    });
                    // Opcional: recargar la página o actualizar la lista de categorías
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error completo:', error); // Debug
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Hubo un problema al crear la categoría'
                });
            }
        }
    });
});