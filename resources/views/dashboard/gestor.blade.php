@extends('layout.layout')

@section('title', 'Gestor')

@section('content')
    <br>
    <h1>Todas las incidencias</h1>

    <br>

    <!-- Filtro por prioridad, técnico y fecha -->
    <form method="GET" action="{{ route('dashboard.gestor') }}" style="display: flex; align-items: center; gap: 10px;">
        <div>
            <label for="filtro_prioridad"><strong>Filtrar por prioridad:</strong></label>
            <select name="filtro_prioridad" id="filtro_prioridad" onchange="this.form.submit()">
                <option value="">Todas</option>
                @foreach ($prioridades as $prioridad)
                    <option value="{{ $prioridad->id }}" 
                        {{ request('filtro_prioridad') == $prioridad->id ? 'selected' : '' }}>
                        {{ $prioridad->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="filtro_tecnico"><strong>Filtrar por técnico:</strong></label>
            <select name="filtro_tecnico" id="filtro_tecnico" onchange="this.form.submit()">
                <option value="">Todos</option>
                @foreach ($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}" 
                        {{ request('filtro_tecnico') == $tecnico->id ? 'selected' : '' }}>
                        {{ $tecnico->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="filtro_fecha"><strong>Ordenar por fecha:</strong></label>
            <select name="filtro_fecha" id="filtro_fecha" onchange="this.form.submit()">
                <option value="asc" {{ request('filtro_fecha') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                <option value="desc" {{ request('filtro_fecha') == 'desc' ? 'selected' : '' }}>Descendente</option>
            </select>
        </div>
    </form>

    <br>

    @if($incidencias->isEmpty())
        <p>No hay incidencias registradas.</p>
    @else
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Incidencia</th>
                <th>Descripción</th>
                <th>Cliente afectado</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Técnico Asignado</th>
                <th>Fecha de Inicio</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->descripcion }}</td>
                    <td>{{ $incidencia->cliente->name }}</td>
                    
                    <td>
                    <form action="{{ route('gestor.updateIncidencia', $incidencia->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="id_prioridad" onchange="this.form.submit()">
                            @foreach ($prioridades as $prioridad)
                                <option value="{{ $prioridad->id }}" 
                                    @if ($incidencia->id_prioridad == $prioridad->id) selected @endif>
                                    {{ $prioridad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    </td>
    
                    <td>
                        <form action="{{ route('gestor.updateIncidencia', $incidencia->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="id_estado" onchange="this.form.submit()">
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}" 
                                        @if ($incidencia->id_estado == $estado->id) selected @endif>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>
    
                    <td>
                        <form action="{{ route('incidencias.updateTecnico', $incidencia->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="id_tecnico" onchange="this.form.submit()">
                                <option value="">No asignado</option>
                                @foreach ($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}" 
                                        {{ $incidencia->id_tecnico == $tecnico->id ? 'selected' : '' }}>
                                        {{ $tecnico->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </td>

                    <td>{{ $incidencia->fecha_inicio }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection
