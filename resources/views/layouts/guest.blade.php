<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ClubSaaS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center px-4">

        <!-- Logo / Marca -->
        <a href="/" class="mb-6 text-2xl font-bold text-green-600">
            ClubSaaS
        </a>

        <!-- Card -->
        <div class="w-full max-w-md bg-white p-6 rounded-xl shadow-sm border">

            {{ $slot }}

        </div>

    </div>

</body>
</html>
