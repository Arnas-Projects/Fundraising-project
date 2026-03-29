@section('title', 'Prisijungti')

<x-guest-layout>
    <section class="auth-panel auth-panel--login">
        <div class="auth-panel__header">
            <span class="auth-panel__eyebrow">Sveiki sugrįžę</span>
            <h1>{{ __('Prisijungti') }}</h1>
            <p>{{ __('Prisijunkite, kad galėtumėte valdyti savo lėšų rinkimo veiklą, remti istorijas ir sekti savo bendruomenės poveikį.') }}</p>
        </div>

        <x-auth-session-status class="auth-status" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="auth-form__field">
                <x-input-label for="email" class="auth-label" :value="__('El. paštas')" />
                <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            <div class="auth-form__field">
                <x-input-label for="password" class="auth-label" :value="__('Slaptažodis')" />
                <x-text-input id="password" class="auth-input"
                              type="password"
                              name="password"
                              required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            <div class="auth-form__meta">
                <label for="remember_me" class="auth-checkbox">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>{{ __('Prisiminti mane') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        {{ __('Pamiršote slaptažodį?') }}
                    </a>
                @endif
            </div>

            <div class="auth-form__actions">
                <x-primary-button class="auth-submit">
                    {{ __('Prisijungti') }}
                </x-primary-button>
            </div>
        </form>

        <p class="auth-panel__switch">
            {{ __('Neturite paskyros?') }}
            <a href="{{ route('register') }}" class="auth-link">{{ __('Užsiregistruokite čia') }}</a>

            <br>

            {{ __('Norėdami grįžti spauskite')}}
            <a href="{{ url('/stories') }}" class="auth-link">{{ __('Čia') }}</a>
        </p>
    </section>
</x-guest-layout>
