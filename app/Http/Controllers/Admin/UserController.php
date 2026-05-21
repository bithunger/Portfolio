<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::orderByDesc('is_owner')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'user' => new User(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validated($request));

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->isOwner()) {
            return redirect()->route('admin.users.index')->withErrors('The owner account cannot be deleted.');
        }

        if ($request->user()->is($user)) {
            return redirect()->route('admin.users.index')->withErrors('You cannot delete your own account.');
        }

        if (User::count() <= 1) {
            return redirect()->route('admin.users.index')->withErrors('At least one admin user must remain.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $passwordRules = $user?->exists
            ? ['nullable', 'confirmed', Password::min(8)]
            : ['required', 'confirmed', Password::min(8)];

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'contact' => ['nullable', 'string', 'max:80'],
            'email' => [
                'required',
                'email:rfc',
                'max:160',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'password' => $passwordRules,
        ], [
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($user?->isOwner()) {
            $data['email_verified_at'] = $user->email_verified_at ?: now();
            $data['is_owner'] = true;
        } else {
            $data['email_verified_at'] = $request->boolean('email_verified') ? now() : null;
        }

        return $data;
    }
}
