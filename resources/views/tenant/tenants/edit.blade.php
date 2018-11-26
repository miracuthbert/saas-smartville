@extends('tenant.layouts.default')

@section('title', page_title("Edit Tenant"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.tenants.index') }}">Tenants</a>
    </li>
    <li class='breadcrumb-item active'>Edit Tenant</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Edit tenant
            </h4>

            <form method="POST" action="{{ route('tenant.tenants.update', $lease) }}">
                @csrf
                @method('PUT')

                <div class="form-group row{{ $errors->has('property_id') ? ' has-error' : '' }}">
                    <label for="property_id" class="col-md-4 control-label">Property</label>

                    <div class="col-md-6">
                        <input type="hidden" name="property_id" value="{{ $lease->property->id }}">
                        <input type="text" class="form-control-plaintext" id="property_id"
                               value="{{ $lease->property->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">Tenant</label>

                    <div class="col-md-6">
                        <input type="text" name="name" class="form-control-plaintext" id="name"
                               value="{{ $lease->user ? $lease->user->name : $lease->invitation->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-md-4 control-label">Lease status</label>

                    <div class="col-md-6">
                        <input type="text" class="form-control-plaintext" id="status"
                               value="{{ $lease->status }}" readonly>
                    </div>
                </div>

                @include('components._datepicker', [
                    'field_name' => 'moved_in_at',
                    'required' => true,
                    'label' => 'Moves in',
                    'description' => 'Date tenant moves in.',
                    'value' => $lease->moved_in_at
                ])

                @include('components._datepicker', [
                    'field_name' => 'moved_out_at',
                    'label' => 'Moves out',
                    'description' => 'Date tenant moves out.',
                    'value' => $lease->moved_out_at
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