<div class="form-group row">
    <label for="payment_method_id" class="control-label col-md-4">{{ $label ?? 'Paid via' }}</label>

    <div class="col-md-6">
        <select name="payment_method_id" id="payment_method_id"
                class="custom-select{{ $errors->has('payment_method_id') ? ' is-invalid' : '' }}"
                {{ isset($required) ? ' required' : '' }}>
            <option value="">---</option>
            @foreach($payment_methods as $method)
                @if(old('payment_method_id') == $method->id)
                    <option value="{{ $method->id }}" selected>{{ $method->name }}</option>
                @elseif(isset($selected) && $selected == $method->id)
                    <option value="{{ $method->id }}" selected>{{ $method->name }}</option>
                @else
                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                @endif
            @endforeach
        </select>

        @if ($errors->has('payment_method_id'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('payment_method_id') }}</strong>
            </div>
        @endif

        {{-- Field description --}}
        @isset($description)
            <small class="form-text text-muted">{{ $description }}</small>
        @endisset
    </div>
</div>
