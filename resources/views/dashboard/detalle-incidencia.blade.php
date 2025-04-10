@extends('layout.layout')

@section('title', 'Detalle de Incidencia')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12 mb-4 mt-3">
            <a href="{{ route('dashboard.cliente') }}" class="btn btn-secondary">
                ← Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h2 class="card-title mb-4">{{ $incidencia->titulo }}</h2>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <h5 class="fw-bold">Descripción</h5>
                        <p class="mb-0">{{ $incidencia->descripcion }}</p>
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Categoría</h5>
                            <p class="mb-0">{{ $incidencia->subcategoria->categoria->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold">Subcategoría</h5>
                            <p class="mb-0">{{ $incidencia->subcategoria->nombre }}</p>
                        </div>
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-md-4">
                            <h5 class="fw-bold">Estado</h5>
                            <p class="mb-0">{{ $incidencia->estado->nombre }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-bold">Prioridad</h5>
                            <p class="mb-0">{{ $incidencia->prioridad->nombre }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-bold">Técnico Asignado</h5>
                            <p class="mb-0">{{ $incidencia->tecnico ? $incidencia->tecnico->name : 'Sin asignar' }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Fecha de Inicio</h5>
                            <p class="mb-0">{{ $incidencia->fecha_inicio }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold">Fecha de Cierre</h5>
                            <p class="mb-0">{{ $incidencia->fecha_final ?? 'No cerrada' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    @if($incidencia->img)
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Imagen Adjunta</h5>
                            </div>
                            <div class="card-body p-3">
                                <img src="{{ Storage::url($incidencia->img) }}" 
                                     class="img-fluid rounded w-100"
                                     alt="Imagen de la incidencia">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .container-fluid {
        max-width: 1800px;
        margin: 0 auto;
    }

    .card {
        border-radius: 0.5rem;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
    }
</style>
@endpush
@endsection
