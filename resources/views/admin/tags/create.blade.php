@extends('main')

@section('content')
    <h1>Sukurti naują tagą</h1>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tags.store') }}">
        @csrf
        <div>
            <label for="name">Pavadinimas:</label>
            <input type="text" name="name" placeholder="Įveskite tago pavadinimą" required>
        </div>
        <button type="submit">Sukurti</button>
        <a href="{{ route('admin.tags.index') }}">Grįžti į tagų sąrašą</a>
    </form>
@endsection
