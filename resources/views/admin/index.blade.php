@extends('main')

{{-- @section('content')
    <div class="blade-container">
        <h1>Admino panelė</h1>

        <nav>
            <ul>
                <li>
                    <a href="{{ route('stories.index') }}">Atgal į kampanijų sąrašą</a>
                </li>
            </ul>
        </nav>

        <h2>Laukiantys patvirtinimo</h2>
        @if ($pendingStories->isEmpty())
            <p>Nėra kampanijų, laukiančių patvirtinimo.</p>
        @else
            <ul>
                @foreach ($pendingStories as $story)
                    <li>
                        <strong>{{ $story->title }}</strong> - {{ $story->user->name }}
                        <form method="POST" action="{{ route('admin.approve', $story) }}" style="display:inline-block;">
                            @csrf
                            <button type="submit">Patvirtinti</button>
                        </form>
                        <form method="POST" action="{{ route('admin.delete', $story) }}" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Ištrinti</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection --}}


@section('content')
    <div class="wrapper2">
        <h1> Admino panelė</h1>

        @foreach ($stories as $story)
            <div class="card color3">
                <h3>{{ $story->title }}</h3>
                <p>Statusas: {{ $story->status }}</p>

                {{-- Approve --}}
                @if ($story->status !== 'active')
                    <form method="POST" action="{{ route('admin.approve', $story) }}">
                        @csrf
                        <button type="submit">Patvirtinti</button>
                    </form>
                @endif

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.delete', $story) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Ištrinti</button>
                </form>
            </div>
        @endforeach

        {{-- Pages --}}
        <div class="pagination">
            {{ $stories->links() }}
        </div>
    </div>
@endsection
