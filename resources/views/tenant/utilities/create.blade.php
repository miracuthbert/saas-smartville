@extends('tenant.layouts.default')

@section('title', page_title('Add Utility'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.utilities.index') }}">Utilities</a>
    </li>
    <li class='breadcrumb-item active'>Add Utility</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h4>Add Utility</h4>
                <p>This is a paid for service that compliments a leased property.</p>
            </div>

            <form method="POST" action="{{ route('tenant.utilities.store') }}" autocomplete="off">
                @csrf

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name') }}" required autofocus>

                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label for="price" class="control-label col-md-4">Price</label>
                    <div class="col-md-6">
                        <price-input name="price"
                                     id="price"
                                     value="{{ old('price') }}"
                                     error="{{ $errors->first('price') }}"
                                     currency="{{ request()->tenant()->currency }}"
                                     required="true"></price-input>
                        <input type="hidden" name="currency" id="currency" value="{{ request()->tenant()->currency }}">
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

                <div class="form-group row{{ $errors->has('billing_type') ? ' has-error' : '' }}">
                    <label for="billing_type" class="control-label col-md-4">Billing type</label>
                    <div class="col-md-6">
                        @foreach($billingTypes as $key => $type)
                            <div class="form-check form-check-inline">
                                @if(old('billing_type') == $key)
                                    <input type="radio" name="billing_type"
                                           class="form-check-input{{ $errors->has('billing_type') ? ' is-invalid' : '' }}"
                                           id="billing_type_{{ $key }}" value="{{ $key }}" checked>
                                @else
                                    <input class="form-check-input{{ $errors->has('billing_type') ? ' is-invalid' : '' }}"
                                           type="radio" name="billing_type" id="billing_type_{{ $key }}"
                                           value="{{ $key }}">
                                @endif
                                <label class="form-check-label" for="billing_type_{{ $key }}">{{ $type }}</label>
                            </div>
                        @endforeach
                        <input type="hidden"
                               class="form-control{{ $errors->has('billing_type') ? ' is-invalid' : '' }}">

                        @if($errors->has('billing_type'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_type') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            Set whether utility billing charge is fixed or varies.
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('billing_interval') ? ' has-error' : '' }}">
                    <label for="billing_interval" class="control-label col-md-4">Billing interval</label>
                    <div class="col-md-6">
                        <select name="billing_interval"
                                class="form-control{{ $errors->has('billing_interval') ? ' is-invalid' : '' }}"
                                id="billing_interval" required>
                            <option value=""> --- Select billing interval ---</option>
                            @foreach($billingIntervals as $key => $interval)
                                <option value="{{ $key }}" {{ old('billing_interval') == $key ? ' selected' : '' }}>
                                    {{ $interval }}
                                </option>
                            @endforeach
                        </select>

                        @if($errors->has('billing_interval'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_interval') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">The interval between every billing.</small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('billing_duration') ? ' has-error' : '' }}">
                    <label for="billing_duration" class="control-label col-md-4">Billing duration</label>
                    <div class="col-md-6">
                        <input type="number" name="billing_duration"
                               class="form-control{{ $errors->has('billing_duration') ? ' is-invalid' : '' }}"
                               id="billing_duration" value="{{ old('billing_duration') }}" min="1" max="365" required>

                        @if($errors->has('billing_duration'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_duration') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">The interval between every billing cycle.</small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('billing_day') ? ' has-error' : '' }}">
                    <label for="billing_day" class="control-label col-md-4">Billing day</label>
                    <div class="col-md-6">
                        <input type="number" name="billing_day"
                               class="form-control{{ $errors->has('billing_day') ? ' is-invalid' : '' }}"
                               id="billing_day" value="{{ old('billing_day') }}" min="1" max="31" required>

                        @if($errors->has('billing_day'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_day') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            The day of month bills should be generated or generate reminder should be sent.
                            Auto generate works only for fixed type bills.
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('billing_due') ? ' has-error' : '' }}">
                    <label for="billing_due" class="control-label col-md-4">Bill due</label>
                    <div class="col-md-6">
                        <input type="number" name="billing_due"
                               class="form-control{{ $errors->has('billing_due') ? ' is-invalid' : '' }}"
                               id="billing_due" value="{{ old('billing_due') }}" min="1" required>

                        @if($errors->has('billing_due'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('billing_due') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            The number of days bill should be paid after invoice is sent out.
                        </small>
                    </div>
                </div>

                <fieldset
                        class="varied-wrapper{{ old('billing_type') === 'fixed' ? ' d-none' : '' }}">
                    <div class="form-group row{{ $errors->has('billing_unit') ? ' has-error' : '' }}">
                        <label for="billing_unit" class="control-label col-md-4">Bill unit</label>
                        <div class="col-md-6">
                            <input type="text" name="billing_unit"
                                   class="form-control{{ $errors->has('billing_unit') ? ' is-invalid' : '' }}"
                                   id="billing_unit" value="{{ old('billing_unit') }}">

                            @if($errors->has('billing_unit'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('billing_unit') }}</strong>
                                </div>
                            @endif

                            <small class="form-text text-muted">
                                The unit of measure for varied bills eg. m<sup>3</sup>, hrs, etc.
                            </small>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable" class="control-label col-md-4">Usable</label>
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_true"
                                   value="1" {{ old('usable') == 1 || !(old('usable')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_true">True (Active)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_false"
                                   value="0" {{ old('usable') == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_false">False (Disable)</label>
                        </div>

                        <input type="hidden" class="form-control{{ $errors->has('usable') ? ' is-invalid' : '' }}">

                        @if($errors->has('usable'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('usable') }}</strong>
                            </div>
                        @endif

                        <small class="form-text">
                            Set whether utility can be used or not.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="all_properties" class="custom-control-input"
                                   id="all_properties" value="1" {{ old('all_properties') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="all_properties">
                                Add to all properties
                            </label>
                        </div>

                        <small class="form-text">
                            Set whether utility should be added to all properties.
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

@push('scripts')
    <script>
        $('input[name="billing_type"]').on('change', function () {
            if ($(this).val() !== 'fixed') {
                $('.varied-wrapper').removeClass('d-none');
                return;
            }

            $('.varied-wrapper').addClass('d-none');
        });
    </script>
@endpush
