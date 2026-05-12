<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::orderBy('display_order')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new Project(['published' => true, 'featured' => false]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Project::create($this->validated($request));

        return redirect()->route('admin.projects.index')->with('status', 'Project created.');
    }

    public function show(Project $project): RedirectResponse
    {
        return redirect()->route('admin.projects.edit', $project);
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request, $project));

        return redirect()->route('admin.projects.index')->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->deletePreviousLocalImage($project);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project deleted.');
    }

    private function validated(Request $request, ?Project $project = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:180'],
            'summary' => ['required', 'string', 'max:260'],
            'description' => ['nullable', 'string', 'max:6000'],
            'project_image_file' => ['nullable', 'image', 'max:5120'],
            'client' => ['nullable', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'tech_stack' => [
                'nullable',
                'string',
                'max:2000',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (count($this->lines((string) $value)) > 4) {
                        $fail('Tech stack can include up to 4 items.');
                    }
                },
            ],
            'live_url' => ['nullable', 'url', 'max:500'],
            'repo_url' => ['nullable', 'url', 'max:500'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($data['project_image_file']);

        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? '') ?: $data['title'], $project);
        $data['tech_stack'] = $this->lines($data['tech_stack'] ?? '');
        $data['featured'] = $request->boolean('featured');
        $data['published'] = $request->boolean('published');
        $data['display_order'] = $data['display_order'] ?? 0;

        if ($imagePath = $this->storeProjectImage($request, $project)) {
            $data['image_url'] = $imagePath;
        }

        return $data;
    }

    private function uniqueSlug(string $value, ?Project $project = null): string
    {
        $base = Str::slug($value) ?: 'project';
        $slug = $base;
        $count = 2;

        while (Project::where('slug', $slug)
            ->when($project, fn ($query) => $query->whereKeyNot($project->getKey()))
            ->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    private function lines(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    private function storeProjectImage(Request $request, ?Project $project = null): ?string
    {
        if (! $request->hasFile('project_image_file')) {
            return null;
        }

        $directory = public_path('uploads/projects');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file('project_image_file');
        $filename = 'project-'.Str::uuid().'.'.$file->extension();

        $file->move($directory, $filename);
        $this->deletePreviousLocalImage($project);

        return '/uploads/projects/'.$filename;
    }

    private function deletePreviousLocalImage(?Project $project): void
    {
        if (! $project?->image_url || ! Str::startsWith($project->image_url, '/uploads/projects/')) {
            return;
        }

        $path = public_path(ltrim($project->image_url, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }
}
