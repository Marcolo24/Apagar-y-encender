@extends('layout.layout')

@section('title', 'Enviar Mensaje')

@section('content')
    <form action="{{ route('incidencias.enviarMensaje', $id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea name="mensaje" id="mensaje" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
@endsection
