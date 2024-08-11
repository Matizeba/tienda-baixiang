<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAIXIANG</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Styles -->
    @vite(['resources/css/app1.css','resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <span class="text-white text-2xl mx-2 font-semibold">BAIXIANG FAMA</span>
                </div>
            </div>
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <span class="text-white text-2xl mx-2 font-semibold"></span>
                </div>
            </div>
            <a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuarios</a>
            <a href="{{route('users2.index')}}"><i class="fas fa-user-friends"></i> Clientes</a>
            <a href="{{route('products.index')}}"><i class="fas fa-box"></i> Productos</a>
            <a href="#"><i class="fas fa-shopping-cart"></i> Ventas</a>
            <a href="#"><i class="fas fa-chart-line"></i> Reportes</a>
            <a href="#"><i class="fas fa-bell"></i> Alertas</a>
            <form action="{{ route('send.test.email') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Enviar Correo de Prueba</button>
            </form>
        </div>
        <div class="user-info">
            <!-- Información del usuario aquí -->
        </div>
    </div>
    <div class="content">
        <div class="navbar">
            <div class="breadcrumbs">
                <a href="{{ route('dashboard') }}" class="text-white"><i class="fas fa-home"></i> Home</a> <span>/</span> @yield('breadcrumbs')
            </div>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href=""><i class="fas fa-user"></i> Perfil</a>
                    <a class="dropdown-item" href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div>
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
