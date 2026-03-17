<h1>Lėšų rinkimo kampanijos</h1>

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