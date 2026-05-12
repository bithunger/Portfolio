<form class="admin-form" method="post" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="form-grid">
        <label>Name <input name="name" value="{{ old('name', $skill->name) }}" required></label>
        <label>Category <input name="category" value="{{ old('category', $skill->category ?? 'General') }}" required></label>
        <label>Proficiency <input type="number" min="1" max="100" name="proficiency" value="{{ old('proficiency', $skill->proficiency ?? 80) }}" required></label>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $skill->display_order ?? 0) }}"></label>
        <label class="check-row"><input type="checkbox" name="active" value="1" @checked(old('active', $skill->active ?? true))> Active</label>
    </div>
    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.skills.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save skill</button>
    </div>
</form>
