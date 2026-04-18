{{-- Usage: wrap a button/link that should trigger a confirm dialog --}}
{{-- The parent must have x-data="{ confirmOpen: false, confirmAction: null }" --}}

<div x-show="confirmOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40" @click="confirmOpen = false"></div>

    <!-- Dialog -->
    <div x-show="confirmOpen" x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-sm w-full p-6 z-10">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100" x-text="confirmTitle || 'Are you sure?'"></h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" x-text="confirmMessage || 'This action cannot be undone.'"></p>

        <div class="flex justify-end gap-3 mt-6">
            <button @click="confirmOpen = false"
                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                Cancel
            </button>
            <button @click="if(confirmAction) confirmAction.submit(); confirmOpen = false"
                class="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                Delete
            </button>
        </div>
    </div>
</div>
