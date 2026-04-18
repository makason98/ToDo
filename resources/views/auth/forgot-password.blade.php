<x-guest-layout>
    <x-slot name="title">Forgot Password</x-slot>

    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="ToDo" class="h-16 mx-auto mb-3">
        <h1 class="text-4xl font-bold text-red-500 mb-2">ToDo</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Organize your tasks, never forget what matters.</p>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-8">Forgot password?</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Enter your email and we'll send you a reset link.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg px-4 py-3 text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Enter your email..." />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <button type="submit"
            class="w-full py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg text-sm transition-colors duration-200">
            Send reset link
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Remember your password?
            <a href="{{ route('login') }}" class="text-red-500 hover:text-red-600 font-medium hover:underline">Log in</a>
        </p>
    </div>
</x-guest-layout>
