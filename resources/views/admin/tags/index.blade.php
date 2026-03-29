@extends('main')

@section('title', 'Tagų valdiklis')

@section('content')
    {{-- TAGS MANAGER --}}
    <div class="tags-manager-container">
        <div class="tags-manager-header">
            <h1>Tagų valdiklis</h1>

            <div class="btn-group">
                <a class="btn-primary" href="{{ route('admin.tags.create') }}">Sukurti naują tagą</a>
                <br>
                <a class="btn-secondary" href="{{ route('admin.index') }}">Grįžti į admin valdiklį</a>
            </div>
        </div>

        {{-- Tags table --}}
        <table class="tags-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pavadinimas</th>

                    <th>Kiekis 
                        <br>
                        <i>{{__('(bendras)')}}</i>
                    </th>

                    <th>Kiekis 
                        <br>
                        <i>{{__('(aktyvios istorijos)')}}</i>
                    </th>

                    <th> Kiekis
                        <br>
                        <i>{{__('(uždarytos istorijos)')}}</i>
                    </th>

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
                        
                        {{-- Tag's stories count --}}
                        <td>{{ $tagsAmount->where('id', $tag->id)->first()->stories_count }}</td>

                        <td>{{ $activeTagsAmount->where('id', $tag->id)->first()->stories_count }}</td>

                        <td>{{ $closedTagsAmount->where('id', $tag->id)->first()->stories_count }}</td>

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
