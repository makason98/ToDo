<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'all');
        $sort = $request->input('sort', 'newest');

        $query = User::withCount('tasks');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($status === 'unverified') {
            $query->whereNull('email_verified_at');
        } elseif ($status === 'admin') {
            $query->whereNotNull('admin_role');
        }

        $query->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'status', 'sort'));
    }

    public function show(User $user): View
    {
        $user->loadCount(['tasks', 'labels']);
        $completedTasksCount = $user->tasks()->where('completed', true)->count();
        $activeTasksCount = $user->tasks()->where('completed', false)->count();

        return view('admin.users.show', compact('user', 'completedTasksCount', 'activeTasksCount'));
    }

    public function edit(Request $request, User $user): View
    {
        abort_unless($request->user()->canEditUser($user), 403, 'You cannot edit this user.');

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $editor = $request->user();
        abort_unless($editor->canEditUser($user), 403, 'You cannot edit this user.');

        $assignableRoles = array_keys($editor->assignableRoles());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'verified' => ['nullable', 'boolean'],
            'admin_role' => ['nullable', Rule::in($assignableRoles)],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->admin_role = $validated['admin_role'] ?: null;

        $shouldBeVerified = $request->boolean('verified');
        if ($shouldBeVerified && ! $user->email_verified_at) {
            $user->email_verified_at = now();
        } elseif (! $shouldBeVerified && $user->email_verified_at) {
            $user->email_verified_at = null;
        }

        $user->save();

        ActivityLog::log(
            'admin.user.update',
            User::class,
            $user->id,
            $editor->name . " edited user '{$user->name}' (#{$user->id})"
        );

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'User updated successfully.');
    }
}
