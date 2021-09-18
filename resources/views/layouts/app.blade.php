<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Coding Chalange') }}</title>

    @include('layouts.partials._scripts')

    @include('layouts.partials._head')
</head>
<body>
    <div id="app" style="background: #f8f9fa!important; height: 100vh;">
        @include('layouts.partials._navbar')

        <main class="py-4">
            @yield('content')
        </main>
    </div>

	@stack('scripts')
</body>
</html>
