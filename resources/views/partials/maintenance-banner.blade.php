@php
    $banner = trim((string) \App\Models\Setting::get('maintenance_banner', ''));
@endphp

@if($banner !== '')
    <div class="bg-yellow-100 dark:bg-yellow-900/30 border-b border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5 19h14a2 2 0 001.84-2.75L13.74 4a2 2 0 00-3.5 0L3.16 16.25A2 2 0 005 19z"/></svg>
            <span>{{ $banner }}</span>
        </div>
    </div>
@endif
