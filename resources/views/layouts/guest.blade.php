<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Fundraising Project | Sveiki atvykę!')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.scss', 'resources/css/auth.scss', 'resources/js/app.js'])
    </head>
    <body class="auth-page">
        <div class="auth-shell">
            <div class="auth-shell__inner">
                {{-- <div class="auth-shell__brand">
                    <a href="/" class="auth-shell__logo-link" aria-label="{{ config('app.name', 'Laravel') }} home">
                        <x-application-logo class="auth-shell__logo" />
                    </a>
                </div> --}}

                <div class="auth-shell__card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
