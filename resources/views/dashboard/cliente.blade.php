@extends('layout.layout')

@section('content')
<main class="container">
    <h1>Pantalla Cliente</h1>

    <!-- Formulario para crear incidencia -->
    <form action="{{ route('incidencias.crear') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="id_subcategoria" class="form-label">Categoría</label>
            <select name="id_subcategoria" id="id_subcategoria" class="form-control" required>
                <option value="">Seleccione una subcategoría</option>
                @foreach(\App\Models\Categoria::with('subcategorias')->get() as $categoria)
                    <optgroup label="{{ $categoria->nombre }}">
                        @foreach($categoria->subcategorias as $subcategoria)
                            <option value="{{ $subcategoria->id }}">{{ $subcategoria->nombre }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>            
        </div>
        <div class="mb-3">
            <label for="id_prioridad" class="form-label">Prioridad</label>
            <select name="id_prioridad" id="id_prioridad" class="form-control" required>
                @foreach(\App\Models\Prioridad::all() as $prioridad)
                    <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Incidencia</button>
    </form>

    <!-- Tabla de incidencias -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Incidencias</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Cerrar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incidencias as $incidencia)
                <tr>
                    <td>{{ $incidencia->titulo }}</td>
                    <td>{{ $incidencia->descripcion }}</td>
                    <td>{{ $incidencia->prioridad->nombre }}</td>
                    <td>{{ $incidencia->estado->nombre }}</td>
                    <td>
                        @if($incidencia->estado->nombre === 'Resuelta')
                            <form action="{{ route('incidencias.cerrar', $incidencia->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">Cerrar</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
@endsection
