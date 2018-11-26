<div class="form-group row{{ $errors->has('utilities') ? ' has-error' : '' }}">
    <label for="utilities" class="control-label col-md-4">Utilities</label>
    <div class="col-md-6">
        @foreach($utilities as $utility)
            <div class="form-check">
                @if(collect(old('utilities', []))->contains($utility->id) ||
                optional($property->utilities)->firstWhere('id', $utility->id))
                    <input class="form-check-input{{ $errors->has('utilities') ? ' is-invalid' : '' }}"
                           type="checkbox" name="utilities[]" id="utilities_{{ $utility->id }}"
                           value="{{ $utility->id }}" checked>
                @else
                    <input class="form-check-input{{ $errors->has('utilities') ? ' is-invalid' : '' }}"
                           type="checkbox" name="utilities[]" id="utilities_{{ $utility->id }}"
                           value="{{ $utility->id }}">
                @endif
                <label class="form-check-label" for="utilities_{{ $utility->id }}">
                    {{ $utility->name }} ({{ $utility->formattedPrice }} / {{ $utility->formattedBillingInterval }})
                </label>
            </div>
        @endforeach

        <input type="hidden" class="form-control{{ $errors->has('utilities') ? ' is-invalid' : '' }}">

        @if($errors->has('utilities'))
            <div class="invalid-feedback">
                @foreach($errors->get('utilities.*') as $message)
                    <div><strong>{{ $message }}</strong></div>
                @endforeach
            </div>
        @endif

        <small class="form-text">
            Select utilities specific to this property.
        </small>
    </div>
</div>