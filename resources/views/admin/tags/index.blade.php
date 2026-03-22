@extends('main')

@section('content')
    {{-- TAGS MANAGER --}}
    <h1>Tagų valdymas</h1>
    <a href="{{ route('admin.tags.create') }}">Sukurti naują tagą</a>
    <br>
    <a href="{{ route('admin.index') }}">Grįžti į admin panelę</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pavadinimas</th>
                <th>Veiksmai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
                <tr>
                    {{-- Recalculating IDs list --}}
                    <td>{{ $loop->iteration }}</td> 
                    <td>{{ $tag->name }}</td>
                    <td>
                        <a href="{{ route('admin.tags.edit', $tag) }}">Redaguoti</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Ar tikrai norite ištrinti šį tagą?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Ištrinti</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection