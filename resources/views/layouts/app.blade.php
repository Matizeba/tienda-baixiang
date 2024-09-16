<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BAIXIANG</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app1.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="flex items-center justify-center mt-8">
            <div class="text-center mt-4 mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
        </div>
                <div class="flex items-center">
                    <span class="text-white text-2xl mx-2 font-semibold">BAIXIANG FAMA</span>
                </div>
            </div>
            <div class="flex items-center justify-center mt-8">
                <div class="flex items-center">
                    <span class="text-white text-2xl mx-2 font-semibold"></span>
                </div>
            </div>
            @if (Auth::user()->role == 1 && Auth::user()->id !=1 && Auth::user()->passwordUpdate  || Auth::user()->role == 2 && Auth::user()->passwordUpdate)

            <a href="{{ route('dashboard') }}"><i class="fas fa-bars"></i> Menú</a>
            @else
            <a href="{{ route('dashboard') }}"><i class="fas fa-bars"></i> Menú</a>
            <a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Usuarios</a>
            <a href="{{route('clients.index')}}"><i class="fas fa-user-friends"></i> Clientes</a>
            <a href="{{route('products.index')}}"><i class="fas fa-box"></i> Productos</a>
            <a href="{{ route('products.view') }}"><i class="fas fa-eye"></i> Ver Productos</a>
            <a href="{{ route('categories.index') }}"><i class="fas fa-list"></i> Categorías</a>
            <a href="{{ route('sales.create') }}"><i class="fas fa-shopping-cart"></i> Ventas</a>
            <a href="{{ route('sales.index') }}"><i class="fas fa-shopping-cart"></i> Ventas</a>
            <a href="#"><i class="fas fa-chart-line"></i> Reportes</a>
            <a href="#"><i class="fas fa-bell"></i> Alertas</a>
            @endif
        </div>
        <div class="user-info">
            <!-- Información del usuario aquí -->
        </div>
    </div>
    <div class="content">
        <div class="navbar">
            <div class="breadcrumbs">
                <a href="{{ route('dashboard') }}" class="text-white"><i class="fas fa-home"></i> Home</a> @yield('breadcrumbs')
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
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            
            
            @yield('content')
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
