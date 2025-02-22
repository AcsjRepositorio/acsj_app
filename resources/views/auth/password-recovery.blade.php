<x-guest-layout>
    <form method="POST" action="{{ route('password.recovery.submit') }}">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Código de Recuperação -->
        <div class="mt-4">
            <x-input-label for="recovery_code" :value="__('Código de Recuperação')" />
            <x-text-input id="recovery_code" type="text" name="recovery_code" required />
            <x-input-error :messages="$errors->get('recovery_code')" class="mt-2" />
        </div>

        <!-- Nova Senha -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Nova Senha')" />
            <x-text-input id="password" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmação da Nova Senha -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Recuperar Senha') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
