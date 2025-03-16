@extends('layout.layout')

@section('title', 'Gestor')

@section('content')
    <br>
    <h1>Incidencias Asignadas a Técnicos</h1>
    
    <!-- Botón para ver incidencias asignadas a técnicos -->
    <a href="{{ route('gestor.verIncidenciasTecnico') }}" class="btn btn-primary">Ver Incidencias por Técnico</a>
    <br>
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
                </tr>
            </thead>
            <tbody>
                @foreach ($incidencias as $incidencia)
                    <tr>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->cliente->name }}</td>
                        
                        <!-- Desplegable para cambiar la prioridad (se envía automáticamente) -->
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
                        </td>

                        <!-- Desplegable para cambiar el estado (se envía automáticamente) -->
                        <td>
                            <select name="id_estado" onchange="this.form.submit()">
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}" 
                                        @if ($incidencia->id_estado == $estado->id) selected @endif>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
