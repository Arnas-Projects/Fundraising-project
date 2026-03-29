@extends('layouts.guest')

@section('title', 'Beaver\'s Fundraising | Paremkite prasmingas istorijas')
@section('body_class', 'auth-page auth-page--welcome')
@section('shell_class', 'auth-shell auth-shell--welcome')
@section('inner_class', 'auth-shell__inner auth-shell__inner--welcome')
@section('card_class', 'auth-shell__card auth-shell__card--welcome')

@section('content')
    <section class="welcome-landing">
        <div class="welcome-hero">
            <div class="welcome-nav">
                <a href="{{ route('welcome') }}" class="welcome-brand" aria-label="Beaver's Fundraising pradinis puslapis">
                    <img src="{{ asset('images/beaver-funding.png') }}" alt="Beaver's Fundraising logotipas">
                    <span>Beaver's Fundraising</span>
                </a>

                <div class="welcome-nav__actions">
                    <a href="{{ route('stories.index') }}" class="btn-secondary">Naršyti istorijas</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Prisijungti</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">Registruotis</a>
                    @endif
                </div>
            </div>

            <div class="welcome-hero__grid">
                <div class="welcome-hero__content">
                    <span class="welcome-pill">Bendruomenės finansuojamos istorijos</span>
                    <h1>Vienoje vietoje atraskite kampanijas, kurioms reikia realaus žmonių palaikymo.</h1>
                    <p>
                        Stebėkite aktyvias iniciatyvas, susipažinkite su jų istorijomis ir prisijunkite prie bendruomenės,
                        kuri renka paramą skaidriai, aiškiai ir su tikru tikslu.
                    </p>

                    <div class="welcome-hero__actions">
                        <a href="{{ route('stories.index') }}" class="btn-primary">Peržiūrėti kampanijas</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-secondary">Sukurti paskyrą</a>
                        @endif
                    </div>

                    <div class="welcome-stats">
                        <article class="welcome-stat">
                            <strong>{{ number_format($storyCount) }}</strong>
                            <span>aktyvios arba užbaigtos istorijos</span>
                        </article>
                        <article class="welcome-stat">
                            <strong>{{ number_format($raisedTotal, 0, '.', ' ') }} EUR</strong>
                            <span>bendra surinkta suma platformoje</span>
                        </article>
                        <article class="welcome-stat">
                            <strong>{{ $featuredStories->count() }}</strong>
                            <span>naujausios istorijos greitai peržiūrai</span>
                        </article>
                    </div>
                </div>

                <aside class="welcome-hero__panel">
                    <div class="welcome-hero__panel-card">
                        <span class="welcome-panel__label">Kodėl verta prisijungti</span>
                        <h2>Matykite pažangą, remkite tikslą ir dalinkitės savo iniciatyva.</h2>
                        <ul class="welcome-checklist">
                            <li>Greitai peržiūrėkite kampanijas ir jų progresą.</li>
                            <li>Prisijungę galėsite aukoti, sekti istorijas ir spausti patinka.</li>
                            <li>Registruoti nariai gali kurti savo lėšų rinkimo kampanijas.</li>
                        </ul>
                    </div>

                    <div class="welcome-hero__spotlight">
                        <span class="welcome-panel__label">Greitas maršrutas</span>
                        <p>Jei norite tik apsižvalgyti, pradėkite nuo istorijų sąrašo. Jei planuojate remti ar kurti kampaniją, prisijunkite arba užsiregistruokite.</p>
                        <div class="welcome-inline-actions">
                            <a href="{{ route('stories.index') }}">Istorijų sąrašas</a>
                            <a href="{{ route('login') }}">Prisijungimas</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <section class="welcome-section">
            <div class="welcome-section__heading">
                <span class="welcome-pill welcome-pill--soft">Kaip tai veikia</span>
                <h2>Aiški struktūra lankytojui nuo pirmo apsilankymo</h2>
            </div>

            <div class="welcome-feature-grid">
                <article class="welcome-feature-card">
                    <h3>Naršykite istorijas</h3>
                    <p>Peržiūrėkite kampanijas, filtrus, aprašymus ir kiek kiekviena istorija jau surinko.</p>
                </article>
                <article class="welcome-feature-card">
                    <h3>Prisijunkite veiksmams</h3>
                    <p>Sukurta taip, kad registruoti nariai galėtų remti, komentuoti ir įsitraukti be papildomų kliūčių.</p>
                </article>
                <article class="welcome-feature-card">
                    <h3>Kurti savo kampaniją</h3>
                    <p>Prisijungę galėsite pateikti savo istoriją ir rinkti lėšas savo tikslui ar bendruomenės iniciatyvai.</p>
                </article>
            </div>
        </section>

        <section class="welcome-section">
            <div class="welcome-section__heading welcome-section__heading--split">
                <div>
                    <span class="welcome-pill welcome-pill--soft">Istorijų peržiūra</span>
                    <h2>Naujausios kampanijos, kurias galite pradėti tyrinėti dabar</h2>
                </div>
                <a href="{{ route('stories.index') }}" class="welcome-section__link">Žiūrėti visas istorijas</a>
            </div>

            <div class="welcome-story-grid">
                @forelse ($featuredStories as $story)
                    @php
                        $raised = $story->total_donated ?? 0;
                        $progress = $story->goal_amount > 0 ? min(100, round(($raised / $story->goal_amount) * 100)) : 0;
                    @endphp

                    <article class="welcome-story-card">
                        @if ($story->main_image)
                            <img src="{{ asset('storage/' . $story->main_image) }}" alt="{{ $story->title }} pagrindinis vaizdas" class="welcome-story-card__image">
                        @else
                            <div class="welcome-story-card__placeholder">Be paveikslėlio</div>
                        @endif

                        <div class="welcome-story-card__body">
                            <div class="welcome-story-card__tags">
                                @foreach ($story->tags->take(3) as $tag)
                                    <span>#{{ $tag->slug }}</span>
                                @endforeach
                            </div>

                            <h3>{{ $story->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($story->short_description, 120) }}</p>

                            <div class="welcome-story-card__meta">
                                <strong>{{ number_format($raised, 0, '.', ' ') }} EUR</strong>
                                <span>iš {{ number_format($story->goal_amount, 0, '.', ' ') }} EUR</span>
                            </div>

                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progress }}%; padding: {{ $progress > 14 ? '0 10px' : '0' }};">
                                    {{ $progress }}%
                                </div>
                            </div>

                            <a href="{{ route('stories.show', $story) }}" class="welcome-story-card__link">Skaityti istoriją</a>
                        </div>
                    </article>
                @empty
                    <article class="welcome-story-card welcome-story-card--empty">
                        <div class="welcome-story-card__body">
                            <h3>Kampanijos netrukus pasirodys</h3>
                            <p>Kol kas dar nėra aktyvių istorijų rodymui šiame bloke, bet galite prisijungti arba užsiregistruoti ir pradėti pildyti platformą turiniu.</p>
                            <div class="welcome-hero__actions">
                                <a href="{{ route('login') }}" class="btn-secondary">Prisijungti</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-primary">Registruotis</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforelse
            </div>
        </section>

        <section class="welcome-cta">
            <div>
                <span class="welcome-pill welcome-pill--soft">Pasiruošę pradėti?</span>
                <h2>Prisijunkite prie bendruomenės arba pirmiausia apžiūrėkite kampanijas.</h2>
            </div>

            <div class="welcome-cta__actions">
                <a href="{{ route('stories.index') }}" class="btn-secondary">Naršyti istorijas</a>
                <a href="{{ route('login') }}" class="btn-secondary">Prisijungti</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Registruotis dabar</a>
                @endif
            </div>
        </section>
    </section>
@endsection
