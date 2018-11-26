@extends('tenant.layouts.default')

@section('title', page_title("Add Utility Invoice"))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item active'>Add Invoice</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Add Utility Invoice
            </h4>

            <form method="POST" action="{{ route('tenant.utilities.invoices.store') }}">
                @csrf

                <div class="form-group row">
                    <label for="utility_id" class="col-md-4 control-label">Utility</label>

                    <div class="col-md-6">
                        <input type="hidden" name="utility_id" value="{{ $utility->id }}">
                        <input type="hidden" name="billing_type" value="{{ $utility->billing_type }}">

                        <input type="text" id="utility_id" class="form-control-plaintext"
                               value="{{ $utility->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_for" class="col-md-4 control-label">For</label>

                    <div class="col-md-6">
                        <input type="hidden" name="start_at" value="{{ $start_at }}">
                        <input type="hidden" name="end_at" value="{{ $end_at }}">

                        <input type="text" id="invoice_for" class="form-control-plaintext"
                               value="{{ dateParse($start_at)->format('d-M') }} - {{ dateParse($end_at)->format('d-M-Y') }}"
                               readonly>
                    </div>
                </div>

                @include('components._datetimepicker', [
                    'field_name' => 'sent_at',
                    'required' => true,
                    'label' => 'Send at',
                    'description' => 'Date invoice should be sent to tenants.',
                    'value' => $sent_at
                ])

                <div class="form-group row">
                    <label for="invoice_due" class="col-md-4 control-label">Due</label>

                    <div class="col-md-6">
                        <input type="hidden" name="due_at" value="{{ $due_at }}">

                        <input type="text" id="invoice_due" class="form-control-plaintext" value="{{ $due_at }}"
                               readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label">Properties</label>
                    <div class="">
                        <div class="form-text">
                            Select properties below to generate and send their respective utility invoice
                        </div>
                        @if($properties->count())
                            <div class="table-responsive-sm mb-3">
                                <table class="table table-borderless">
                                    <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Property</th>
                                        <th>Tenant</th>

                                        {{-- Show if utility varied --}}
                                        @if($utility->billing_type == 'varied')
                                            <th>Previous</th>
                                            <th>Current</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($properties as $property)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="properties[{{ $property->id }}][id]"
                                                           class="custom-control-input"
                                                           id="property{{ $property->id }}"
                                                           value="{{ $property->id }}"
                                                            {{ old("properties.{$property->id}.id") == $property->id ? ' checked' : '' }}>
                                                    <label class="custom-control-label"
                                                           for="property{{ $property->id }}">&nbsp;</label>
                                                </div>

                                                @if ($errors->has("properties.{$property->id}.id"))
                                                    <input type="hidden" class="form-control is-invalid">
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->first("properties.{$property->id}.id") }}</strong>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $property->name }}</td>
                                            <td>{{ $property->currentLease->user->name }}</td>

                                            {{-- Show if utility varied --}}
                                            @if($utility->billing_type == 'varied')
                                                <td>
                                                    <input type="text" name="properties[{{ $property->id }}][previous]"
                                                           class="form-control{{ $errors->has("properties.{$property->id}.previous") ? ' is-invalid' : '' }}"
                                                           value="{{ old("properties.{$property->id}.previous") }}">

                                                    @if ($errors->has("properties.{$property->id}.previous"))
                                                        <div class="invalid-feedback">
                                                            <strong>{{ $errors->first("properties.{$property->id}.previous") }}</strong>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" name="properties[{{ $property->id }}][current]"
                                                           class="form-control{{ $errors->has("properties.{$property->id}.current") ? ' is-invalid' : '' }}"
                                                           value="{{ old("properties.{$property->id}.current") }}">

                                                    @if ($errors->has("properties.{$property->id}.current"))
                                                        <div class="invalid-feedback">
                                                            <strong>{{ $errors->first("properties.{$property->id}.current") }}</strong>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="form-text">Sorry, no properties subscribed to {{ $utility->name }} found.</div>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Send</button>
                        <button type="submit" class="btn btn-secondary" name="cancel" value="1">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection