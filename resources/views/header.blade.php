{{-- Project logo and name container --}}
<header>
    <div class="nav-wrapper">
        {{-- Navigation bar --}}
        <nav class="action-box">
            <div>
                <a href="/" data-text="Pagrindinis">Pagrindinis</a>

                @auth
                    <a href="/dashboard" data-text="Prietaisų skydelis">Prietaisų skydelis</a>
                    <a href="/stories/create" data-text="Sukurti kampaniją">Sukurti kampaniją</a>
                @endauth

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
                                <a class="admin-panel-link" href="{{ route('admin.index') }}"
                                    data-text="Administratorius">Administratorius</a>
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

        <div class="action-box">
            <a href="/" class="project-logo">
                <img src="{{ asset('images/beaver-funding.png') }}" alt="Project Logo" class="project-logo">
            </a>

            <form method="GET" action="{{ route('stories.index') }}">
                @csrf

                {{-- If search field is empty, do not reload the page, otherwise filter campaigns by title --}}
                @if (request('search') && request('search') !== '')
                    <input type="text" name="search" placeholder="Ieškoti kampanijų..."
                        value="{{ request('search') }}">
                @else
                    <input type="text" name="search" placeholder="Ieškoti kampanijų...">
                @endif
                <button type="submit" data-text="Ieškoti">Ieškoti</button>
            </form>
        </div>
    </div>
</header>
