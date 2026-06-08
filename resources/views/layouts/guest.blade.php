<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
  <body class="font-sans text-gray-900 antialiased">
    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 overflow-hidden">
        
        <div class="absolute inset-0 z-0 bg-cover bg-center" 
             style="background-image: url('/img/fondo_ven911.jpg'); filter: blur(6px); transform: scale(1.1);">
        </div>

        <div class="absolute inset-0 z-0 bg-green-900/40"></div>

        <div class="relative z-10 w-full sm:max-w-md mt-6 px-10 py-12 bg-white/95 backdrop-blur-md shadow-2xl overflow-hidden sm:rounded-3xl border-b-8 border-red-600">
            {{ $slot }}
        </div>

        <p class="relative z-10 mt-4 text-white font-black tracking-widest uppercase drop-shadow-lg text-sm">
            VEN-911 &copy; 2026
        </p>
    </div>
</body>
</html>