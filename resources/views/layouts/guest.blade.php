<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Fundraising Project | Sveiki atvykę!')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=TikTok+Sans:opsz,wght@12..36,300..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.scss', 'resources/css/auth.scss', 'resources/css/welcome-page.scss', 'resources/js/app.js'])
    </head>
    <body class="@yield('body_class', 'auth-page')">
        <div class="@yield('shell_class', 'auth-shell')">
            <div class="@yield('inner_class', 'auth-shell__inner')">
                <div class="@yield('card_class', 'auth-shell__card')">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </div>
            </div>
        </div>
    </body>
</html>
