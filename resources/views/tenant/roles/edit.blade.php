@extends('tenant.layouts.default')

@section('title', page_title('Edit Role'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.roles.index') }}">Roles</a>
    </li>
    <li class='breadcrumb-item active'>Edit</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Edit Role</h4>

            <form method="POST" action="{{ route('tenant.roles.update', $companyRole) }}" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name', $companyRole->name) }}" required autofocus>

                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('details') ? ' has-error' : '' }}">
                    <label for="details" class="control-label col-md-4">Details</label>
                    <div class="col-md-6">
                        <textarea name="details" id="details" rows="5"
                                  class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}">{{ old('details', $companyRole->details) }}</textarea>

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
                        <select name="usable" id="usable"
                                class="custom-select form-control{{ $errors->has('usable') ? ' is-invalid' : '' }}">
                            <option value="0"{{ old('usable', $companyRole->usable) == 0 ? ' selected' : '' }}>False
                            </option>
                            <option value="1"{{ old('usable', $companyRole->usable) == 1 ? ' selected' : '' }}>True
                            </option>
                        </select>

                        @if($errors->has('usable'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('usable') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                @include('tenant.roles.partials.form._permissions', ['role' => $companyRole])

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection