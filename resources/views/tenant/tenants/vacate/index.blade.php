@extends('tenant.layouts.default')

@section('title', page_title("Vacate Tenant"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.tenants.index') }}">Tenants</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('tenant.tenants.edit', $lease) }}">Tenant</a>
    </li>
    <li class='breadcrumb-item active'>Vacate</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Vacate tenant
            </h4>

            <form method="POST" action="{{ route('tenant.tenants.vacate.store', $lease) }}">
                @csrf

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Tenant</label>

                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control-plaintext" id="name"
                               value="{{ optional($lease->user)->name ?: $lease->invitation->name }}" readonly>
                    </div>
                </div><!-- /.form-group -->

                <div class="form-group row{{ $errors->has('property_id') ? ' has-error' : '' }}">
                    <label for="property_id" class="col-md-4 control-label">Property</label>

                    <div class="col-md-6">
                        <input type="hidden" name="property_id" value="{{ $lease->property->id }}">
                        <input type="text" class="form-control-plaintext" id="property_id"
                               value="{{ $lease->property->name }}" readonly>
                    </div>
                </div><!-- /.form-group -->

                <div class="form-group row">
                    <label for="status" class="col-md-4 control-label">Lease status</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control-plaintext" id="status"
                               value="{{ $lease->status }}" readonly>
                    </div>
                </div><!-- /.form-group -->

                <div class="form-group row">
                    <label for="status" class="col-md-4 control-label">Moved In</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control-plaintext" id="moved_in"
                               value="{{ $lease->formattedMoveIn }}" readonly>
                    </div>
                </div><!-- /.form-group -->

                <div class="form-group row">
                    <label for="status" class="col-md-4 control-label">Moves out</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control-plaintext" id="moved_out"
                               value="{{ $lease->formattedMoveOut }}" readonly>
                    </div>
                </div><!-- /.form-group -->

                @datetimepicker([
                    'field_name' => 'vacated_at',
                    'label' => 'Vacated at',
                    'description' => 'Date tenant vacated.',
                    'value' => $lease->vacated_at
                ])
                @enddatetimepicker

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <p class="h4">
                            Are you sure you want to vacate tenant?
                        </p>

                        <button type="submit" class="btn btn-danger">Yes, Vacate Tenant</button>
                        <a href="{{ route('tenant.tenants.index') }}" role="button" class="btn btn-success">
                            No! Get me out of here
                        </a>
                    </div>
                </div><!-- /.form-group -->
            </form><!-- /form -->
        </div><!-- /.card-body -->
    </div><!-- /.card -->
@endsection