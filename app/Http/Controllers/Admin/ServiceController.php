<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::orderBy('display_order')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create', [
            'service' => new Service(['active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Service::create($this->validated($request));

        return redirect()->route('admin.services.index')->with('status', 'Service created.');
    }

    public function show(Service $service): RedirectResponse
    {
        return redirect()->route('admin.services.edit', $service);
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validated($request));

        return redirect()->route('admin.services.index')->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'icon' => ['nullable', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:2000'],
            'deliverables' => [
                'nullable',
                'string',
                'max:2000',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (count($this->lines((string) $value)) > 4) {
                        $fail('Deliverables can include up to 4 items.');
                    }
                },
            ],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['deliverables'] = $this->lines($data['deliverables'] ?? '');
        $data['active'] = $request->boolean('active');
        $data['display_order'] = $data['display_order'] ?? 0;

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
