{{-- @php
    $raised = $story->donations->sum('amount');
    $goal = $story->goal_amount;
    $percentage = $goal > 0 ? min(100, ($raised / $goal) * 100) : 0;
@endphp --}}

<a href="{{ route('stories.index') }}">Grįžti atgal</a>

<p>Statusas: {{ $story->status }}</p>

@if ($story->status === 'closed')
    <p style="color: crimson;">Kampanija uždaryta</p>    
@endif

<h1>{{ $story->title }}</h1>

@if ($story->main_image)
    <img src="{{ asset('storage/' . $story->main_image) }}" width="400">
@endif

<p>{{ $story->short_description }}</p>

<hr>

<p>{{ $story->full_story }}</p>


{{-- /////////////////////////  RENKAMA SUMA  ///////////////////////// --}}

<h3>Tikslas: {{ $goal }} EUR</h3>
<h3>Surinkta: {{ $raised }} EUR iš {{ $goal }} EUR</h3>

<div style="width:400px; background:#ddd; height:25px; border-radius:5px;">
    <div style="width:{{ $percentage }}%; background:green; height:25px; border-radius:5px;"></div>
</div>

<p>{{ round($percentage) }}% surinkta</p>

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


<br>

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


<h3>Naujausios aukos</h3>

<ul>
    @foreach ($recentDonations as $donation)
        <li>
            {{ $donation->user->name }}
            paaukojo
            {{ $donation->amount }} EUR
        </li>
    @endforeach
</ul>
