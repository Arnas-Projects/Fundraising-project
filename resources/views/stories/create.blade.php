@extends('main')

@section('content')
    <div class="form-container">
        <h1>Sukurkite istoriją</h1>

        <form class="form-group" method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="text" name="title" placeholder="Pavadinimas">

            <textarea name="short_description" placeholder="Trumpas aprašymas"></textarea>

            <textarea name="full_story" placeholder="Pilnas aprašymas"></textarea>

            <input type="number" name="goal_amount" placeholder="Tikslo suma">

            <input type="file" name="main_image">

            <div class="tags-container">
                <p>Pasirinkite žymas:</p>
                @foreach ($tags as $tag)
                    <label>
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}">
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>

            <button type="submit">Sukurti</button>

        </form>

        <a class="form-back-link" href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
    </div>
@endsection
