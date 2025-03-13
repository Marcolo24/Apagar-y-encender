@extends('layout.layout')

@section('title', 'Gestor')

@section('content')
    <h1>Gestor</h1>

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
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidencias as $incidencia)
                    <tr>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->cliente->name }}</td>
                        
                        <!-- Desplegable para cambiar la prioridad -->
                        <td>
                            <form action="{{ route('gestor.updateIncidencia', $incidencia->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <select name="id_prioridad">
                                    @foreach ($prioridades as $prioridad)
                                        <option value="{{ $prioridad->id }}" 
                                            @if ($incidencia->id_prioridad == $prioridad->id) selected @endif>
                                            {{ $prioridad->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                        </td>

                        <!-- Desplegable para cambiar el estado -->
                        <td>
                            <select name="id_estado">
                                @foreach ($estados as $estado)
                                    <option value="{{ $estado->id }}" 
                                        @if ($incidencia->id_estado == $estado->id) selected @endif>
                                        {{ $estado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <!-- Botón de actualizar -->
                        <td>
                            <button type="submit">Actualizar</button>
                        </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
