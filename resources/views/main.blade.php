<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=TikTok+Sans:opsz,wght@12..36,300..900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <title>{{ $title ?? 'Fundraising Project' }}</title>
</head>

<body>

    <div class="nav-wrapper">
        <nav class="action-box">
            <div>
                <a href="/" data-text="Pagrindinis">Pagrindinis</a>

                @auth
                    <a href="/dashboard" data-text="Prietaisų skydelis">Prietaisų skydelis</a>
                    <a href="/stories/create" data-text="Sukurti kampaniją">Sukurti kampaniją</a>
                @endauth

                <form method="GET" action="{{ route('stories.index') }}" style="display:inline-block;">
                    @csrf
                    <input type="text" name="search" placeholder="Ieškoti kampanijų..."
                        value="{{ request('search') }}">
                    <button type="submit" data-text="Ieškoti">Ieškoti</button>
                </form>

                {{-- <form method="GET" action="/" style="display:inline;">
                    <input type="text" name="search" placeholder="Search campaigns...">
                    <button type="submit">Search</button>
                </form> --}}
            </div>

            <div>
                @auth
                    <form method="POST" action="/logout">
                        @csrf
                        {{-- ADMINS ONLY --}}
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.index') }}" data-text="Administratorius">Administratorius</a>
                            @endif
                        @endauth
                        <span style="margin-right:15px;"><strong>{{ auth()->user()->name }}</strong></span>
                        <button type="submit" data-text="Atsijungti">Atsijungti</button>
                    </form>
                @endauth

                @guest
                    <a href="/login" data-text="Prisijungti">Prisijungti</a>
                    <a href="/register" data-text="Registruotis">Registruotis</a>
                @endguest
            </div>
        </nav>
    </div>


    <hr>

    @if (session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p style="color:crimson;">{{ session('error') }}</p>
    @endif

    <div>
        @yield('content')
    </div>

</body>

</html>
