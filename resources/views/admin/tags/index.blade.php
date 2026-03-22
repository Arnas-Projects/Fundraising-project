@extends('main')

@section('content')
    {{-- TAGS MANAGER --}}
    <div class="tags-manager-container">
        <div class="tags-manager-header">
            <h1>Tagų valdymas</h1>

            <div class="btn-group">
                <a class="btn-primary" href="{{ route('admin.tags.create') }}">Sukurti naują tagą</a>
                <br>
                <a class="btn-secondary" href="{{ route('admin.index') }}">Grįžti į admin panelę</a>
            </div>
        </div>

        {{-- Tags table --}}
        <table class="tags-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tags as $tag)
                    <tr>
                        {{-- Recalculating IDs list --}}
                        <td>{{ $loop->iteration }}</td>

                        {{-- <td>{{ $tag->name }}</td> --}}

                        {{-- Tag's name as slug --}}
                        <td>{{ $tag->slug }}</td>
                        <td class="actions-column">
                            <div class="tag-actions">
                                <a class="btn-edit" href="{{ route('admin.tags.edit', $tag) }}">Redaguoti</a>
                                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST"
                                    style="display:inline-block;"
                                    onsubmit="return confirm('Ar tikrai norite ištrinti šį tagą?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-delete" type="submit">Ištrinti</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
