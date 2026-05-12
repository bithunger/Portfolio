<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        return view('admin.skills.index', [
            'skills' => Skill::orderBy('category')->orderBy('display_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.skills.create', [
            'skill' => new Skill(['active' => true, 'proficiency' => 80]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Skill::create($this->validated($request));

        return redirect()->route('admin.skills.index')->with('status', 'Skill created.');
    }

    public function show(Skill $skill): RedirectResponse
    {
        return redirect()->route('admin.skills.edit', $skill);
    }

    public function edit(Skill $skill): View
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $skill->update($this->validated($request));

        return redirect()->route('admin.skills.index')->with('status', 'Skill updated.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')->with('status', 'Skill deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:120'],
            'proficiency' => ['required', 'integer', 'min:1', 'max:100'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['active'] = $request->boolean('active');
        $data['display_order'] = $data['display_order'] ?? 0;

        return $data;
    }
}
