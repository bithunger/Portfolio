<form class="admin-form" method="post" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="form-grid">
        <label>Degree <input name="degree" value="{{ old('degree', $educationEntry->degree) }}" required></label>
        <label>Institution <input name="institution" value="{{ old('institution', $educationEntry->institution) }}" required></label>
        <label>Location <input name="location" value="{{ old('location', $educationEntry->location) }}"></label>
        <label>Start year <input type="number" min="1950" max="2100" name="start_year" value="{{ old('start_year', $educationEntry->start_year) }}"></label>
        <label>End year <input type="number" min="1950" max="2100" name="end_year" value="{{ old('end_year', $educationEntry->end_year) }}"></label>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $educationEntry->display_order ?? 0) }}"></label>
        <label class="full">Summary <textarea name="summary" rows="4">{{ old('summary', $educationEntry->summary) }}</textarea></label>
        <label class="full">Highlights <textarea name="highlights" rows="5">{{ old('highlights', implode("\n", $educationEntry->highlights ?? [])) }}</textarea></label>
        <label class="check-row"><input type="checkbox" name="active" value="1" @checked(old('active', $educationEntry->active ?? true))> Active</label>
    </div>
    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.education.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save education</button>
    </div>
</form>
