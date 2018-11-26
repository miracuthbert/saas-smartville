<div class="form-group row{{ $errors->has('role') ? ' has-error' : '' }}">
    <label for="role" class="col-md-4 control-label">Role</label>

    <div class="col-md-6">
        <select name="role" id="role"
                class="form-control custom-select{{ $errors->has('role') ? ' is-invalid' : '' }}">
            <option value="">--- Select a role ---</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}"
                        {{ old('role') == $role->id ? ' selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        @if ($errors->has('role'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('role') }}</strong>
            </div>
        @endif
    </div>
</div>
