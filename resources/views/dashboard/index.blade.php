@extends('main')

@section('content')
    <h1>Prietaisų skydelis</h1>

    <h2>Mano istorijos</h2>
    <ul>
        {{-- User's stories with their status --}}
        @foreach ($myStories as $story)
            <div>
                <a href="{{ route('stories.show', $story) }}">
                    {{ $story->title }}
                </a>

                {{-- Status --}}
                <span class="status-badge">
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
                </span>

                {{-- Funds raised --}}
                @if ($story->status === 'active' || $story->status === 'closed')
                    <p>Surinkta: {{ $story->donations->sum('amount') }} EUR</p>
                    <p>Tikslas: {{ $story->goal_amount }} EUR</p>
                @endif
                
            </div>
        @endforeach
    </ul>

    <hr>

    <h2>Mano aukos</h2>

    @foreach ($myDonations as $donation)
        <div>
            Paaukota {{ $donation->amount }} EUR kampanijai
            <a href="{{ route('stories.show', $donation->story) }}">
                {{ $donation->story->title }}
            </a>
        </div>
    @endforeach

    <hr>
    <div
        style="width:fit-content; margin-top:40px; padding:20px 35px; background:#eee; border:1px solid #ccc; border-radius:5px;">
        <a href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
    </div>
@endsection
