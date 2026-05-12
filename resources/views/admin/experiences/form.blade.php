<form class="admin-form" method="post" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="form-grid">
        <label>Role <input name="role" value="{{ old('role', $experience->role) }}" required></label>
        <label>Company <input name="company" value="{{ old('company', $experience->company) }}" required></label>
        <label>Location <input name="location" value="{{ old('location', $experience->location) }}"></label>
        <label>Start date <input type="date" name="start_date" value="{{ old('start_date', optional($experience->start_date)->format('Y-m-d')) }}"></label>
        <label>End date <input type="date" name="end_date" value="{{ old('end_date', optional($experience->end_date)->format('Y-m-d')) }}"></label>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $experience->display_order ?? 0) }}"></label>
        <div class="check-group">
            <label class="check-row"><input type="checkbox" name="is_current" value="1" @checked(old('is_current', $experience->is_current))> Current</label>
            <label class="check-row"><input type="checkbox" name="active" value="1" @checked(old('active', $experience->active ?? true))> Active</label>
        </div>
        <label class="full">Summary <textarea name="summary" rows="4">{{ old('summary', $experience->summary) }}</textarea></label>
        <label class="full">Highlights <textarea name="highlights" rows="5">{{ old('highlights', implode("\n", $experience->highlights ?? [])) }}</textarea></label>
    </div>
    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.experiences.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save role</button>
    </div>
</form>
