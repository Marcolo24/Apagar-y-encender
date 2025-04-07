@extends('layout.layout')

@section('title', 'Detalle de Incidencia')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('dashboard.cliente') }}" class="btn btn-secondary">
                ← Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-4">{{ $incidencia->titulo }}</h2>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <h5 class="fw-bold">Descripción</h5>
                        <p>{{ $incidencia->descripcion }}</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Categoría</h5>
                            <p>{{ $incidencia->subcategoria->categoria->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold">Subcategoría</h5>
                            <p>{{ $incidencia->subcategoria->nombre }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h5 class="fw-bold">Estado</h5>
                            <p>{{ $incidencia->estado->nombre }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-bold">Prioridad</h5>
                            <p>{{ $incidencia->prioridad->nombre }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-bold">Técnico Asignado</h5>
                            <p>{{ $incidencia->tecnico ? $incidencia->tecnico->name : 'Sin asignar' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Fecha de Inicio</h5>
                            <p>{{ $incidencia->fecha_inicio }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold">Fecha de Cierre</h5>
                            <p>{{ $incidencia->fecha_final ?? 'No cerrada' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    @if($incidencia->img)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Imagen Adjunta</h5>
                            </div>
                            <div class="card-body">
                                <img src="{{ Storage::url($incidencia->img) }}" 
                                     class="img-fluid rounded" 
                                     alt="Imagen de la incidencia">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
