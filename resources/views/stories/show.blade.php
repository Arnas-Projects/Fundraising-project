@extends('main')



@guest
    <div>
        {{-- style="display:flex; gap:15px; justify-content:flex-end; padding:20px;"> --}}
        <a href="/login">Prisijungti</a>
        <a href="/register">Registruotis</a>
    </div>
@endguest


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

                @auth
                    <div>
                        {{-- style="display:flex; gap:15px; justify-content:flex-end; padding:20px;"> --}}
                        <span>{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Atsijungti</button>
                        </form>
                    </div>
                @endauth
            </ul>
        </nav>

        <p class="status-badge">Statusas:
            @if ($story->status === 'active')
                <span class="status-active">Aktyvi</span>
            @else
                <span class="status-closed">Uždaryta</span>
            @endif
        </p>

        @if ($story->status === 'closed')
            <p class="status-closed">Kampanija uždaryta</p>
        @else
            <p class="status-active">Kampanija aktyvi</p>
        @endif

        <div class="box-container color3">
            <h1>{{ $story->title }}</h1>

            <div class="tag-container">
                <h3>Žymos:</h3>
                @foreach ($story->tags as $tag)
                    <a href="{{ route('tags.show', $tag) }}" class="tag">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>

            <p>Autorius: {{ $story->user->name ?? 'Nežinomas' }}</p>


            @if ($story->main_image)
                <img src="{{ asset('storage/' . $story->main_image) }}" width="200">
            @endif

            <p>{{ $story->short_description }}</p>

            <hr>

            <p>{{ $story->full_story }}</p>

            <hr>

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

            <div class="progress-bar" style="width:400px; background:#cacaca; height:15px; border-radius:50px;">
                <div class="progress-fill"
                    style="width:{{ $percentage }}%; background:green; height:15px; border-radius:50px;"></div>
            </div>

            <p>{{ round($percentage) }}% surinkta</p>
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


        <div class="box-container color2">
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
                <p>Ši kampanija uždaryta.</p>
            @endif

            @guest
                <p>
                    <a href="/login">Prisijunkite</a>, kad galėtumėte skirti paramą.
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
            <a href="/login">Prisijunkite</a>, kad galėtumėte skirti paramą.
        </p>
    @endguest

    @else

        <h3>Ši kampanija pasiekė tikslą 🎉</h3>
        
    @endif --}}


        <div class="box-container color1">
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
    @endsection
</div>
