@extends('layout.layout')

@section('title', 'Gestor')

@section('content')
    <br>

    @if($incidencias->isEmpty())
        <p>No hay incidencias registradas.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>ID Cliente</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidencias as $incidencia)
                    <tr>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->id_cliente }}</td> 
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
