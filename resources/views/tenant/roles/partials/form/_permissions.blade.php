<div class="form-group row{{ $errors->has('permissions') ? ' has-error' : '' }}">
    <label for="permissions" class="control-label col-md-4">Permissions</label>
    <div class="col-md-6" id="permissionsCheck">
        <div class="form-check">
            <input type="checkbox" class="form-check-input checkbox-toggle" id="selectAllPermissions"
                   data-target="#permissionsCheck">
            <label for="selectAllPermissions" class="form-check-label">
                Assign all permissions
            </label>
        </div>
        <hr>
        @foreach($permissions as $permission)
            <div class="form-check">
                @if(collect(old('permissions', []))->contains($permission->id) ||
                (isset($role) && optional($role->permissions)->firstWhere('id', $permission->id)))
                    <input class="form-check-input{{ $errors->has('permissions') ? ' is-invalid' : '' }}"
                           type="checkbox" name="permissions[]" id="permissions_{{ $permission->id }}"
                           value="{{ $permission->id }}" data-parent="#permissionsCheck" checked>
                @else
                    <input class="form-check-input{{ $errors->has('permissions') ? ' is-invalid' : '' }}"
                           type="checkbox" name="permissions[]" id="permissions_{{ $permission->id }}"
                           value="{{ $permission->id }}" data-parent="#permissionsCheck">
                @endif
                <label class="form-check-label" for="permissions_{{ $permission->id }}">
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach

        <input type="hidden" class="form-control{{ $errors->has('permissions') ? ' is-invalid' : '' }}">

        @if($errors->has('permissions'))
            <div class="invalid-feedback">
                @foreach($errors->get('permissions.*') as $message)
                    <div><strong>{{ $message }}</strong></div>
                @endforeach
            </div>
        @endif

        <small class="form-text">
            Select permissions specific to this role.
        </small>
    </div>
</div>