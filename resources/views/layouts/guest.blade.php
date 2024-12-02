<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Iniciar sesión</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-food-image bg-cover flex items-center min-h-screen font-sans antialiased">
    <div class="flex w-full max-w-7xl mx-auto rounded-xl shadow-2xl overflow-hidden">

    <div x-data="{ activeSlide: 0, slides: ['images/images4.png', 'images/slide2.png', 'images/slide3.jpg'] }"
     x-init="setInterval(() => activeSlide = (activeSlide + 1) % slides.length, 4000)"
     class="w-1/2 relative bg-cover bg-center text-white p-16 flex flex-col justify-center">

    <!-- Imágenes de fondo del carrusel -->
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index" class="absolute inset-0 bg-cover bg-center"
             :style="`background-image: url(${slide})`">
        </div>
    </template>

    <!-- Superposición de color y texto de bienvenida -->
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative z-10">
        <h1 class="text-2xl font-bold mb-4">Bienvenido a BAIXIANG FAMA</h1>
        <p class="text-lg leading-relaxed">La mejor selección de productos a un clic de distancia. Inicia sesión para explorar nuestros menús.</p>
    </div>
</div>


        <!-- Sección de login -->
        <div class="w-1/2 bg-white p-16 flex flex-col justify-center">
    <div class="text-center mb-8">
        <img src="{{ asset('images/images2.png') }}" alt="Logo" class="mx-auto img-fluid" style="max-height:90px;">
        <h2 class="text-2xl font-semibold text-gray-800 mt-4">Iniciar sesión</h2>
        <p class="text-gray-600 mt-1">Accede a tu cuenta para disfrutar</p>
    </div>

    <!-- Contenido del formulario -->
    {{ $slot }}

    <div class="text-center mt-8">
        <p class="text-gray-600">¿Eres nuevo? <a href="{{ route('register') }}" class="text-green-600 font-semibold">Regístrate aquí</a></p>
    </div>
</div>

    </div>
</body>
</script>
</html>
