<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        return view('admin.messages.index', [
            'messages' => ContactMessage::latest()->get(),
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        if ($contactMessage->read_at === null) {
            $contactMessage->update(['read_at' => now()]);
        }

        return view('admin.messages.show', compact('contactMessage'));
    }

    public function markRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update(['read_at' => now()]);

        return back()->with('status', 'Message marked as read.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.messages.index')->with('status', 'Message deleted.');
    }
}
