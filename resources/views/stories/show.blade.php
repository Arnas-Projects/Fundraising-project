<h1>{{ $story->title }}</h1>

@if ($story->main_image)
    <img src="{{ asset('storage/' . $story->main_image) }}" width="400">
@endif

<p>{{ $story->short_description }}</p>

<hr>

<p>{{ $story->full_story }}</p>

<h3>Surinkta: {{ $story->goal_amount }} EUR</h3>