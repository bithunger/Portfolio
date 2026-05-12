<form class="admin-form" method="post" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="form-grid">
        <label>Title <input name="title" value="{{ old('title', $service->title) }}" required></label>
        <label>Icon label <input name="icon" value="{{ old('icon', $service->icon) }}"></label>
        <label class="full">Description <textarea name="description" rows="5" required>{{ old('description', $service->description) }}</textarea></label>
        <label class="full">
            Deliverables
            <textarea name="deliverables" rows="5">{{ old('deliverables', implode("\n", $service->deliverables ?? [])) }}</textarea>
            <small class="form-hint warning">One item per line. Maximum 4 deliverables will be accepted.</small>
        </label>
        <label>Display order <input type="number" min="0" name="display_order" value="{{ old('display_order', $service->display_order ?? 0) }}"></label>
        <label class="check-row"><input type="checkbox" name="active" value="1" @checked(old('active', $service->active ?? true))> Active</label>
    </div>
    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.services.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save service</button>
    </div>
</form>
