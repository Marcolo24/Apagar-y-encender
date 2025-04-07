@extends('layout.layout')

@section('title', 'Técnico')

@section('content')
<br>
    <!-- Filtros y Botones de Acción -->
    <div id="divFiltrosUsuario" class="d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3">
            <div>
                <input type="text" name="name" class="question" id="inputNombre" required autocomplete="off" placeholder="Buscar incidencia"/>
                <label for="buscar"><span>Buscar incidencia</span></label>
            </div>
            <div class="divFiltroSede">
                <select class="question" name="prioridad" id="inputPrioridad">
                    <option value="" selected>Prioridad</option>
                    @foreach($prioridades as $prioridad)
                        <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="divFiltroSede">
                <select class="question" name="estado" id="inputEstado">
                    <option value="" selected>Estado</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-secondary" id="btnReiniciarFiltros">
                <i class="bi bi-arrow-counterclockwise"></i> Reiniciar filtros
            </button>
        </div>
    </div>

    <br>
    <!-- Tabla de Incidencias -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Incidencias</th>
                <th>Descripción</th>
                <th>Cliente Afectado</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tabla-incidencias">
            @foreach($incidencias as $incidencia)
                <tr data-id="{{ $incidencia->id }}">
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->descripcion }}</td>
                    <td>{{ $incidencia->cliente->name ?? 'N/A' }}</td>
                    <td>{{ $incidencia->prioridad->nombre ?? 'N/A' }}</td>
                    <td>{{ $incidencia->estado->nombre ?? 'N/A' }}</td>
                    <td>
                        @if($incidencia->estado->nombre == 'Asignada')
                            <button class="btn-accion" data-action="empezar" style="color: blue;">Empezar</button>
                        @elseif($incidencia->estado->nombre == 'En trabajo')
                            <button class="btn-accion" data-action="resolver" style="color: green;">Resolver</button>
                        @elseif($incidencia->estado->nombre == 'Resuelta')
                            <p>Resuelta</p>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="{{ asset('../js/filtrotecnico.js') }}"></script>
    <script src="{{ asset('../js/btn-ajax.js') }}"></script>
@endsection