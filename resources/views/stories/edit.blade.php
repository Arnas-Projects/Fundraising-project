@extends('main')

@section('content')
    <div class="form-container">
        <h1>Redaguoti kampaniją</h1>

        <form class="form-group" method="POST" action="{{ route('stories.update', $story) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="title">Pavadinimas:</label>
            <input type="text" name="title" placeholder="Pavadinimas" value="{{ $story->title }}">
            @error('title')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="short_description">Trumpas aprašymas:</label>
            <textarea name="short_description" placeholder="Trumpas aprašymas">{{ $story->short_description }}</textarea>
            @error('short_description')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="full_story">Pilnas aprašymas:</label>
            <textarea name="full_story" placeholder="Pilnas aprašymas">{{ $story->full_story }}</textarea>
            @error('full_story')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="goal_amount">Tikslo suma:</label>
            <input type="number" name="goal_amount" placeholder="Tikslo suma" value="{{ $story->goal_amount }}">
            @error('goal_amount')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="main_image">Pagrindinis paveikslėlis:</label>
            <input type="file" name="main_image">
            @error('main_image')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="gallery_images">Galerijos paveikslėliai:</label>
            <input type="file" name="gallery_images[]" multiple>
            @error('gallery_images')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <div class="tags-container">
                <p>Pasirinkite žymas:</p>
                @foreach ($tags as $tag)
                    <label>
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                            {{ $story->tags->contains($tag->id) ? 'checked' : '' }}>
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>

            <br>

            <label for="new_tags">Naujos žymos {{ __('(pasirenkama)') }}:</label>
            <input type="text" name="new_tags" value="{{ old('new_tags') }}"
                placeholder="Naujos žymos, atskirtos kableliais">
            @error('new_tags')
                <p class="message message-error">{{ $message }}</p>
            @enderror


            <button type="submit">Išsaugoti</button>
        </form>
        <a class="form-back-link" href="{{ route('stories.show', $story) }}">Grįžti</a>
    </div>
@endsection
