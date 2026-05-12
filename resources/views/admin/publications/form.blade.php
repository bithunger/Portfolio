<form class="admin-form" method="post" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="form-grid">
        <label class="full">Title <input name="title" value="{{ old('title', $publication->title) }}" required></label>
        <label>Year <input type="number" min="1950" max="2100" name="year" value="{{ old('year', $publication->year) }}"></label>
        <label>Icon label <input name="icon" value="{{ old('icon', $publication->icon) }}"></label>
        <label>Journal name <input name="journal_name" value="{{ old('journal_name', $publication->journal_name) }}"></label>
        <label>Publisher <input name="publisher" value="{{ old('publisher', $publication->publisher) }}"></label>
        <label class="full">Article link <input type="url" name="article_url" value="{{ old('article_url', $publication->article_url) }}"></label>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $publication->display_order ?? 0) }}"></label>
        <label class="check-row"><input type="checkbox" name="active" value="1" @checked(old('active', $publication->active ?? true))> Active</label>
    </div>
    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.publications.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save publication</button>
    </div>
</form>
