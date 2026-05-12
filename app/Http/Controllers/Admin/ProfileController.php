<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'profile' => Profile::site(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = Profile::site();

        $data = $request->validate([
            'owner_name' => ['required', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:180'],
            'tagline' => ['nullable', 'string', 'max:220'],
            'hero_panel_label' => ['nullable', 'string', 'max:120'],
            'hero_panel_text' => ['nullable', 'string', 'max:200'],
            'bio' => ['nullable', 'string', 'max:4000'],
            'portrait_image' => ['nullable', 'image', 'max:5120'],
            'portrait_crop' => ['nullable', 'string'],
            'resume_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'frontend_logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'backend_logo_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'favicon_file' => ['nullable', 'file', 'mimes:ico,png,jpg,jpeg,webp,svg', 'max:1024'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:80'],
            'location' => ['nullable', 'string', 'max:120'],
            'availability' => ['nullable', 'string', 'max:120'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'seo_description' => ['nullable', 'string', 'max:300'],
            'github_url' => ['nullable', 'url', 'max:500'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'twitter_url' => ['nullable', 'url', 'max:500'],
            'dribbble_url' => ['nullable', 'url', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'whatsapp_url' => ['nullable', 'string', 'max:500'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'accent_color' => ['required', 'string', 'max:20'],
            'frontend_background_color' => ['nullable', 'string', 'max:20'],
            'backend_background_color' => ['nullable', 'string', 'max:20'],
            'font_family' => ['nullable', Rule::in(array_keys(Profile::fontOptions()))],
        ]);

        unset(
            $data['portrait_image'],
            $data['portrait_crop'],
            $data['resume_file'],
            $data['frontend_logo_file'],
            $data['backend_logo_file'],
            $data['favicon_file'],
        );

        if ($portraitPath = $this->storePortrait($request, $profile)) {
            $data['portrait_url'] = $portraitPath;
        }

        if ($resumePath = $this->storeResume($request, $profile)) {
            $data['resume_url'] = $resumePath;
        }

        if ($frontendLogoPath = $this->storeBrandAsset($request, $profile, 'frontend_logo_file', 'frontend-logo', 'frontend_logo_url')) {
            $data['frontend_logo_url'] = $frontendLogoPath;
        }

        if ($backendLogoPath = $this->storeBrandAsset($request, $profile, 'backend_logo_file', 'backend-logo', 'backend_logo_url')) {
            $data['backend_logo_url'] = $backendLogoPath;
        }

        if ($faviconPath = $this->storeBrandAsset($request, $profile, 'favicon_file', 'favicon', 'favicon_url')) {
            $data['favicon_url'] = $faviconPath;
        }

        $data['frontend_background_color'] = ($data['frontend_background_color'] ?? null) ?: '#f7f8f6';
        $data['backend_background_color'] = ($data['backend_background_color'] ?? null) ?: '#f7f8f6';
        $data['font_family'] = ($data['font_family'] ?? null) ?: 'elegant';

        $profile->update($data);

        return redirect()->route('admin.profile.edit')->with('status', 'Profile updated.');
    }

    private function storePortrait(Request $request, Profile $profile): ?string
    {
        $directory = public_path('uploads/profiles');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if ($request->filled('portrait_crop')) {
            return $this->storeCroppedPortrait($request->string('portrait_crop')->toString(), $directory, $profile);
        }

        if (! $request->hasFile('portrait_image')) {
            return null;
        }

        $file = $request->file('portrait_image');
        $filename = 'portrait-'.Str::uuid().'.'.$file->extension();

        $file->move($directory, $filename);
        $this->deletePreviousLocalPortrait($profile);

        return '/uploads/profiles/'.$filename;
    }

    private function storeCroppedPortrait(string $dataUrl, string $directory, Profile $profile): string
    {
        if (! preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $dataUrl, $matches)) {
            throw ValidationException::withMessages([
                'portrait_image' => 'The cropped portrait image is invalid.',
            ]);
        }

        $extension = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
        $encodedImage = str_replace(' ', '+', substr($dataUrl, strpos($dataUrl, ',') + 1));
        $image = base64_decode($encodedImage, true);

        if ($image === false) {
            throw ValidationException::withMessages([
                'portrait_image' => 'The cropped portrait image could not be saved.',
            ]);
        }

        $filename = 'portrait-'.Str::uuid().'.'.$extension;
        file_put_contents($directory.DIRECTORY_SEPARATOR.$filename, $image);
        $this->deletePreviousLocalPortrait($profile);

        return '/uploads/profiles/'.$filename;
    }

    private function deletePreviousLocalPortrait(Profile $profile): void
    {
        if (! $profile->portrait_url || ! Str::startsWith($profile->portrait_url, '/uploads/profiles/')) {
            return;
        }

        $path = public_path(ltrim($profile->portrait_url, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }

    private function storeResume(Request $request, Profile $profile): ?string
    {
        if (! $request->hasFile('resume_file')) {
            return null;
        }

        $directory = public_path('uploads/resumes');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file('resume_file');
        $filename = 'resume-'.Str::uuid().'.'.$file->extension();

        $file->move($directory, $filename);
        $this->deletePreviousLocalResume($profile);

        return '/uploads/resumes/'.$filename;
    }

    private function deletePreviousLocalResume(Profile $profile): void
    {
        if (! $profile->resume_url || ! Str::startsWith($profile->resume_url, '/uploads/resumes/')) {
            return;
        }

        $path = public_path(ltrim($profile->resume_url, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }

    private function storeBrandAsset(Request $request, Profile $profile, string $input, string $prefix, string $attribute): ?string
    {
        if (! $request->hasFile($input)) {
            return null;
        }

        $directory = public_path('uploads/branding');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = $request->file($input);
        $filename = $prefix.'-'.Str::uuid().'.'.$file->extension();

        $file->move($directory, $filename);
        $this->deletePreviousLocalBrandAsset($profile, $attribute);

        return '/uploads/branding/'.$filename;
    }

    private function deletePreviousLocalBrandAsset(Profile $profile, string $attribute): void
    {
        $pathValue = $profile->{$attribute};

        if (! $pathValue || ! Str::startsWith($pathValue, '/uploads/branding/')) {
            return;
        }

        $path = public_path(ltrim($pathValue, '/'));

        if (is_file($path)) {
            unlink($path);
        }
    }
}
