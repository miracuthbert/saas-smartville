@extends('tenant.layouts.default')

@section('title', page_title("Add Rent Invoice"))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Rent</li>
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.rent.invoices.index') }}">Invoices</a>
    </li>
    <li class='breadcrumb-item active'>Add Invoice</li>
@endsection

@section('tenant.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                Add Rent Invoice
            </h4>

            <form method="POST" action="{{ route('tenant.rent.invoices.store') }}">
                @csrf

                @datepicker([
                    'field_name' => 'start_at',
                    'required' => true,
                    'label' => 'From',
                    'description' => 'Start date for rent invoice.'
                ])
                @enddatepicker


                @datepicker([
                    'field_name' => 'end_at',
                    'required' => true,
                    'label' => 'To',
                    'description' => 'End date for rent invoice.'
                ])
                @enddatepicker

                @datetimepicker([
                    'field_name' => 'sent_at',
                    'required' => true,
                    'label' => 'Send at',
                    'description' => 'Date invoice should be sent to tenants.'
                ])
                @enddatetimepicker

                @datetimepicker([
                    'field_name' => 'due_at',
                    'required' => true,
                    'label' => 'Due at',
                    'description' => 'Date payment due.'
                ])
                @enddatetimepicker

                <div class="form-group row">
                    <label class="col-md-4 control-label">Properties</label>
                    <div class="col-md-8">
                        <div class="form-text">
                            Select properties below to generate and send their respective rent invoice
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($properties as $property)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="properties[{{ $property->id }}][id]"
                                                           class="custom-control-input" id="property{{ $property->id }}"
                                                           value="{{ $property->id }}"
                                                            {{ array_has(old('properties'), $property->id) ? ' checked' : '' }}>
                                                    <label class="custom-control-label"
                                                           for="property{{ $property->id }}">&nbsp;</label>
                                                </div>
                                            </td>
                                            <td>{{ $property->name }}</td>
                                            <td>{{ $property->currentLease->user->name }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="form-text">Sorry, no properties with active tenants found.</div>
                        @endif
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