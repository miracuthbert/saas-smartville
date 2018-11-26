<div class="form-group row{{ $errors->has('currency') ? ' has-error' : '' }}">
    <label for="currency" class="control-label col-md-4">Currency</label>
    <div class="col-md-6">
        <select name="currency" id="currency"
                class="form-control custom-select{{ $errors->has('currency') ? ' is-invalid' : '' }}"{{ $attrs or '' }}>
            <option value="">--- Select a currency ---</option>
            @foreach($currencies as $currency)
                @if(old('currency') == $currency->cc)
                    <option value="{{ $currency->cc }}" selected>{{ $currency->cc }} ({{ $currency->name }})</option>
                @elseif(isset($currency_code) && $currency_code == $currency->cc)
                    <option value="{{ $currency->cc }}" selected>{{ $currency->cc }} ({{ $currency->name }})</option>
                @else
                    <option value="{{ $currency->cc }}">{{ $currency->cc }} ({{ $currency->name }})</option>
                @endif
            @endforeach
        </select>

        @if($errors->has('currency'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('currency') }}</strong>
            </div>
        @endif

        @isset($description)
            <small class="form-text text-muted">{{ $description }}</small>
        @endisset
    </div>
</div>
