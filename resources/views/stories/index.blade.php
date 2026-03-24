@extends('main')

@section('content')
    <div class="wrapper">

        <div class="wrapper-header">
            <h1>Lėšų rinkimo kampanijos</h1>

            {{-- Filtravimas pagal tagus - drop down meniu --}}
            {{-- <div class="filter-container">
                <form method="GET" action="{{ route('stories.index') }}">
                    <label for="tag">Filtruoti pagal žymą:</label>
                    <select name="tag" id="tag" onchange="this.form.submit()">
                        <option value="">Visos žymos</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                #{{ $tag->slug }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Filtruoti</button>
                </form>
            </div> --}}

            {{-- Filtravimas pagal tagus - drop down meniu su mygtuku --}}
            <div class="filter-container">
                <form method="GET" action="{{ route('stories.index') }}">
                    <label for="tag">Filtruoti pagal žymą:</label>
                    <select name="tag" id="tag">
                        <option value="">Visos žymos</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                #{{ $tag->slug }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Filtruoti</button>
                </form>
            </div>
        </div>

        {{-- Sėkmės žinutė, rodoma, kai yra kampanijų atitinkančių paiešką --}}
        @if (isset($successMessage))
            <p class="message message-success">{{ $successMessage }}</p>
        @endif

        @if (isset($info))
            <p class="message message-info">{{ $info }}</p>
        @endif

        {{-- <div class="action-box">
            <div>
                <a href="{{ route('stories.create') }}" data-text="Sukurti naują kampaniją">Sukurti naują kampaniją</a>
                <a href="{{ route('dashboard') }}" data-text="Mano prietaisų skydelis">Mano prietaisų skydelis</a>
            </div>
            <div>
                @guest
                    <a href="{{ route('login') }}">Prisijungti</a>
                    <a href="{{ route('register') }}">Registruotis</a>
                @endguest
                @auth
                    <span><strong>{{ auth()->user()->name }}</strong></span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="logout-btn" type="submit">Atsijungti</button>
                    </form>
                @endauth
            </div>
        </div> --}}

        <div class="cards-container">
            @foreach ($stories as $story)
                <div class="story-item card">
                    {{-- style="margin-bottom:40px; border-bottom:1px solid #ccc; padding-bottom:20px;"> --}}

                    <h2>
                        <a href="{{ route('stories.show', $story) }}">
                            {{ $story->title }}
                        </a>
                    </h2>

                    @if ($story->main_image)
                        <img src="{{ asset('storage/' . $story->main_image) }}" width="250">
                    @endif

                    <p>{{ $story->short_description }}</p>

                    <p> Surinkta:
                        <span>{{ $story->donations->sum('amount') }} EUR iš </span><span>{{ $story->goal_amount }} EUR</span>
                    </p>

                    <p> Iki tikslo liko:
                        <span>{{ $story->goal_amount - $story->donations->sum('amount') }} EUR</span>
                    </p>

                    {{-- <p>Tikslas: {{ $story->goal_amount }} EUR</p>
                    <p>Surinkta: {{ $story->donations->sum('amount') }} EUR</p> --}}

                    {{-- LIKING AND LIKES COUNT --}}
                    <div class="likes-container">
                        <p>Likes: {{ $story->likes->count() }}</p>
                        @auth
                            <form method="POST" action="{{ route('stories.like', $story) }}">
                                @csrf
                                <button type="submit" class="like-btn">
                                    {{ $story->likes->where('user_id', auth()->id())->count() ? 'Unlike' : 'Like' }}
                                </button>
                            </form>
                        @endauth
                    </div>

                    {{-- Goal bar --}}
                    <div class="progress-bar">
                        <div class="progress-fill"
                            style="width: {{ ($story->donations->sum('amount') / $story->goal_amount) * 100 }}%;
                        padding: {{ ($story->donations->sum('amount') / $story->goal_amount) * 100 > 10 ? '0 10px' : '0' }};
                        ">
                            {{ round(($story->donations->sum('amount') / $story->goal_amount) * 100) }}%
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <br>

        <div class="pagination">
            {{ $stories->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
