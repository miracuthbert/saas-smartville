@extends('tenant.layouts.default')

@section('title', page_title("Add Tenant | {$property->name}"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.properties.index') }}">Properties</a>
    </li>
    <li class='breadcrumb-item'>{{ $property->name }}</li>
    <li class='breadcrumb-item active'>Add Tenant</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Add tenant
            </h4>

            <form method="POST" action="{{ route('tenant.properties.invitations.store', $property) }}">
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
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-mail Address</label>

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

                        <div class="form-text text-muted">A link will be sent to the tenant to complete registration.
                        </div>
                    </div>
                </div>

                @include('components._datepicker', [
                    'field_name' => 'moved_in_at',
                    'required' => true,
                    'label' => 'Moves in',
                    'description' => 'Date tenant moves in.'
                ])

                @include('components._datepicker', [
                    'field_name' => 'moved_out_at',
                    'label' => 'Moves out',
                    'description' => 'Date tenant moves out.'
                ])

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Send Invitation</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection