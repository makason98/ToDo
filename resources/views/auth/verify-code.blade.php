<x-guest-layout>
    <x-slot name="title">Verify Email</x-slot>

    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="ToDo" class="h-16 mx-auto mb-3">
        <h1 class="text-4xl font-bold text-red-500 mb-2">ToDo</h1>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-8">Verify your email</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">We sent a 6-digit code to <strong>{{ auth()->user()->email }}</strong></p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg px-4 py-3 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.code.verify') }}" class="space-y-4">
        @csrf

        <div>
            <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Verification Code</label>
            <input id="code" type="text" name="code" required autofocus
                maxlength="6" inputmode="numeric" pattern="[0-9]*"
                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg text-center text-lg tracking-widest font-semibold focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="000000" />
            <x-input-error :messages="$errors->get('code')" class="mt-1" />
        </div>

        <button type="submit"
            class="w-full py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg text-sm transition-colors duration-200">
            Verify
        </button>
    </form>

    <div class="mt-6 text-center">
        <form method="POST" action="{{ route('verification.code.resend') }}">
            @csrf
            <button type="submit" class="text-sm text-red-500 hover:text-red-600 hover:underline">
                Resend code
            </button>
        </form>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-400 dark:text-gray-500 hover:text-gray-600 hover:underline">
                Log out
            </button>
        </form>
    </div>
</x-guest-layout>
