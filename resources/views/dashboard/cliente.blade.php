@extends('layout.layout')

@section('title', 'Cliente')

@section('content')
<br>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrarIncidenciaModal">
        Registrar Incidencia
    </button>

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
                            <label for="id_subcategoria" class="form-label">Categoría</label>
                            <select name="id_subcategoria" id="id_subcategoria" class="form-control" required>
                                <option value="" disabled selected>Selecciona Categoría</option>
                                @foreach(\App\Models\Subcategoria::all() as $subcategoria)
                                    <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                                @endforeach
                            </select>
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
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                <th>Estado</th>
                <th>Cerrar Incidencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->descripcion }}</td>
                    <td>{{ $incidencia->prioridad->nombre }}</td>
                    <td>{{ $incidencia->fecha_inicio }}</td>
                    <td>{{ $incidencia->fecha_final ?? '--' }}</td>
                    <td>{{ $incidencia->estado->nombre }}</td>
                    <td>
                        <button 
                            class="btn btn-primary cerrar-incidencia-btn {{ $incidencia->estado->nombre !== 'Resuelta' ? 'disabled' : '' }}"
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
@endsection

@section('scripts')
    <script src="{{ asset('js/registrarIncidencia.js') }}"></script>
    <script src="{{ asset('js/cerrarIncidencia.js') }}"></script>
@endsection
