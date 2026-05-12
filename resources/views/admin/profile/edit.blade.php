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
                    <div class="file-field">
                        <span class="field-label">Portrait image</span>
                        <div class="file-picker">
                            <input id="portrait_image" class="file-picker-input" type="file" name="portrait_image" accept="image/png,image/jpeg,image/webp" data-portrait-input data-file-input>
                            <label class="file-picker-button" for="portrait_image">Choose file</label>
                            <span class="file-picker-name" data-file-name>No file chosen</span>
                        </div>
                    </div>
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
            <div class="file-field">
                <span class="field-label">Resume file</span>
                <div class="file-picker">
                    <input id="resume_file" class="file-picker-input" type="file" name="resume_file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" data-file-input>
                    <label class="file-picker-button" for="resume_file">Choose file</label>
                    <span class="file-picker-name" data-file-name>No file chosen</span>
                </div>
                @if ($profile->resume_url)
                    <small class="form-hint">Current resume: <a href="{{ $profile->resume_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </div>
            <label>Email <input type="email" name="email" value="{{ old('email', $profile->email) }}" required></label>
            <label>Phone <input name="phone" value="{{ old('phone', $profile->phone) }}"></label>
            <label>Location <input name="location" value="{{ old('location', $profile->location) }}"></label>
            <label>Availability <input name="availability" value="{{ old('availability', $profile->availability) }}"></label>

            <div class="form-section-title full">
                <span>Homepage order</span>
            </div>
            @php($homeSectionOptions = \App\Models\Profile::homeSectionOptions())
            @php($orderedHomeSections = collect(old('home_section_order', $profile->orderedHomeSections()))->filter(fn ($section) => array_key_exists($section, $homeSectionOptions))->values())
            @php($orderedHomeSections = $orderedHomeSections->merge(collect(array_keys($homeSectionOptions))->diff($orderedHomeSections))->values())
            <div class="home-order-list full" data-sortable-home-sections>
                @foreach ($orderedHomeSections as $sectionKey)
                    <div class="home-order-item" draggable="true" data-sortable-item>
                        <input type="hidden" name="home_section_order[]" value="{{ $sectionKey }}">
                        <span class="home-order-handle" aria-hidden="true">
                            <span></span><span></span><span></span>
                        </span>
                        <span class="home-order-name">{{ $homeSectionOptions[$sectionKey] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="form-section-title full">
                <span>Theme and brand</span>
            </div>
            <div class="theme-color-grid full">
                <label class="color-field">
                    Accent color
                    <span class="color-control">
                        <input type="color" name="accent_color" value="{{ old('accent_color', $profile->accent_color ?: '#0f766e') }}" data-color-picker>
                        <span class="color-value" data-color-value></span>
                    </span>
                </label>
                <label class="color-field">
                    Frontend background
                    <span class="color-control">
                        <input type="color" name="frontend_background_color" value="{{ old('frontend_background_color', $profile->frontend_background_color ?: '#f7f8f6') }}" data-color-picker>
                        <span class="color-value" data-color-value></span>
                    </span>
                </label>
                <label class="color-field">
                    Backend background
                    <span class="color-control">
                        <input type="color" name="backend_background_color" value="{{ old('backend_background_color', $profile->backend_background_color ?: '#f7f8f6') }}" data-color-picker>
                        <span class="color-value" data-color-value></span>
                    </span>
                </label>
            </div>
            <label class="select-field font-style-field">
                Font style
                <select name="font_family">
                    @foreach (\App\Models\Profile::fontOptions() as $key => $option)
                        <option value="{{ $key }}" @selected(old('font_family', $profile->font_family ?: 'elegant') === $key)>{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
            <div class="file-field">
                <span class="field-label">Frontend logo</span>
                <div class="file-picker">
                    <input id="frontend_logo_file" class="file-picker-input" type="file" name="frontend_logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml" data-file-input>
                    <label class="file-picker-button" for="frontend_logo_file">Choose file</label>
                    <span class="file-picker-name" data-file-name>No file chosen</span>
                </div>
                @if ($profile->frontend_logo_url)
                    <small class="form-hint">Current logo: <a href="{{ $profile->frontend_logo_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </div>
            <div class="file-field">
                <span class="field-label">Backend logo</span>
                <div class="file-picker">
                    <input id="backend_logo_file" class="file-picker-input" type="file" name="backend_logo_file" accept="image/png,image/jpeg,image/webp,image/svg+xml" data-file-input>
                    <label class="file-picker-button" for="backend_logo_file">Choose file</label>
                    <span class="file-picker-name" data-file-name>No file chosen</span>
                </div>
                @if ($profile->backend_logo_url)
                    <small class="form-hint">Current logo: <a href="{{ $profile->backend_logo_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </div>
            <div class="file-field">
                <span class="field-label">Favicon</span>
                <div class="file-picker">
                    <input id="favicon_file" class="file-picker-input" type="file" name="favicon_file" accept=".ico,image/png,image/jpeg,image/webp,image/svg+xml" data-file-input>
                    <label class="file-picker-button" for="favicon_file">Choose file</label>
                    <span class="file-picker-name" data-file-name>No file chosen</span>
                </div>
                @if ($profile->favicon_url)
                    <small class="form-hint">Current favicon: <a href="{{ $profile->favicon_url }}" target="_blank" rel="noreferrer">open file</a></small>
                @endif
            </div>

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
