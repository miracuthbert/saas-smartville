<div class="form-group row{{ $errors->has('utility_id') ? ' has-error' : '' }}">
    <label for="utility_id" class="col-md-4 control-label">Utility</label>

    <div class="col-md-6">
        <select name="utility_id" id="utility_id"
                class="form-control custom-select{{ $errors->has('utility_id') ? ' is-invalid' : '' }}" required>
            <option value="">--- Select a utility ---</option>
            @foreach($utilities as $utility)
                <option value="{{ $utility->id }}"
                        {{ old('utility_id') == $utility->id ? ' selected' : '' }}>
                    {{ $utility->name }} ({{ $utility->formattedPrice }} / {{ $utility->formattedBillingInterval }})
                </option>
            @endforeach
        </select>

        @if ($errors->has('utility_id'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('utility_id') }}</strong>
            </div>
        @endif
    </div>
</div>
