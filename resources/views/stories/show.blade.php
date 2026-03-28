@extends('main')



{{-- @guest
    <div>
        <a href="/login">Prisijungti</a>
        <a href="/register">Registruotis</a>
    </div>
@endguest --}}


{{-- @php
        $raised = $story->donations->sum('amount');
        $goal = $story->goal_amount;
        $percentage = $goal > 0 ? min(100, ($raised / $goal) * 100) : 0;
        @endphp --}}

@section('content')
    <div class="blade-container">
        <nav>
            <ul>
                <li>
                    <a href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
                </li>

                @auth
                    @if ($story->user_id === auth()->id())
                        <li>
                            <a href="{{ route('stories.edit', $story) }}">Redaguoti</a>

                            <form method="POST" action="{{ route('stories.destroy', $story) }}" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Ištrinti</button>
                            </form>
                        </li>
                    @endif
                @endauth
            </ul>
        </nav>

        <div class="status-wrapper">
            {{-- Checking campaign status --}}
            <p class="status-badge">Statusas:
                @if ($story->status === 'active')
                    <span class="status-active">Kampanija aktyvi</span>
                @elseif ($story->status === 'pending')
                    <span class="status-pending">Kampanija laukia patvirtinimo</span>
                @elseif ($story->status === 'closed')
                    <span class="status-closed">Kampanija uždaryta</span>
                @elseif ($story->status === 'rejected')
                    <span class="status-rejected">Kampanija atmesta</span>
                @else
                    <span>{{ $story->status }}</span>
                @endif
            </p>

            {{-- After checking campaign's content get back to the admin panel --}}
            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" data-text="Grįžti į admin panelę">Grįžti į admin panelę</a>
                @endif
            @endauth
        </div>

        <div class="box-container">
            <h1>{{ $story->title }}</h1>

            <div class="tag-container">
                {{-- Slug tags --}}
                <h3>Žymos:</h3>
                @if ($story->tags->count())
                    @foreach ($story->tags as $tag)
                        <a href="{{ route('stories.index', ['tag' => $tag->id]) }}" class="tag">#{{ $tag->slug }}</a>
                    @endforeach
                @else
                    <p>Nėra žymų</p>
                @endif
            </div>

            <p>Autorius: {{ $story->user->name ?? 'Nežinomas' }}</p>


            @if ($story->main_image)
                <div class="main-image-container">
                    <img class="show-img" src="{{ asset('storage/' . $story->main_image) }}" width="200">
                </div>
            @endif

            <p>{{ $story->short_description }}</p>

            <hr>

            <p>{{ $story->full_story }}</p>

            <hr>

            {{-- LIKES --}}
            @php
                $liked = $story->likes->where('user_id', auth()->id())->count();
            @endphp

            @auth
                <form method="POST" action="{{ route('stories.like', $story) }}">
                    @csrf
                    <button type="submit">
                        {{ $liked ? 'Unlike' : 'Like' }} ({{ $story->likes->count() }})
                    </button>
                </form>
            @endauth

            {{-- GALERIJA --}}
            @if ($story->galleryImages->count())
                <h3>Galerija</h3>
                <div class="gallery">
                    @foreach ($story->galleryImages as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" width="200">
                    @endforeach
                </div>
            @endif

            {{-- /////////////////////////  RENKAMA SUMA  ///////////////////////// --}}


            <h3>Tikslas: {{ $goal }} EUR</h3>
            <h3>Surinkta: {{ $raised }} EUR iš {{ $goal }} EUR</h3>

            {{-- <div class="progress-bar" style="width:400px; background:#cacaca; height:15px; border-radius:50px;">
                <div class="progress-fill"
                    style="width:{{ $percentage }}%; background:green; height:15px; border-radius:50px;"></div>
            </div> --}}

            {{-- Goal bar --}}
            <div class="progress-bar2">
                <div class="progress-fill"
                    style="width: {{ ($raised / $goal) * 100 }}%;
                padding: {{ ($raised / $goal) * 100 > 10 ? '0 10px' : '0' }};
                ">
                    {{ round(($raised / $goal) * 100) }}%
                </div>
            </div>

            <p>{{ round(($raised / $goal) * 100) }}% surinkta</p>
        </div>

        {{-- /////////////////////////  RENKAMA SUMA: END  ///////////////////////// --}}


        {{-- <h3>Surinkta: {{ $story->donations->sum('amount') }}</h3> --}}

        {{-- /////////////////////////  RENKAMA SUMA  ///////////////////////// --}}

        {{-- <h3>Renkama suma: {{ $story->goal_amount }} EUR</h3>

        <h3>Surinkta: {{ $raised }} EUR iš {{ $goal }}</h3>

        <div style="width:400px; background:#ddd; height:25px; border-radius:5px;">
            <div style="width:{{ $percentage }}%; background:green; height:25px; border-radius:5px;"></div>
        </div>

        <p>{{ number_format($percentage, 1) }}% surinkta</p> --}}

        {{-- /////////////////////////  RENKAMA SUMA: END  ///////////////////////// --}}


        <div class="box-container">
            @if ($story->status === 'active')
                <h3>Paremti kampaniją</h3>

                @auth
                    <form method="POST" action="{{ route('donations.store', $story) }}">
                        @csrf

                        <input type="number" name="amount" step="0.01" placeholder="Įveskite sumą">

                        <button type="submit">Aukoti</button>
                    </form>
                @endauth
            @else
                <p>Ši kampanija yra:
                    @if ($story->status === 'pending')
                        <span><strong>laukianti patvirtinimo</strong></span>
                    @elseif ($story->status === 'closed')
                        <span><strong>uždaryta</strong></span>
                    @elseif ($story->status === 'rejected')
                        <span><strong>atmesta</strong></span>
                    @endif
                </p>
            @endif

            @guest
                <p>
                    <a href="{{ route('login', ['redirect' => url()->current()]) }}">Prisijunkite</a>, kad galėtumėte skirti
                    paramą.
                </p>
            @endguest
        </div>



        {{-- @if ($raised < $goal)
        <h3>Paremti kampaniją</h3>

        @auth
            <form method="POST" action="{{ route('donations.store', $story) }}">
                @csrf

                <input type="number" name="amount" step="0.01" placeholder="Įveskite sumą">

                <button type="submit">Donate</button>
            </form>
        @endauth

        @guest
            <p>
                <a href="{{ route('login', ['redirect' => url()->current()]) }}">Prisijunkite</a>, kad galėtumėte skirti paramą.
            </p>
        @endguest

        @else

            <h3>Ši kampanija pasiekė tikslą 🎉</h3>
            
        @endif --}}


        <div class="box-container">
            <h3>Naujausios aukos</h3>

            <ul class="donation-list">
                @foreach ($recentDonations as $donation)
                    <li class="donation-item">
                        <strong>{{ $donation->user->name }}</strong>
                        paaukojo
                        <em>{{ $donation->amount }} EUR</em>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- /////////////////////////  ADDING COMMENTS  ///////////////////////// --}}
        <div class="box-container">
            <h3>Rašyti komentarą</h3>

            @auth
                <form class="comment-form" method="POST" action="{{ route('stories.comments', $story) }}">
                    @csrf
                    <textarea name="content" placeholder="Rašykite komentarą..." required></textarea>
                    <button type="submit">Paskelbti</button>
                </form>
            @else
                <p>
                    {{-- Nukreipimas į tą patį puslapį po prisijungimo --}}
                    <a href="{{ route('login', ['redirect' => url()->current()]) }}">Prisijunkite</a>, kad galėtumėte palikti
                    komentarą.
                </p>
            @endauth

            <h3>Komentarai</h3>
            <ul class="comment-list">
                @forelse ($story->comments as $comment)
                    <li class="comment-item">
                        <strong>{{ $comment->user->name }}</strong>:
                        {{ $comment->content }}
                    </li>
                @empty
                    <p> {{ __('Nėra komentarų... :(') }} </p>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
