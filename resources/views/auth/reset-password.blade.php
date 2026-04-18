<x-guest-layout>
    <x-slot name="title">Reset Password</x-slot>

    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="ToDo" class="h-16 mx-auto mb-3">
        <h1 class="text-4xl font-bold text-red-500 mb-2">ToDo</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Organize your tasks, never forget what matters.</p>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-8">Set new password</h2>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Enter your email..." />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Enter new password..." />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-3 py-2.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Confirm new password..." />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit"
            class="w-full py-3 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg text-sm transition-colors duration-200">
            Reset Password
        </button>
    </form>
</x-guest-layout>
