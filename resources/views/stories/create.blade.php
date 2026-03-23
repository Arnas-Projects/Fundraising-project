@extends('main')

@section('content')
    <div class="form-container">
        <h1>Sukurkite istoriją</h1>

        <form class="form-group" method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="text" name="title" value="{{ old('title') }}" placeholder="Pavadinimas">
            @error('title')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <textarea name="short_description" placeholder="Trumpas aprašymas">{{ old('short_description') }}</textarea>
            @error('short_description')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <textarea name="full_story" placeholder="Pilnas aprašymas">{{ old('full_story') }}</textarea>
            @error('full_story')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <input type="number" name="goal_amount" value="{{ old('goal_amount') }}" placeholder="Tikslo suma">
            @error('goal_amount')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <div>
                <label for="main_image">Pasirinkite pagrindinį paveikslėlį:</label>
                <input type="file" name="main_image">
            </div>

            <div>
                <label for="gallery_images">Pasirinkite galerijos paveikslėlius:</label>
                <input type="file" name="gallery_images[]" multiple>
            </div>

            <div class="tags-container">
                <p>Pasirinkite žymas:</p>
                @foreach ($tags as $tag)
                    <label>
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>

            <button type="submit">Sukurti</button>

        </form>

        <a class="form-back-link" href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
    </div>
@endsection
