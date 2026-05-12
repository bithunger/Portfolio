<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationEntryController extends Controller
{
    public function index(): View
    {
        return view('admin.education.index', [
            'educationEntries' => EducationEntry::orderBy('display_order')
                ->orderByDesc('end_year')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.education.create', [
            'educationEntry' => new EducationEntry(['active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        EducationEntry::create($this->validated($request));

        return redirect()->route('admin.education.index')->with('status', 'Education entry created.');
    }

    public function edit(EducationEntry $education): View
    {
        return view('admin.education.edit', [
            'educationEntry' => $education,
        ]);
    }

    public function update(Request $request, EducationEntry $education): RedirectResponse
    {
        $education->update($this->validated($request));

        return redirect()->route('admin.education.index')->with('status', 'Education entry updated.');
    }

    public function destroy(EducationEntry $education): RedirectResponse
    {
        $education->delete();

        return redirect()->route('admin.education.index')->with('status', 'Education entry deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'degree' => ['required', 'string', 'max:180'],
            'institution' => ['required', 'string', 'max:180'],
            'location' => ['nullable', 'string', 'max:140'],
            'start_year' => ['nullable', 'integer', 'min:1950', 'max:2100'],
            'end_year' => ['nullable', 'integer', 'min:1950', 'max:2100', 'gte:start_year'],
            'summary' => ['nullable', 'string', 'max:3000'],
            'highlights' => ['nullable', 'string', 'max:3000'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['highlights'] = $this->lines($data['highlights'] ?? '');
        $data['display_order'] = $data['display_order'] ?? 0;
        $data['active'] = $request->boolean('active');

        return $data;
    }

    private function lines(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
