<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('BAIXIANG') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle with Popper -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    @vite(['resources/css/app1.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'block' : 'hidden'" class="fixed inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>
        <aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="sidebar fixed z-30 inset-y-0 left-0 w-64 transition duration-300 bg-gradient-to-r from-blue-800 to-blue-600 text-white overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
             <div>
                <div class="text-center mt-4 mb-4">
                <div class="flex items-center justify-center mt-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
            </div>
            <div class="flex items-center justify-center mt-8">
                <span class="text-2xl font-semibold">BAIXIANG FAMA</span>
            </div>
            <nav class="mt-10">
            @if(auth()->user()->role != 3 && auth()->user()->id != 1 && auth()->user()->passwordUpdate)
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('dashboard') }}">
                    <i class="fas fa-bars"></i>
                    <span class="mx-3">Menú</span>
                </a>
            @elseif(auth()->user()->role == 1)
                <!-- Menú Principal -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('dashboard') }}">
                    <i class="fas fa-bars"></i>
                    <span class="mx-3">Menú</span>
                </a>

                <!-- Gestión de Usuarios -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i>
                    <span class="mx-3">Usuarios</span>
                </a>

                <!-- Gestión de Clientes -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('clients.index') }}">
                    <i class="fas fa-user-friends"></i>
                    <span class="mx-3">Clientes</span>
                </a>

                <!-- Productos y Categorías -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i>
                    <span class="mx-3">Productos</span>
                </a>
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('categories.index') }}">
                    <i class="fas fa-list"></i>
                    <span class="mx-3">Categorías</span>
                </a>
                
                <!-- Compras y Historial -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('purchases.index') }}">
                    <i class="fas fa-history"></i>
                    <span class="mx-3">Historial de Compras</span>
                </a>
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('purchases.view') }}">
                    <i class="fas fa-eye"></i>
                    <span class="mx-3">Ver Productos</span>
                </a>

                <!-- Ventas y Reportes -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('sales.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="mx-3">Ventas</span>
                </a>
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('reports.sales_by_month') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span class="mx-3">Reporte de Ventas por Mes</span>
                </a>
            @elseif(auth()->user()->role == 2)
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i>
                    <span class="mx-3">Productos</span>
                </a>
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('categories.index') }}">
                    <i class="fas fa-list"></i>
                    <span class="mx-3">Categorías</span>
                </a>
                
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('purchases.view') }}">
                    <i class="fas fa-eye"></i>
                    <span class="mx-3">Ver Productos</span>
                </a>

                <!-- Ventas y Reportes -->
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('sales.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="mx-3">Ventas</span>
                </a>
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('reports.sales_by_month') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span class="mx-3">Reporte de Ventas por Mes</span>
                </a>
                @else
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('purchases.view') }}">
                    <i class="fas fa-eye"></i>
                    <span class="mx-3">Ver Productos</span>
                </a>
                <a class="nav-link flex items-center mt-4 py-2 px-6 hover:bg-blue-500" href="{{ route('purchases.index') }}">
                    <i class="fas fa-history"></i>
                    <span class="mx-3">Historial de Compras</span>
                </a>
                @endif

            </nav>

        </aside>
        <div class="flex-1 flex flex-col overflow-hidden">
            
        <header class="header flex justify-between items-center p-4 bg-blue-800 text-white shadow">
    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-200 lg:hidden">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <div class="breadcrumbs">
        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-gray-100">Menu</a>@yield('breadcrumbs')
    </div>
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="px-4 py-2 text-sm hover:bg-blue-700">
            {{ Auth::user()->name }}
        </button>
        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md overflow-hidden shadow-xl z-10">
            <a class="block px-4 py-2 text-sm text-black hover:bg-gray-200">Perfil</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="block px-4 py-2 text-sm text-black hover:bg-gray-200 w-full text-left">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</header>
<!-- Main Content -->
<main class="flex-1 overflow-auto p-6 bg-gray-100">
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

    @yield('content')
</main>
</div>
</div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
