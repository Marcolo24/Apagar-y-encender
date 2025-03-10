@extends('layout.layout')

@section('title', 'Login')

@section('content')
<div class="container">
    <form id="loginForm" method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-flex-container">
            <div class="logo-section">
                <img src="{{asset('/img/logo.png')}}" alt="Logo" class="form-logo">
            </div>
            <div class="form-section">
                <h2>SolveIT</h2>
                <div>
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico">
                    <span class="error-message" id="emailError"></span>
                </div>

                <div>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña">
                    <span class="error-message" id="passwordError"></span>
                </div>

                @if ($errors->has('credentials'))
                    <span class="invalid-feedback" role="alert">
                        {{ $errors->first('credentials') }}
                    </span>
                @endif
                
                <button type="submit">IDENTIFICATE</button>
            </div>
        </div>
    </form>
</div>
@endsection



