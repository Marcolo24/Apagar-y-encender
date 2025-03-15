@extends('layout.layout')

@section('title', 'Incidencias por Técnico')

@section('content')
    <br>
    <h1>Incidencias Asignadas a cada Técnico</h1>
    
    <!-- Botón para volver a la página del gestor -->
    <a href="{{ route('dashboard.gestor') }}" class="btn btn-secondary">← Volver al Gestor</a>
    <br>
    <br>

    @if($incidenciasAsignadas->isEmpty())
        <p>No hay incidencias asignadas a técnicos.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Incidencia</th>
                    <th>Descripción</th>
                    <th>Cliente afectado</th>
                    <th>Prioridad</th>
                    <th>Técnico Encargado</th>
                    <th>Asignar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incidenciasAsignadas as $incidencia)
                    <tr>
                        <td>{{ $incidencia->titulo }}</td>
                        <td>{{ $incidencia->descripcion }}</td>
                        <td>{{ $incidencia->cliente->name }}</td>
                        <td>{{ $incidencia->prioridad->nombre }}</td>
                        <td>{{ $incidencia->tecnico->name ?? 'No asignado' }}</td>
                        <td>
                            <!-- Formulario para cambiar el técnico encargado -->
                            <form action="{{ route('incidencias.updateTecnico', $incidencia->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="id_tecnico" class="form-control" onchange="this.form.submit()">
                                    <option value="">Seleccionar técnico</option>
                                    @foreach ($tecnicos as $tecnico)
                                        <option value="{{ $tecnico->id }}" {{ $incidencia->id_tecnico == $tecnico->id ? 'selected' : '' }}>
                                            {{ $tecnico->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
