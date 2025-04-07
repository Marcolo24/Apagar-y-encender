<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Next:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('/img/logo.png')}}" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <header>
        <div id="divHeader">
            <div>
                <img id="logo" src="{{ asset('/img/logo.png') }}" alt="">
            </div>
            <div>
               <h1 id="titulo" class="margin0">SolveIT</h1> 
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span id="rolUsuario">
                    {{ Auth::user()->rol->nombre ?? 'Desconocido' }}
                </span>
                <div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img id="btnCerrarSesion" src="{{ asset('/img/cerrarSesion.png') }}" alt="Cerrar sesión">
                    </a>
                </div>                
            </div>
        </div>
    </header>
    <main>
        @yield('content') <!-- Aquí se inyectará el contenido de las vistas hijas -->
    </main>
    @yield('scripts') <!-- Aquí ponemos el contenido de la validación JS-->
</body>
</html>
