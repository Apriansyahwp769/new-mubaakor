<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- 🔥 CUSTOM HEADER UNTUK LOGIN ADMIN 🔥 --}}
    <div class="mb-4 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-indigo-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3v-1m18-3H11" />
        </svg>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
            LOGIN ADMIN MUBAAKOR
        </h1>
        <p class="text-sm text-gray-500 mt-1">Akses Terbatas. Masukkan kredensial Anda.</p>
    </div>
    {{-- 🔥 AKHIR CUSTOM HEADER 🔥 --}}

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            {{-- Mengubah gaya tombol login menjadi lebih menonjol --}}
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg font-semibold shadow-lg hover:bg-indigo-700 transition duration-150">
                {{ __('MASUK KE DASHBOARD') }}
            </button>
        </div>
    </form>
</x-guest-layout>