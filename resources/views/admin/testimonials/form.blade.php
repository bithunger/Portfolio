<form class="admin-form" method="post" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="form-grid">
        <label>Name <input name="name" value="{{ old('name', $testimonial->name) }}" required></label>
        <label>Title <input name="title" value="{{ old('title', $testimonial->title) }}"></label>
        <label>Company <input name="company" value="{{ old('company', $testimonial->company) }}"></label>
        <div class="avatar-uploader">
            <div class="avatar-preview">
                @if ($testimonial->avatar_url)
                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" data-cover-preview>
                @else
                    <span data-cover-placeholder>{{ mb_substr($testimonial->name ?: 'T', 0, 1) }}</span>
                    <img src="" alt="" data-cover-preview hidden>
                @endif
            </div>
            <label>
                Avatar image
                <input type="file" name="avatar_file" accept="image/png,image/jpeg,image/webp" data-cover-input>
                @if ($testimonial->avatar_url)
                    <small class="form-hint">Upload a new image to replace the current avatar.</small>
                @endif
            </label>
        </div>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $testimonial->display_order ?? 0) }}"></label>
        <div class="check-group">
            <label class="check-row"><input type="checkbox" name="featured" value="1" @checked(old('featured', $testimonial->featured))> Featured</label>
            <label class="check-row"><input type="checkbox" name="active" value="1" @checked(old('active', $testimonial->active ?? true))> Active</label>
        </div>
        <label class="full">Quote <textarea name="quote" rows="6" required>{{ old('quote', $testimonial->quote) }}</textarea></label>
    </div>
    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.testimonials.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save testimonial</button>
    </div>
</form>
