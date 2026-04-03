@extends('main')

@section('title', 'Sukurti tagą')

@section('content')
    <div class="tag-form-container">
        <h1>Sukurti naują tagą</h1>

        @if ($errors->any())
            <div class="tag-form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>KLAIDA: {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.tags.store') }}">
            @csrf
            <div class="tag-form-group">
                <label for="name">Pavadinimas:</label>
                <input type="text" name="name" placeholder="Įveskite tago pavadinimą" required>
            </div>
            <div class="tag-form-actions">
                <button type="submit">Sukurti</button>
                <a href="{{ route('admin.tags.index') }}">Grįžti į tagų sąrašą</a>
            </div>
        </form>
    </div>
@endsection
