<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DevStagram - @yield('titulo')</title>
        
        @vite('resources/css/app.css')
    </head>

    <body>

    <h1 class="text-4xl bg-red-600">@yield('titulo')</h1>


    @yield('contenido')

    </body>

</html>