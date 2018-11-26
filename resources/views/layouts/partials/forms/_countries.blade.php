<div class="form-group row{{ $errors->has('country') ? ' has-error' : '' }}">
    <label for="country" class="control-label col-md-4">Country</label>
    <div class="col-md-6">
        <select name="country" id="country"
                class="form-control custom-select{{ $errors->has('country') ? ' is-invalid' : '' }}">
            <option value="">--- Select a country ---</option>
            @foreach($countries as $country)
                @if(old('country') == $country->name)
                    <option value="{{ $country->name }}" selected>{{ $country->name }}</option>
                @elseif(isset($selected) && $selected == $country->name)
                    <option value="{{ $country->name }}" selected>{{ $country->name }}</option>
                @else
                    <option value="{{ $country->name }}">{{ $country->name }}</option>
                @endif
            @endforeach
        </select>

        @if($errors->has('country'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('country') }}</strong>
            </div>
        @endif

        @isset($description)
            <small class="form-text text-muted">{{ $description }}</small>
        @endisset
    </div>
</div>
