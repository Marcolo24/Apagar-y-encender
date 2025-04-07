@extends('layout.layout')

@section('title', 'Cliente')

@section('content')
<br>
        <div class="row mb-4 text-center">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#registrarIncidenciaModal">
                    Registrar Incidencia
                </button>
            </div>
        </div>

        <div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm p-3">
            <div class="card-body">
                <form id="filtrosForm" method="GET" class="row g-3 align-items-center">
                    <div class="col-lg-4 col-md-6">
                        <label for="estado" class="form-label fw-bold">Estado</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="">Todos los estados</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->nombre }}" {{ request('estado') == $estado->nombre ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-lg-4 col-md-6">
                        <label for="orden" class="form-label fw-bold">Ordenar por Fecha</label>
                        <select name="orden" id="orden" class="form-select">
                            <option value="desc" {{ request('orden') == 'desc' ? 'selected' : '' }}>Más recientes primero</option>
                            <option value="asc" {{ request('orden') == 'asc' ? 'selected' : '' }}>Más antiguos primero</option>
                        </select>
                    </div>

                    <!-- Ocultar Resueltas + Botón Limpiar juntos -->
                    <div class="col-lg-4 col-md-6 d-flex align-items-center justify-content-between">
                        <div class="form-check form-switch d-flex align-items-center">
                            <input class="form-check-input me-2" type="checkbox" id="ocultar_resueltas" name="ocultar_resueltas" value="1"
                                {{ request('ocultar_resueltas') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="ocultar_resueltas">
                                Ocultar resueltas y cerradas
                            </label>
                        </div>
                        <button type="button" id="limpiarFiltros" class="btn btn-outline-secondary ms-3">Limpiar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



        <!-- Modal para Registrar Incidencia -->
        <div class="modal fade" id="registrarIncidenciaModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Registrar Nueva Incidencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="incidenciaForm" action="{{ route('incidencias.crear') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="img" class="form-label">Imagen (opcional)</label>
                                <input type="file" name="img" id="img" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="id_subcategoria" class="form-label">Subcategoría</label>
                                <select name="id_subcategoria" id="id_subcategoria" class="form-control" required>
                                    <option value="" disabled selected>Selecciona Subcategoría</option>
                                    @foreach(\App\Models\Subcategoria::with('categoria')->get() as $subcategoria)
                                        <option value="{{ $subcategoria->id }}" data-categoria="{{ $subcategoria->categoria->nombre }}">
                                            {{ $subcategoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría</label>
                                <input type="text" id="categoria" class="form-control" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="id_prioridad" class="form-label">Prioridad</label>
                                <select name="id_prioridad" id="id_prioridad" class="form-control" required>
                                    <option value="" disabled selected>Selecciona Prioridad</option>
                                    @foreach(\App\Models\Prioridad::all() as $prioridad)
                                        <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <br><br>

        <!-- Tabla de incidencias -->
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Prioridad</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Final</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="incidencias-tbody">
                @foreach ($incidencias as $incidencia)
                    <tr class="fila-incidencia" data-href="{{ route('incidencias.detalle', $incidencia->id) }}" style="cursor: pointer;">
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->prioridad->nombre }}</td>
                        <td>{{ $incidencia->fecha_inicio }}</td>
                        <td>{{ $incidencia->fecha_final ?? '--' }}</td>
                        <td>{{ $incidencia->estado->nombre }}</td>
                        <td onclick="event.stopPropagation();">  <!-- Evita que el click en el botón redirija -->
                            <button 
                                class="btn btn-primary btn-sm cerrar-incidencia-btn {{ $incidencia->estado->nombre !== 'Resuelta' ? 'disabled' : '' }}"
                                data-id="{{ $incidencia->id }}"
                                data-estado="{{ $incidencia->estado->nombre }}"
                            >
                                Cerrar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/registrarIncidencia.js') }}"></script>
    <script src="{{ asset('js/cerrarIncidencia.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hacer las filas clicables
        document.querySelectorAll('.fila-incidencia').forEach(fila => {
            fila.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });

            // Efecto hover
            fila.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });

            fila.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
    </script>
@endsection

<style>
.fila-incidencia {
    transition: background-color 0.2s ease;
}

.fila-incidencia:hover {
    background-color: #f8f9fa;
}

/* Evitar que el texto sea seleccionable al hacer click */
.fila-incidencia td {
    user-select: none;
}
</style>
