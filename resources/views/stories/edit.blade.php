@extends('main')

@section('content')
    <h1>Redaguoti kampaniją</h1>

    <form method="POST" action="{{ route('stories.update', $story) }}">
        @csrf
        @method('PUT')

        <input type="text" name="title" placeholder="Pavadinimas" value="{{ $story->title }}">

        <textarea name="short_description" placeholder="Trumpas aprašymas">{{ $story->short_description }}</textarea>

        <textarea name="full_story" placeholder="Pilnas aprašymas">{{ $story->full_story }}</textarea>

        <input type="number" name="goal_amount" placeholder="Tikslo suma" value="{{ $story->goal_amount }}">

        <button type="submit">Išsaugoti</button>
    </form>
@endsection
