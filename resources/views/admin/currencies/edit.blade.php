@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.currencies.index') }}">Currencies</a>
    </li>
    <li class='breadcrumb-item active'>Edit</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit currency</h4>

            <form action="{{ route('admin.currencies.update', $currency) }}" method="post">
                @csrf
                @method('PUT')

                <div class="form-group row{{ $errors->has('cc') ? ' has-error' : '' }}">
                    <label for="cc" class="control-label col-md-4">Currency code</label>
                    <div class="col-md-6">
                        <input type="text" name="cc"
                               class="form-control{{ $errors->has('cc') ? ' is-invalid' : '' }}" id="cc"
                               value="{{ old('cc', $currency->cc) }}" required autofocus>

                        @if($errors->has('cc'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('cc') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('symbol') ? ' has-error' : '' }}">
                    <label for="symbol" class="control-label col-md-4">Symbol</label>
                    <div class="col-md-6">
                        <input type="text" name="symbol"
                               class="form-control{{ $errors->has('symbol') ? ' is-invalid' : '' }}" id="symbol"
                               value="{{ old('symbol', $currency->symbol) }}" required>

                        @if($errors->has('symbol'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('symbol') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name', $currency->name) }}" required>

                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable" class="control-label col-md-4">Usable</label>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_true"
                                   value="1" {{ old('usable', $currency->usable) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_true">True (Active)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_false"
                                   value="0" {{ old('usable', $currency->usable) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_false">False (Disable)</label>
                        </div>

                        <input type="hidden" class="form-control{{ $errors->has('usable') ? ' is-invalid' : '' }}">

                        @if($errors->has('usable'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('usable') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            Set whether currency can be used or not.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection