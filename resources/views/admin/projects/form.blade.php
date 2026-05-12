<form class="admin-form" method="post" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="form-grid">
        <label>Title <input name="title" value="{{ old('title', $project->title) }}" required></label>
        <label>Slug <input name="slug" value="{{ old('slug', $project->slug) }}"></label>
        <label class="full">Summary <input name="summary" value="{{ old('summary', $project->summary) }}" required></label>
        <label class="full">Description <textarea name="description" rows="7">{{ old('description', $project->description) }}</textarea></label>
        <div class="full cover-uploader">
            <div class="cover-preview">
                @if ($project->image_url)
                    <img src="{{ $project->image_url }}" alt="{{ $project->title }}" data-cover-preview>
                @else
                    <span data-cover-placeholder>No project image yet</span>
                    <img src="" alt="" data-cover-preview hidden>
                @endif
            </div>
            <label>
                Project image
                <input type="file" name="project_image_file" accept="image/png,image/jpeg,image/webp" data-cover-input>
                @if ($project->image_url)
                    <small class="form-hint">Upload a new image to replace the current project image.</small>
                @endif
            </label>
        </div>
        <label>Client <input name="client" value="{{ old('client', $project->client) }}"></label>
        <label>Role <input name="role" value="{{ old('role', $project->role) }}"></label>
        <label>Year <input type="number" name="year" min="1990" max="2100" value="{{ old('year', $project->year) }}"></label>
        <label>Live URL <input type="url" name="live_url" value="{{ old('live_url', $project->live_url) }}"></label>
        <label>Source URL <input type="url" name="repo_url" value="{{ old('repo_url', $project->repo_url) }}"></label>
        <label>Display order <input type="number" name="display_order" min="0" value="{{ old('display_order', $project->display_order ?? 0) }}"></label>
        <div class="check-group">
            <label class="check-row"><input type="checkbox" name="featured" value="1" @checked(old('featured', $project->featured))> Featured</label>
            <label class="check-row"><input type="checkbox" name="published" value="1" @checked(old('published', $project->published ?? true))> Published</label>
        </div>
        <label class="full">
            Tech stack
            <textarea name="tech_stack" rows="5">{{ old('tech_stack', implode("\n", $project->tech_stack ?? [])) }}</textarea>
            <small class="form-hint warning">One item per line. Maximum 4 tech stack items will be accepted.</small>
        </label>
    </div>

    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.projects.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save project</button>
    </div>
</form>
