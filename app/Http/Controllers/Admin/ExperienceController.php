<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences.index', [
            'experiences' => Experience::orderBy('display_order')->orderByDesc('start_date')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.experiences.create', [
            'experience' => new Experience(['active' => true, 'is_current' => false]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Experience::create($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Experience created.');
    }

    public function show(Experience $experience): RedirectResponse
    {
        return redirect()->route('admin.experiences.edit', $experience);
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->validated($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Experience updated.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('status', 'Experience deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'max:140'],
            'company' => ['required', 'string', 'max:140'],
            'location' => ['nullable', 'string', 'max:120'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'summary' => ['nullable', 'string', 'max:3000'],
            'highlights' => ['nullable', 'string', 'max:3000'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_current'] = $request->boolean('is_current');
        $data['active'] = $request->boolean('active');
        $data['display_order'] = $data['display_order'] ?? 0;
        $data['highlights'] = $this->lines($data['highlights'] ?? '');

        if ($data['is_current']) {
            $data['end_date'] = null;
        }

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
