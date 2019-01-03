@extends('tenant.layouts.default')

@section('title', page_title("Add Payment Method"))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Payments</li>
    <li class="breadcrumb-item">Methods</li>
    <li class="breadcrumb-item active">Add Payment Method</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Add Payment Method
            </h4>

            <form method="POST" action="{{ route('tenant.account.payments.methods.store') }}">
                @csrf

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               name="name"
                               value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            The payment's method name such as: bank account, mobile money...
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('details') ? ' has-error' : '' }}">
                    <label for="details" class="control-label col-md-4">Details</label>
                    <div class="col-md-6">
                        <textarea name="details" id="details" rows="5"
                                  class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}">{{ old('details') }}</textarea>

                        @if($errors->has('details'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('details') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable" class="control-label col-md-4">Usable</label>
                    <div class="col-md-6">
                        <select name="usable" id="usable" class="custom-select{{ $errors->has('usable') ? ' is-invalid' : '' }}">
                            <option value="1"{{ old('usable') == 1 ? ' selected' : '' }}>Active</option>
                            <option value="0"{{ old('usable') == 0 ? ' selected' : '' }}>Disabled</option>
                        </select>

                        @if($errors->has('usable'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('usable') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            Set whether amenity can be used or not.
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
