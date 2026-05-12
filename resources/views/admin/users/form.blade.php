<form class="admin-form" method="post" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST') @method($method) @endif

    <div class="form-grid">
        <label>Name <input name="name" value="{{ old('name', $user->name) }}" required></label>
        <label>Email <input type="email" name="email" value="{{ old('email', $user->email) }}" required></label>
        <label>
            Password
            <input type="password" name="password" @required(! $user->exists)>
        </label>
        <label>Password confirmation <input type="password" name="password_confirmation" @required(! $user->exists)></label>
        @if ($user->exists)
            <small class="form-hint full">Leave password fields blank to keep the current password.</small>
        @endif
        <label class="check-row full">
            <input type="checkbox" name="email_verified" value="1" @checked(old('email_verified', (bool) $user->email_verified_at))>
            Email verified
        </label>
    </div>

    <div class="form-actions">
        <a class="btn ghost" href="{{ route('admin.users.index') }}">Cancel</a>
        <button class="btn primary" type="submit">Save user</button>
    </div>
</form>
