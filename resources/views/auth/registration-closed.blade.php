<x-guest-layout>
    <x-slot name="title">Registrations closed</x-slot>

    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="ToDo" class="h-16 mx-auto mb-3">
        <h1 class="text-4xl font-bold text-red-500 mb-2">ToDo</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-500 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Registrations are currently closed</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">New sign-ups have been temporarily disabled. Please check back later.</p>
    </div>

    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-red-500 hover:text-red-600 font-medium hover:underline">Log in</a>
        </p>
    </div>
</x-guest-layout>
