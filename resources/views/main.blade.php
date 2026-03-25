<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=TikTok+Sans:opsz,wght@12..36,300..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.scss', 'resources/css/auth.scss'])
    <title>{{ $title ?? 'Fundraising Project' }}</title>
</head>

<body>

    @include('header')
    <hr>
    @include('messages')

    <main class="main-content">
        <div class="nav-wrapper">
            @yield('content')
        </div>
    </main>
    @include('footer')


    {{-- @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color:crimson;">{{ session('error') }}</p>
    @endif --}}

</body>

</html>
