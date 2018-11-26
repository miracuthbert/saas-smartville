@extends('tenant.layouts.default')

@section('title', page_title('Company Profile'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.account.index') }}">Account</a>
    </li>
    <li class='breadcrumb-item active'>Profile</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h4>Company Profile</h4>
            </div>

            <form method="POST" action="{{ route('tenant.account.profile.store') }}" autocomplete="off">
                @csrf

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name', request()->tenant()->name) }}" required autofocus>

                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="short_name" class="control-label col-md-4">Short name</label>

                    <div class="col-md-6">
                        <input type="text" name="short_name" class="form-control-plaintext" id="short_name"
                               value="{{ request()->tenant()->short_name }}">

                        <small class="form-text text-muted">
                            A short and unique identifier of the company in invoices, receipts...
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email"
                               value="{{ old('email', request()->tenant()->email) }}" required>

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">The primary email that manages the company's account.
                        </small>
                    </div>
                </div>

                @include('layouts.partials.forms._countries', [
                    'description' => 'The country company is in.',
                    'selected' => request()->tenant()->country
                ])

                @include('layouts.partials.forms._currencies', [
                    'description' => 'The currency for default pricing label.',
                    'currency_code' => request()->tenant()->currency,
                    'attrs' => 'readonly'
                ])

                @include('layouts.partials.forms._timezones', [
                    'description' => 'The local time that company records will be shown.',
                    'selected' => request()->tenant()->timezone
                ])

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection