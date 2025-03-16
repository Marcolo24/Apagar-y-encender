@extends('layout.layout')

@section('title', 'Admin')

@section('content')
    <br>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Sede</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->apellidos }}</td>
                    <td>{{ $usuario->correo }}</td>
                    <td>{{ $usuario->sede->nombre }}</td>
                    <td>{{ $usuario->rol->nombre }}</td>
                    <td>
                        <a href="">Editar</a>
                        <a href="" id="eliminarUsuario" data-id="{{ $usuario->id }}">Eliminar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="{{ asset('../js/eliminarUsuarios.js') }}"></script>
@endsection
