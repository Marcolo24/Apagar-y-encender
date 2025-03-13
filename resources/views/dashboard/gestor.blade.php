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
                </tr>
            </thead>
            <tbody>
                @foreach ($incidencias as $incidencia)
                    <tr>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->cliente->name }}</td> 
                        <td>{{ $incidencia->prioridad->nombre }}</td>
                        <td>
                            @if ($incidencia->id_estado == 1)
                                Abierto
                            @elseif ($incidencia->id_estado == 2)
                                En progreso
                            @elseif ($incidencia->id_estado == 3)
                                Cerrado
                            @else
                                Desconocido
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
