<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Eugene</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    @vite('resources/css/app.css')

    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">
    <header class="py-4 bg-blue-500">
        <div class="container flex flex-row justify-between px-4 mx-auto ">
            <div>
                <a href="/" class="text-2xl font-semibold text-white">{{ config('app.name', 'Eugene') }}</a>
            </div>
            <div>
                <a href="{{ route('doctors.index') }}"
                    class="mx-2 text-xl font-semibold text-white">{{ __('Doctors') }}</a>
                <a href="{{ route('clinics.index') }}"
                    class="mx-2 text-xl font-semibold text-white">{{ __('Clinics') }}</a>
                <a href="{{ route('tests.index') }}"
                    class="mx-2 text-xl font-semibold text-white">{{ __('Tests') }}</a>
            </div>

        </div>
    </header>

    <main class="mt-8 mb-8">
        @yield('content')
    </main>

    <!-- Scripts -->
    @vite('resources/js/app.js')

    @livewireScripts
</body>

</html>
