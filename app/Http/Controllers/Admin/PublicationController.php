<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        return view('admin.publications.index', [
            'publications' => Publication::orderBy('display_order')
                ->orderByDesc('year')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.publications.create', [
            'publication' => new Publication(['active' => true, 'icon' => 'RP']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Publication::create($this->validated($request));

        return redirect()->route('admin.publications.index')->with('status', 'Publication created.');
    }

    public function edit(Publication $publication): View
    {
        return view('admin.publications.edit', compact('publication'));
    }

    public function update(Request $request, Publication $publication): RedirectResponse
    {
        $publication->update($this->validated($request));

        return redirect()->route('admin.publications.index')->with('status', 'Publication updated.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        $publication->delete();

        return redirect()->route('admin.publications.index')->with('status', 'Publication deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:220'],
            'year' => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'journal_name' => ['nullable', 'string', 'max:180'],
            'publisher' => ['nullable', 'string', 'max:180'],
            'article_url' => ['nullable', 'url', 'max:500'],
            'icon' => ['nullable', 'string', 'max:20'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['display_order'] = $data['display_order'] ?? 0;
        $data['active'] = $request->boolean('active');

        return $data;
    }
}
