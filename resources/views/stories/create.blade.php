@extends('main')

@section('title', 'Sukurti istoriją')

@section('content')
    <div class="form-container">
        <h1>Sukurkite istoriją</h1>

        <form class="form-group" method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data">
            @csrf

            <label for="title">Pavadinimas {{ __('(privaloma)') }}:</label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Pavadinimas">
            @error('title')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="short_description">Trumpas aprašymas {{ __('(privaloma)') }}:</label>
            <textarea name="short_description" placeholder="Trumpas aprašymas">{{ old('short_description') }}</textarea>
            @error('short_description')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="full_story">Pilnas aprašymas {{ __('(privaloma)') }}:</label>
            <textarea name="full_story" placeholder="Pilnas aprašymas">{{ old('full_story') }}</textarea>
            @error('full_story')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <label for="goal_amount">Tikslo suma {{ __('(privaloma)') }}:</label>
            <input type="number" name="goal_amount" value="{{ old('goal_amount') }}" placeholder="Tikslo suma">
            @error('goal_amount')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <div>
                <label for="main_image">Pasirinkite pagrindinį paveikslėlį {{ __('(privaloma)') }}:</label>
                <input type="file" name="main_image">
            </div>
            @error('main_image')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <div>
                <label for="gallery_images">Pasirinkite galerijos paveikslėlius {{ __('(neprivaloma)') }}:</label>
                <input type="file" name="gallery_images[]" multiple>
            </div>
            @error('gallery_images.*')
                <p class="message message-error">{{ $message }}</p>
            @enderror

            <div class="tags-container">
                <label>Pasirinkite žymas {{ __('(neprivaloma)') }}:</label>
                @foreach ($tags as $tag)
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
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
            <button type="submit">Sukurti</button>

        </form>

        <a class="form-back-link" href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
    </div>
@endsection
