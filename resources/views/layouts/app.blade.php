<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @livewireStyles
</head>
<body class="antialiased min-h-screen bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-gray-100">
    {{ $slot }}

    @filamentScripts
    @livewireScripts
</body>
</html>

