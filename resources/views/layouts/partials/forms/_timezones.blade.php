<div class="form-group row{{ $errors->has('timezone') ? ' has-error' : '' }}">
    <label for="timezone" class="control-label col-md-4">Timezone</label>
    <div class="col-md-6">
        <select name="timezone" id="timezone"
                class="form-control custom-select{{ $errors->has('timezone') ? ' is-invalid' : '' }}">
            <option value="">--- Select a timezone ---</option>
            @foreach($timezones as $timezone)
                @if(old('timezone') == $timezone)
                    <option value="{{ $timezone }}" selected>{{ $timezone }}</option>
                @elseif(isset($selected) && $selected == $timezone)
                    <option value="{{ $timezone }}" selected>{{ $timezone }}</option>
                @else
                    <option value="{{ $timezone }}">{{ $timezone }}</option>
                @endif
            @endforeach
        </select>

        @if($errors->has('timezone'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('timezone') }}</strong>
            </div>
        @endif

        @isset($description)
            <small class="form-text text-muted">{{ $description }}</small>
        @endisset
    </div>
</div>
