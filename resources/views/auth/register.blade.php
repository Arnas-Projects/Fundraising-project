@section('title', 'Sukurti paskyrą')

<x-guest-layout>
    <section class="auth-panel auth-panel--register">
        <div class="auth-panel__header">
            <span class="auth-panel__eyebrow">Prisijunkite prie mūsų</span>
            <h1>{{ __('Sukurti paskyrą') }}</h1>
            <p>{{ __('Prisijunkite, kad galėtumėte dalintis istorijomis, remti kitų iniciatyvas ir sekti mūsų bendruomenės veiklą.') }}</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="auth-form__field">
                <x-input-label for="name" class="auth-label" :value="__('Vardas')" />
                <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="auth-error" />
            </div>

            <div class="auth-form__field">
                <x-input-label for="email" class="auth-label" :value="__('El. paštas')" />
                <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            <div class="auth-form__field auth-form__field-grid">
                <div>
                    <x-input-label for="password" class="auth-label" :value="__('Slaptažodis')" />
                    <x-text-input id="password" class="auth-input"
                                  type="password"
                                  name="password"
                                  required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="auth-error" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" class="auth-label" :value="__('Patvirtinti slaptažodį')" />
                    <x-text-input id="password_confirmation" class="auth-input"
                                  type="password"
                                  name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
                </div>
            </div>

            <div class="auth-form__actions auth-form__actions--split">
                <a class="auth-link auth-link--muted" href="{{ route('login') }}">
                    {{ __('Jau užsiregistravote? Prisijunkite') }}
                </a>

                <x-primary-button class="auth-submit">
                    {{ __('Užsiregistruoti') }}
                </x-primary-button>
            </div>
        </form>
    </section>
</x-guest-layout>
