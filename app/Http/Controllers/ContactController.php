<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('portfolio.contact', [
            'profile' => Profile::site(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ContactMessage::create($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'company' => ['nullable', 'string', 'max:160'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:3000'],
        ]));

        return back()->with('status', 'Message received. I will reply soon.');
    }
}
