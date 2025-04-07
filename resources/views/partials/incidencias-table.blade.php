@foreach ($incidencias as $incidencia)
    <tr class="fila-incidencia" data-href="{{ route('incidencias.detalle', $incidencia->id) }}" style="cursor: pointer;">
        <td>{{ $incidencia->titulo }}</td>
        <td>{{ $incidencia->descripcion }}</td>
        <td>{{ $incidencia->prioridad->nombre }}</td>
        <td>{{ $incidencia->fecha_inicio }}</td>
        <td>{{ $incidencia->fecha_final ?? '--' }}</td>
        <td>{{ $incidencia->estado->nombre }}</td>
        <td onclick="event.stopPropagation();">
            <button 
                class="btn btn-primary btn-sm cerrar-incidencia-btn {{ $incidencia->estado->nombre !== 'Resuelta' ? 'disabled' : '' }}"
                data-id="{{ $incidencia->id }}"
                data-estado="{{ $incidencia->estado->nombre }}"
            >
                Cerrar
            </button>
        </td>
    </tr>
@endforeach
