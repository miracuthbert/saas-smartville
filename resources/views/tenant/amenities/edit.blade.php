@extends('tenant.layouts.default')

@section('title', page_title('Edit Amenity'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.amenities.index') }}">Amenities</a>
    </li>
    <li class='breadcrumb-item active'>Edit Amenity</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h4>Edit Amenity</h4>
            </div>

            <form method="POST" action="{{ route('tenant.amenities.update', $amenity) }}" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name', $amenity->name) }}" required autofocus>

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
                                  class="form-control{{ $errors->has('details') ? ' is-invalid' : '' }}">{{ old('details', $amenity->details) }}</textarea>

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
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_true"
                                   value="1" {{ old('usable', $amenity->usable) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_true">True (Active)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input{{ $errors->has('usable') ? ' is-invalid' : '' }}"
                                   type="radio" name="usable" id="usable_false"
                                   value="0" {{ old('usable', $amenity->usable) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="usable_false">False (Disable)</label>
                        </div>

                        <input type="hidden" class="form-control{{ $errors->has('usable') ? ' is-invalid' : '' }}">

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
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="all_properties" class="custom-control-input"
                                   id="all_properties" value="1" {{ old('all_properties', $amenity->all_properties) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="all_properties">
                                Assign to all properties
                            </label>
                        </div>

                        <small class="form-text">
                            Set whether amenity should be added to all properties.
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