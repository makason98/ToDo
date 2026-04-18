<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $values = [
            'registration_open' => Setting::bool('registration_open', true),
            'maintenance_banner' => Setting::get('maintenance_banner', ''),
        ];

        return view('admin.settings.index', compact('values'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_banner' => ['nullable', 'string', 'max:500'],
        ]);

        Setting::set('registration_open', $request->boolean('registration_open') ? '1' : '0');
        Setting::set('maintenance_banner', $validated['maintenance_banner'] ?? '');

        ActivityLog::log(
            'admin.settings.update',
            Setting::class,
            null,
            $request->user()->name . ' updated platform settings'
        );

        return back()->with('status', 'Settings saved.');
    }
}
