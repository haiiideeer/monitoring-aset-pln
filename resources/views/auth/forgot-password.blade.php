<x-guest-layout>
    <!-- Logo PLN (opsional, bisa dihapus jika tidak diinginkan) -->
    <div class="text-center mb-1">
        <img src="{{ asset('assets/images/foto.png') }}" alt="Logo PLN" class="mx-auto w-2">
    </div>

    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('Lupa kata sandi? Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Kirim Tautan Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
