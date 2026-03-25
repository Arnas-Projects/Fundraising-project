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

        <div class="filter-container">
            <form method="GET" action="{{ route('admin.index') }}">
                <label for="status">Filtruoti pagal statusą:</label>
                <select name="status" id="status" onchange="this.form.submit()">
                    <option value="">Visi statusai</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Laukia patvirtinimo</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktyvūs</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Atmesti</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Uždaryti</option>
                </select>
            </form>
        </div>

        {{-- Go to tags management --}}
        <div>
            <a href="{{ route('admin.tags.index') }}">Tagų valdymas</a>

            <div>
                <span>Laukiantys patvirtinimo: <strong>{{ $pendingCount }}</strong></span>
                <span>Aktyvūs: <strong>{{ $activeCount }}</strong></span>
                <span>Atmesti: <strong>{{ $rejectedCount }}</strong></span>
                <span>Uždaryti: <strong>{{ $closedCount }}</strong></span>
            </div>
        </div>

        @foreach ($stories as $story)
            <div class="card color3">
                <h3>{{ $story->title }}</h3>
                <p>Statusas:
                    @if ($story->status === 'active')
                        <span class="status-active">aktyvi</span>
                    @elseif ($story->status === 'pending')
                        <span class="status-pending">laukia patvirtinimo</span>
                    @elseif ($story->status === 'closed')
                        <span class="status-closed">uždaryta</span>
                    @elseif ($story->status === 'rejected')
                        <span class="status-rejected">atmesta</span>
                    @else
                        <span>{{ $story->status }}</span>
                    @endif
                </p>

                {{-- Open campaign content before approving --}}
                @if (
                    $story->status === 'pending' ||
                        $story->status === 'active' ||
                        $story->status === 'closed' ||
                        $story->status === 'rejected')
                    <a href="{{ route('stories.show', $story) }}" data-text="Peržiūrėti kampaniją">Peržiūrėti kampaniją</a>
                @endif

                {{-- Approve --}}
                {{-- @if ($story->status !== 'active')
                    <form method="POST" action="{{ route('admin.approve', $story) }}">
                        @csrf
                        <button type="submit">Patvirtinti</button>
                    </form>
                @endif --}}

                {{-- Approve, if status is 'pending' --}}
                @if ($story->status === 'pending')
                    <form method="POST" action="{{ route('admin.approve', $story) }}">
                        @csrf
                        <button type="submit">Patvirtinti</button>
                    </form>
                @endif

                {{-- Reject --}}
                @if ($story->status === 'pending')
                    <form method="POST" action="{{ route('admin.reject', $story) }}">
                        @csrf
                        <button type="submit">Atmesti</button>
                    </form>
                @endif

                {{-- Delete --}}
                @if ($story->status === 'active' || $story->status === 'closed')
                    <form method="POST" action="{{ route('admin.delete', $story) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Ištrinti</button>
                    </form>
                @endif


            </div>
        @endforeach

        {{-- Pages --}}
        <div class="pagination">
            {{ $stories->links() }}
        </div>
    </div>
@endsection
