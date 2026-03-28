@extends('main')

@section('content')
    <div class="wrapper">

        <div class="wrapper-header">
        
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

                    <label for="like">Filtruoti pagal patiktukus:</label>
                    <select name="like" id="like">
                        <option value="">Visos kampanijos</option>
                        <option value="most" {{ request('like') == 'most' ? 'selected' : '' }}>Mažėjimo tvarka</option>
                        <option value="least" {{ request('like') == 'least' ? 'selected' : '' }}>Didėjimo tvarka</option>
                    </select>

                    <button type="submit">Filtruoti</button>
                </form>
            </div>

            <h1>Lėšų rinkimo kampanijos</h1>
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

        {{-- Kampanijų sąrašas pagal surinktą sumą. Pirmiausia rodomos mažiausiai arba 0 pinigų surinkusios kampanijos --}}

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

                    {{-- Show up to 3 tags --}}
                    <div class="tag-container">
                        @foreach ($story->tags->take(3) as $tag)
                            <a href="{{ route('stories.index', ['tag' => $tag->id]) }}">#{{ $tag->slug }}</a>
                        @endforeach
                    </div>

                    <p>{{ $story->short_description }}</p>

                    <p> Surinkta:
                        <span>{{ $story->total_donated ?? 0 }} EUR iš </span><span>{{ $story->goal_amount }} EUR</span>
                    </p>

                    @if ($story->goal_amount > ($story->total_donated ?? 0))
                        <p> Iki tikslo liko:
                            <span>{{ $story->goal_amount - ($story->total_donated ?? 0) }} EUR</span>
                        </p>
                    @elseif (($story->total_donated ?? 0) >= $story->goal_amount)
                        <p> Kampanija pasiekė tikslą! </p>
                    @endif

                    {{-- <p>Tikslas: {{ $story->goal_amount }} EUR</p>
                    <p>Surinkta: {{ $story->total_donated ?? 0 }} EUR</p> --}}

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
                            style="width: {{ ($story->total_donated ?? 0) / $story->goal_amount * 100 }}%;
                        padding: {{ ($story->total_donated ?? 0) / $story->goal_amount * 100 > 10 ? '0 10px' : '0' }};
                        ">
                            {{ round(($story->total_donated ?? 0) / $story->goal_amount * 100) }}%
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
