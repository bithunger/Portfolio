@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
    <div class="admin-heading">
        <div>
            <p class="eyebrow">Profile</p>
            <h1>Site identity</h1>
        </div>
    </div>

    <form class="admin-form" method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" data-profile-form>
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-section-title full">
                <span>Identity</span>
            </div>
            <label>Owner name <input name="owner_name" value="{{ old('owner_name', $profile->owner_name) }}" required></label>
            <label>Headline <input name="headline" value="{{ old('headline', $profile->headline) }}" required></label>
            <label class="full">Tagline <input name="tagline" value="{{ old('tagline', $profile->tagline) }}"></label>
            <label>Hero card label <input name="hero_panel_label" value="{{ old('hero_panel_label', $profile->hero_panel_label) }}" placeholder="Portfolio Studio"></label>
            <label>Hero card text <input name="hero_panel_text" value="{{ old('hero_panel_text', $profile->hero_panel_text) }}" placeholder="{{ $profile->email }}"></label>
            <label class="full">Bio <textarea name="bio" rows="5">{{ old('bio', $profile->bio) }}</textarea></label>
            <div class="portrait-uploader full" data-portrait-editor>
                <input type="hidden" name="portrait_crop" data-portrait-crop>
                <div class="portrait-current">
                    <img src="{{ $profile->portrait_url ?: '' }}" alt="{{ $profile->owner_name }}" data-portrait-preview @if (! $profile->portrait_url) hidden @endif>
                    @if (! $profile->portrait_url)
                        <div class="portrait-placeholder" data-portrait-placeholder>{{ mb_substr($profile->owner_name, 0, 1) }}</div>
                    @endif
                </div>
                <div class="portrait-controls">
                    <label>Portrait image <input type="file" name="portrait_image" accept="image/png,image/jpeg,image/webp" data-portrait-input></label>
                    <div class="crop-workbench" data-crop-workbench hidden>
                        <canvas width="900" height="900" data-crop-canvas></canvas>
                        <div class="crop-controls">
                            <label>Zoom <input type="range" min="1" max="3" step="0.01" value="1" data-crop-zoom></label>
                            <label>Rotate <input type="range" min="-15" max="15" step="1" value="0" data-crop-rotate></label>
                            <button class="btn ghost" type="button" data-crop-reset>Reset</button>
                        </div>
                    </div>
                </div>
            </div>
            <label>
                Resume file
                <input type="file" name="resume_file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                @if ($profile->resume_url)
                    <small class="form-hint">Current resume: <a href="{{ $profile->resume_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </label>
            <label>Email <input type="email" name="email" value="{{ old('email', $profile->email) }}" required></label>
            <label>Phone <input name="phone" value="{{ old('phone', $profile->phone) }}"></label>
            <label>Location <input name="location" value="{{ old('location', $profile->location) }}"></label>
            <label>Availability <input name="availability" value="{{ old('availability', $profile->availability) }}"></label>

            <div class="form-section-title full">
                <span>Theme and brand</span>
            </div>
            <label>Accent color <input type="color" name="accent_color" value="{{ old('accent_color', $profile->accent_color ?: '#0f766e') }}"></label>
            <label>Frontend background <input type="color" name="frontend_background_color" value="{{ old('frontend_background_color', $profile->frontend_background_color ?: '#f7f8f6') }}"></label>
            <label>Backend background <input type="color" name="backend_background_color" value="{{ old('backend_background_color', $profile->backend_background_color ?: '#f7f8f6') }}"></label>
            <label>
                Font style
                <select name="font_family">
                    @foreach (\App\Models\Profile::fontOptions() as $key => $option)
                        <option value="{{ $key }}" @selected(old('font_family', $profile->font_family ?: 'elegant') === $key)>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Frontend logo
                <input type="file" name="frontend_logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                @if ($profile->frontend_logo_url)
                    <small class="form-hint">Current logo: <a href="{{ $profile->frontend_logo_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </label>
            <label>
                Backend logo
                <input type="file" name="backend_logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                @if ($profile->backend_logo_url)
                    <small class="form-hint">Current logo: <a href="{{ $profile->backend_logo_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </label>
            <label>
                Favicon
                <input type="file" name="favicon_file" accept=".ico,image/png,image/jpeg,image/webp,image/svg+xml">
                @if ($profile->favicon_url)
                    <small class="form-hint">Current favicon: <a href="{{ $profile->favicon_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </label>

            <div class="form-section-title full">
                <span>SEO and links</span>
            </div>
            <label>SEO title <input name="seo_title" value="{{ old('seo_title', $profile->seo_title) }}"></label>
            <label class="full">SEO description <textarea name="seo_description" rows="3">{{ old('seo_description', $profile->seo_description) }}</textarea></label>
            <label>GitHub URL <input type="url" name="github_url" value="{{ old('github_url', $profile->github_url) }}"></label>
            <label>LinkedIn URL <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url) }}"></label>
            <label>Twitter URL <input type="url" name="twitter_url" value="{{ old('twitter_url', $profile->twitter_url) }}"></label>
            <label>Dribbble URL <input type="url" name="dribbble_url" value="{{ old('dribbble_url', $profile->dribbble_url) }}"></label>
            <label>Facebook URL <input type="url" name="facebook_url" value="{{ old('facebook_url', $profile->facebook_url) }}"></label>
            <label>WhatsApp link or number <input name="whatsapp_url" value="{{ old('whatsapp_url', $profile->whatsapp_url) }}"></label>
            <label class="full">Website URL <input type="url" name="website_url" value="{{ old('website_url', $profile->website_url) }}"></label>
        </div>

        <div class="form-actions">
            <button class="btn primary" type="submit">Save profile</button>
        </div>
    </form>
@endsection
