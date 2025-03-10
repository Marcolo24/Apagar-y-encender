<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SolveIT</title>
    <script src="{{ asset('js/login.js') }}" defer></script> <!-- Archivo JavaScript externo -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
        <!-- Fuentes de Google -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <img src="{{ asset('img/logo.png') }}" alt="Restopolitan Logo" width="150">
        </div>
    </nav>

    <!-- Contenedor del formulario -->
    <div class="container">
        <h2>SolveIT</h2>
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <label>E-mail:</label>
            <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico">
            <span class="error-message" id="emailError" style="color: red; font-size: 14px;"></span>

            <label>Contraseña:</label>
            <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña">
            <span class="error-message" id="passwordError" style="color: red; font-size: 14px;"></span><br>
            
            <!-- Aquí mostramos el error global si las credenciales son incorrectas -->
            @if ($errors->has('credentials'))
                <span class="invalid-feedback" role="alert" style="color: red">
                    {{ $errors->first('credentials') }}
                </span><br>
            @endif
            
            <br>
            <a href="#" class="forgot-password">¿Has olvidado tu contraseña?</a>
            <button type="submit">IDENTIFICATE</button>
        </form>
    </div>
</body>
</html>