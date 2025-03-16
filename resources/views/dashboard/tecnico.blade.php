@extends('layout.layout')

@section('title', 'Técnico')

@section('content')
<br>
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
        <tbody>
            @foreach($incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->descripcion }}</td>
                    <td>{{ $incidencia->cliente->name }} {{ $incidencia->cliente->apellidos }}</td>
                    <td>{{ $incidencia->prioridad->nombre }}</td>
                    <td>{{ $incidencia->estado->nombre }}</td>
                    <td>
                        @if($incidencia->estado->nombre == 'Asignada')
                            <a href="{{ route('incidencias.empezar', $incidencia->id) }}" class="btn btn-primary">Empezar</a>
                        @elseif($incidencia->estado->nombre == 'En trabajo')
                            <a href="{{ route('incidencias.resolver', $incidencia->id) }}" class="btn btn-success">Resolver</a>
                            <a href="{{ route('incidencias.mensaje', $incidencia->id) }}" class="btn btn-info">Enviar Mensaje</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection