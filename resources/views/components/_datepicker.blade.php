<div class="form-group{{ isset($horizontal) && $horizontal == false ? '' : ' row' }}{{ $errors->has($field_name) ? ' has-error' : '' }}">
    <label for="{{ $field_name }}"
           class="{{ isset($horizontal) && $horizontal == false ? '' : 'col-md-4 control-label' }}">
        {{ $label }}
    </label>

    <div class="{{ isset($horizontal) && $horizontal == false ? '' : 'col-md-6' }}">
        <input id="{{ $field_name }}" type="text"
               class="form-control datepicker{{ $errors->has($field_name) ? ' is-invalid' : '' }}"
               name="{{ $field_name }}"
               value="{{ old($field_name, isset($value) ? $value : null) }}"{{ isset($required) ? ' required' : '' }}>

        @if ($errors->has($field_name))
            <div class="invalid-feedback">
                <strong>{{ $errors->first("{$field_name}") }}</strong>
            </div>
        @endif

        {{-- Field description --}}
        @isset($description)
            <small class="form-text text-muted">{{ $description }}</small>
        @endisset
    </div>
</div>
