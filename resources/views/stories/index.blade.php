<h1>Lėšų rinkimo kampanijos</h1>

<div style="width:400px; background:#eee; padding:20px; margin-bottom:40px; border-radius:5px; border:1px solid #ccc;">
    <a href="{{ route('stories.create') }}">Sukurti naują kampaniją</a>
</div>

@foreach ($stories as $story)
    <div style="margin-bottom:40px; border-bottom:1px solid #ccc; padding-bottom:20px;">

        <h2>
            <a href="{{ route('stories.show', $story) }}">
                {{ $story->title }}
            </a>
        </h2>

        @if ($story->main_image)
            <img src="{{ asset('storage/' . $story->main_image) }}" width="250">
        @endif

        <p>{{ $story->short_description }}</p>

        <p>Tikslas: {{ $story->goal_amount }} EUR</p>
        <p>Surinkta: {{ $story->donations->sum('amount') }}</p>

    </div>
@endforeach