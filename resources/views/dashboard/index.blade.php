@extends('main')

@section('content')
    <h1>Prietaisų skydelis</h1>

    <h2>Mano istorijos</h2>
    <ul>
        @foreach ($myStories as $story)
            <div>
                <a href="{{ route('stories.show', $story) }}">
                    {{ $story->title }}
                </a>
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
