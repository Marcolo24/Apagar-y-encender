@extends('layout.layout')

@section('title', 'Incidencias por Técnico')

@section('content')
    <h1>Incidencias Asignadas a Técnicos</h1>

    <!-- Botón para volver a la página del gestor -->
    <a href="{{ route('dashboard.gestor') }}" class="btn btn-secondary">← Volver al Gestor</a>

    @if($incidenciasAsignadas->isEmpty())
        <p>No hay incidencias asignadas a técnicos.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Técnico</th>
                    <th>Incidencia</th>
                    <th>Descripción</th>
                    <th>Cliente</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidenciasAsignadas as $incidencia)
                    <tr>
                        <td>{{ $incidencia->tecnico->name ?? 'No asignado' }}</td>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->cliente->name }}</td>
                        <td>{{ $incidencia->prioridad->nombre }}</td>
                        <td>{{ $incidencia->estado->nombre }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
