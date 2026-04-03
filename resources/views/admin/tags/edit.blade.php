@extends('main')

@section('title', 'Redaguoti tagą')

@section('content')
    <div class="tag-form-container">
        <h1>Redaguoti tagą</h1>

        @if ($errors->any())
            <div class="tag-form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>KLAIDA: {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.tags.update', $tag) }}">
            @csrf
            @method('PUT')
            <div class="tag-form-group">
                <label for="name">Pavadinimas:</label>
                <input type="text" name="name" value="{{ $tag->name }}" placeholder="Įveskite tago pavadinimą" required>
            </div>
            <div class="tag-form-actions">
                <button type="submit">Atnaujinti</button>
                <a href="{{ route('admin.tags.index') }}">Grįžti į tagų sąrašą</a>
            </div>
        </form>
    </div>
@endsection
