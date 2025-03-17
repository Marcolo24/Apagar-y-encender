@extends('layout.layout')

@section('title', 'Admin')

@section('content')
    <!-- Añadir el meta tag para CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <br>
    <h1>Gestión de usuarios</h1>
    <div id="divFiltrosUsuario" class="d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3">
            <div>
                <input type="text" name="name" class="question" id="inputNombre" required autocomplete="off" placeholder="Buscar usuario"/>
                <label for="buscar"><span>Buscar usuario</span></label>
            </div>
            <div>
                <input type="text" name="email" class="question" id="inputEmail" required autocomplete="off" placeholder="Buscar email"/>
                <label for="buscar"><span>Buscar email</span></label>
            </div>
            <div class="divFiltroSede">
                <select class="question" name="sede" id="inputSede">
                    <option disabled selected value="">Sede</option>
                    @foreach($sedes as $sede)
                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                
            </div>
            <button class="btn btn-secondary" id="btnReiniciarFiltros">
                <i class="bi bi-arrow-counterclockwise"></i> Reiniciar filtros
            </button>
        </div>
        <div class="d-flex gap-3" id="divBtns">
            <button class="btn btn-primary" id="btnCrearUsuario">Crear usuario</button>
            <button class="btn btn-danger" id="btnCrearCategoria">Crear categoría</button>
        </div>
    </div>

    <br>
    <table>
        <thead>
            <tr>
                <th onclick="ordenarTabla('id')">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="margin0">ID</p>
                        <p class="margin0">↑↓</p>
                    </div>
                </th>
                <th onclick="ordenarTabla('name')">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="margin0">Nombre</p>
                        <p class="margin0">↑↓</p>
                    </div>
                </th>
                <th onclick="ordenarTabla('apellidos')">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="margin0">Apellidos</p>
                        <p class="margin0">↑↓</p>
                    </div>
                </th>
                <th onclick="ordenarTabla('correo')">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="margin0">Email</p>
                        <p class="margin0">↑↓</p>
                    </div>
                </th>
                <th onclick="ordenarTabla('sede')">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="margin0">Sede</p>
                        <p class="margin0">↑↓</p>
                    </div>
                </th>
                <th onclick="ordenarTabla('rol')">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="margin0">Rol</p>
                        <p class="margin0">↑↓</p>
                    </div>
                </th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-usuarios">
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->apellidos }}</td>
                    <td>{{ $usuario->correo }}</td>
                    <td>{{ $usuario->sede->nombre }}</td>
                    <td>{{ $usuario->rol->nombre }}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm rounded-3 me-2" data-id="{{ $usuario->id }}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-3" id="eliminarUsuario" data-id="{{ $usuario->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="{{ asset('../js/eliminarUsuarios.js') }}"></script>
    <script src="{{ asset('../js/editarUsuarios.js') }}"></script>
    <script src="{{ asset('../js/crearUsuarios.js') }}"></script>
    <script src="{{ asset('../js/filtrarUsuarios.js') }}"></script>
    <script src="{{ asset('../js/crearCategorias.js') }}"></script>
@endsection
