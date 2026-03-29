<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Support our fundraising project and make a difference today.">
    <meta name="keywords" content="fundraising, charity, donation, community">
    <meta name="author" content="Fundraising Project">
    <meta property="og:title" content="@yield('title', 'Fundraising Project')">
    <meta property="og:description" content="Support our fundraising project and make a difference today.">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="icon" href="{{ asset('web_icon/beaver.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=TikTok+Sans:opsz,wght@12..36,300..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <title>@yield('title', 'Fundraising Project')</title>
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
