<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::orderBy('display_order')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.create', [
            'testimonial' => new Testimonial(['active' => true, 'featured' => false]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Testimonial::create($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial created.');
    }

    public function show(Testimonial $testimonial): RedirectResponse
    {
        return redirect()->route('admin.testimonials.edit', $testimonial);
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request, $testimonial));

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->deletePreviousLocalAvatar($testimonial);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial deleted.');
    }

    private function validated(Request $request, ?Testimonial $testimonial = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'title' => ['nullable', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:120'],
            'quote' => ['required', 'string', 'max:2500'],
            'avatar_file' => ['nullable', 'image', 'max:4096'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        unset($data['avatar_file']);

        $data['featured'] = $request->boolean('featured');
        $data['active'] = $request->boolean('active');
        $data['display_order'] = $data['display_order'] ?? 0;

        if ($avatarPath = $this->storeAvatar($request, $testimonial)) {
            $data['avatar_url'] = $avatarPath;
        }

        return $data;
    }

    private function storeAvatar(Request $request, ?Testimonial $testimonial = null): ?string
    {
        if (! $request->hasFile('avatar_file')) {
            return null;
        }

        $directory = public_path('uploads/testimonials');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file('avatar_file');
        $filename = 'avatar-'.Str::uuid().'.'.$file->extension();

        $file->move($directory, $filename);
        $this->deletePreviousLocalAvatar($testimonial);

        return '/uploads/testimonials/'.$filename;
    }

    private function deletePreviousLocalAvatar(?Testimonial $testimonial): void
    {
        if (! $testimonial?->avatar_url || ! Str::startsWith($testimonial->avatar_url, '/uploads/testimonials/')) {
            return;
        }

        $path = public_path(ltrim($testimonial->avatar_url, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }
}
