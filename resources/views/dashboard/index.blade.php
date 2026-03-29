@extends('main')

@section('title', 'Prietaisų skydelis')

@section('content')
    <div class="dash-wrap">
        <h1 style="text-transform: uppercase;">Prietaisų skydelis</h1>
        <div class="back-link">
            <a href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
        </div>
    </div>



    {{-- User's stories with their status --}}
    <div class="dashboard-container">
        <div class="dashboard-card">
            <h2>Mano istorijos</h2>
            @if ($myStories->isNotEmpty())
                @foreach ($myStories as $story)
                    <div class="card">
                        <div class="story-title-status">
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
                        </div>

                        {{-- Funds raised --}}
                        @if ($story->status === 'active' || $story->status === 'closed')
                            <p>Surinkta: <span>{{ $story->donations->sum('amount') }}</span> EUR</p>
                            <p>Tikslas: <span>{{ $story->goal_amount }}</span> EUR</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p><i>Jūs dar nesukūrėte kampanijos.</i></p>
            @endif
        </div>


        <div class="dashboard-card">
            <h2>Skirtos aukos</h2>

            @if ($myDonations->isNotEmpty())
                @foreach ($myDonations as $donation)
                    <div class="card">
                        <p>Paaukota <span>{{ $donation->amount }}</span> EUR kampanijai
                            <a href="{{ route('stories.show', $donation->story) }}">
                                {{ $donation->story->title }}
                            </a>
                        </p>
                    </div>
                @endforeach
            @else
                <p><i>Jūs dar nepaaukojote.</i></p>
            @endif
        </div>



        <div class="dashboard-card">
            {{-- Patinkančios kampanijos --}}
            <h2>Man patinkančios kampanijos</h2>
            @if ($myLikedStories->isNotEmpty())
                @foreach ($myLikedStories as $story)
                    <div class="card">
                        <a href="{{ route('stories.show', $story) }}">
                            {{ $story->title }}
                        </a>
                    </div>
                @endforeach
            @else
                <p><i>JUMS NIEKS NEPATINKA!</i></p>
            @endif
        </div>



        <div class="dashboard-card">
            {{-- Komentarai --}}
            <h2>Mano komentarai</h2>
            @if ($myComments->isNotEmpty())
                @foreach ($myComments as $comment)
                    <div class="card">
                        <p><a href="{{ route('stories.show', $comment->story) }}">
                                {{ $comment->story->title }}:
                            </a>"{{ $comment->content }}"
                        </p>
                    </div>
                @endforeach
            @else
                <p><i>Jūs dar nekomentavote.</i></p>
            @endif
        </div>
    </div>
@endsection
