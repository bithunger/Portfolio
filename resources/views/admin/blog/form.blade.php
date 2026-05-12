<form class="admin-form" method="post" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    @php
        $timezone = config('app.timezone');
        $publishedAtValue = old('published_at');

        if ($publishedAtValue === null && $post->published_at) {
            $publishedAtValue = $post->published_at->copy()->timezone($timezone)->format('Y-m-d\TH:i');
        }
    @endphp

    <div class="form-grid">
        <label>Title <input name="title" value="{{ old('title', $post->title) }}" required></label>
        <label>Slug <input name="slug" value="{{ old('slug', $post->slug) }}"></label>
        <label class="full">Excerpt <input name="excerpt" maxlength="360" value="{{ old('excerpt', $post->excerpt) }}" required></label>
        <label class="full tinymce-field">
            Body
            <textarea class="tinymce-editor" name="body" rows="18" required data-tinymce-editor>{{ old('body', $post->body) }}</textarea>
            <small class="form-hint">Use the image tool for URL images and the Crop menu for quick image ratios.</small>
        </label>
        <div class="full cover-uploader">
            <div class="cover-preview">
                @if ($post->cover_image_url)
                    <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" data-cover-preview>
                @else
                    <span data-cover-placeholder>No cover yet</span>
                    <img src="" alt="" data-cover-preview hidden>
                @endif
            </div>
            <div class="file-field">
                <span class="field-label">Cover image</span>
                <div class="file-picker">
                    <input id="cover_image_file" class="file-picker-input" type="file" name="cover_image_file" accept="image/png,image/jpeg,image/webp" data-cover-input data-file-input>
                    <label class="file-picker-button" for="cover_image_file">Choose file</label>
                    <span class="file-picker-name" data-file-name>No file chosen</span>
                </div>
                @if ($post->cover_image_url)
                    <small class="form-hint">Upload a new image to replace the current cover.</small>
                @endif
            </div>
        </div>
        <label>
            Published time
            <input type="datetime-local" name="published_at" value="{{ $publishedAtValue }}">
        </label>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $post->display_order ?? 0) }}"></label>
        <small class="form-hint full">Current {{ $timezone }} time: {{ now($timezone)->format('M j, Y g:i A') }}</small>
        <div class="check-group">
            <label class="check-row"><input type="checkbox" name="featured" value="1" @checked(old('featured', $post->featured))> Featured</label>
            <label class="check-row"><input type="checkbox" name="published" value="1" @checked(old('published', $post->published ?? true))> Published</label>
        </div>
    </div>

    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.blog.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save post</button>
    </div>
</form>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin" defer></script>
    @endpush
@endonce
