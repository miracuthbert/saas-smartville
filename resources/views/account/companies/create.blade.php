@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create new company</h4>

                        <form method="POST" action="{{ route('account.companies.store') }}">
                            @csrf

                            <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="control-label col-md-4">Name</label>

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

                                    <small class="form-text text-muted">
                                        The company or business name.
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('short_name') ? ' has-error' : '' }}">
                                <label for="name" class="control-label col-md-4">Short name</label>

                                <div class="col-md-6">
                                    <input id="short_name" type="text"
                                           class="form-control{{ $errors->has('short_name') ? ' is-invalid' : '' }}"
                                           name="short_name"
                                           value="{{ old('short_name') }}" maxlength="7" required>

                                    @if ($errors->has('short_name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('short_name') }}</strong>
                                        </div>
                                    @endif

                                    <small class="form-text text-muted">
                                        A short and unique identifier of the company in invoices, receipts...
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="control-label col-md-4">Email Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email"
                                           value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif

                                    <small class="form-text text-muted">
                                        The email that we will be used for the company account.
                                    </small>
                                </div>
                            </div>

                            @include('layouts.partials.forms._countries', [
                                'description' => 'The country company is in.'
                            ])

                            @include('layouts.partials.forms._currencies', [
                                'description' => 'The currency for default pricing label.'
                            ])

                            @include('layouts.partials.forms._timezones', [
                                'description' => 'The local time that company records will be shown.'
                            ])

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
