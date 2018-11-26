@extends('tenant.layouts.default')

@section('title', page_title('Add Property'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.properties.index') }}">Properties</a>
    </li>
    <li class='breadcrumb-item active'>Add Property</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <h4>Add Property</h4>
            </div>

            <form method="POST" action="{{ route('tenant.properties.store', $property) }}" autocomplete="off">
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

                <property-image-upload send-as="upload"
                                       endpoint="{{ route('tenant.properties.image.store', $property) }}"
                                       property-image="{{ $property->image }}"
                                       image-url="{{ $property->imageUrl }}"></property-image-upload>

                @include('tenant.properties.partials.forms._categories')

                <div class="form-group row{{ $errors->has('overview_short') ? ' has-error' : '' }}">
                    <label for="overview_short" class="control-label col-md-4">Overview short</label>
                    <div class="col-md-6">
                        <input type="text" name="overview_short"
                               class="form-control{{ $errors->has('overview_short') ? ' is-invalid' : '' }}"
                               id="overview_short" value="{{ old('overview_short') }}" required>

                        @if($errors->has('overview_short'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('overview_short') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('size') ? ' has-error' : '' }}">
                    <label for="size" class="control-label col-md-4">Size</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="size"
                                   class="form-control{{ $errors->has('size') ? ' is-invalid' : '' }}"
                                   id="size" value="{{ old('size') }}" required>

                            <div class="input-group-append">
                                <select class="custom-select" id="size_unit" disabled>
                                    <option value="1" selected>sq. ft (Square feet)</option>
                                    {{--<option value="2">sq. m (Square meters)</option>--}}
                                </select>
                            </div>

                            @if($errors->has('size'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('size') }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('price') ? ' has-error' : '' }}">
                    <label for="price" class="control-label col-md-4">Price</label>
                    <div class="col-md-6">
                        <price-input name="price"
                                     id="price"
                                     value="{{ old('price') }}"
                                     error="{{ $errors->first('price') }}"
                                     currency="{{ $property->currency }}"
                                     required="true"></price-input>

                        <input type="hidden" name="currency" id="currency" value="{{ $property->currency }}">
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('overview') ? ' has-error' : '' }}">
                    <label for="overview" class="control-label col-md-4">Overview</label>
                    <div class="col-md-6">
                        <textarea name="overview" id="overview" rows="5"
                                  class="form-control{{ $errors->has('overview') ? ' is-invalid' : '' }}">{{ old('overview') }}</textarea>

                        @if($errors->has('overview'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('overview') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                @include('tenant.properties.partials.forms._amenities', compact('property'))

                @include('tenant.properties.partials.forms._utilities', compact('property'))

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection