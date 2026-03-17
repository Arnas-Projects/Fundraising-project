<a href="{{ route('stories.index') }}">Grįžti atgal</a>

<h1>{{ $story->title }}</h1>

@if ($story->main_image)
    <img src="{{ asset('storage/' . $story->main_image) }}" width="400">
@endif

<p>{{ $story->short_description }}</p>

<hr>

<p>{{ $story->full_story }}</p>

<h3>Renkama suma: {{ $story->goal_amount }} EUR</h3>

<h3>Surinkta: {{ $story->donations->sum('amount') }}</h3>


<br>

<h3>Paremti kampaniją</h3>

@auth
    <form method="POST" action="{{ route('donations.store', $story) }}">
        @csrf

        <input type="number" name="amount" step="0.01" placeholder="Įveskite sumą">

        <button type="submit">Aukoti</button>
    </form>
@endauth

@guest
    <p>
        <a href="/login">Prisijunkite</a>, kad galėtumėte skirti paramą.
    </p>
@endguest