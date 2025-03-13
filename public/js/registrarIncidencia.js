$(document).ready(function () {
    $("#incidenciaForm").submit(function (event) {
        event.preventDefault();

        $.ajax({
            url: "/incidencias/crear", // Ruta donde se enviará el formulario
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Éxito",
                    text: response.message,
                });

                $("#registrarIncidenciaModal").modal("hide"); // Cerrar el modal
                $("#incidenciaForm")[0].reset(); // Resetear el formulario

                // Agregar la nueva incidencia a la tabla
                let newRow = `
                    <tr>
                        <td>${response.incidencia.id}</td>
                        <td>${response.incidencia.titulo}</td>
                        <td>${response.incidencia.descripcion}</td>
                        <td>${response.incidencia.prioridad.nombre}</td>
                        <td>${response.incidencia.estado.nombre}</td>
                        <td>${response.incidencia.fecha_inicio}</td>
                        <td>-</td>
                    </tr>`;
                $("#incidenciasTable").prepend(newRow);
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: xhr.responseJSON.message,
                });
            }
        });
    });
});