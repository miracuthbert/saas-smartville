@extends('tenant.layouts.default')

@section('title', page_title('Edit Property'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('tenant.properties.index') }}">Properties</a>
    </li>
    <li class='breadcrumb-item active'>Edit Property</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-edit-tab" data-toggle="pill" href="#pills-edit" role="tab"
                           aria-controls="pills-edit" aria-selected="true">
                            Edit
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-features-tab" data-toggle="pill" href="#pills-features" role="tab"
                           aria-controls="pills-features" aria-selected="false">
                            Features
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content border-0" id="pills-tabContent">
                <!-- Edit -->
                <div class="tab-pane fade show active" id="pills-edit" role="tabpanel" aria-labelledby="pills-edit-tab">
                    <form method="POST" action="{{ route('tenant.properties.update', $property) }}" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="control-label col-md-4">Name</label>
                            <div class="col-md-6">
                                <input type="text" name="name"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                                       value="{{ old('name', $property->name) }}" required autofocus>

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

                        @include('tenant.properties.partials.forms._categories', ['category_id' => $property->category_id])

                        <div class="form-group row{{ $errors->has('overview_short') ? ' has-error' : '' }}">
                            <label for="overview_short" class="control-label col-md-4">Overview short</label>
                            <div class="col-md-6">
                                <input type="text" name="overview_short"
                                       class="form-control{{ $errors->has('overview_short') ? ' is-invalid' : '' }}"
                                       id="overview_short"
                                       value="{{ old('overview_short', $property->overview_short) }}"
                                       required>

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
                                           id="size" value="{{ old('size', $property->size) }}" required>

                                    <div class="input-group-append">
                                        <select class="custom-select" id="size_unit" disabled>
                                            <option value="1">sq. ft (Square feet)</option>
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
                                             value="{{ old('price', $property->priceAmount) }}"
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
                                  class="form-control{{ $errors->has('overview') ? ' is-invalid' : '' }}">{{ old('overview', $property->overview) }}</textarea>

                                @if($errors->has('overview'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('overview') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @include('tenant.properties.partials.forms._amenities', compact('property'))

                        @include('tenant.properties.partials.forms._utilities', compact('property'))

                        <div class="form-group row{{ $errors->has('live') ? ' has-error' : '' }}">
                            <label for="live" class="control-label col-md-4">Live</label>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input{{ $errors->has('live') ? ' is-invalid' : '' }}"
                                           type="radio" name="live" id="live_true"
                                           value="1" {{ old('live', $property->live) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="live_true">True (Active)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input{{ $errors->has('live') ? ' is-invalid' : '' }}"
                                           type="radio" name="live" id="live_false"
                                           value="0" {{ old('live', $property->live) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="live_false">False (Disable)</label>
                                </div>

                                <input type="hidden"
                                       class="form-control{{ $errors->has('live') ? ' is-invalid' : '' }}">

                                @if($errors->has('live'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('live') }}</strong>
                                    </div>
                                @endif

                                <small class="form-text">
                                    Set whether property can be used or not.
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Save</button>

                                @if($property->isVacant)
                                    <button type="submit" name="add_tenant" class="btn btn-success" value="1">
                                        Save & Add tenant
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Features -->
                <div class="tab-pane fade" id="pills-features" role="tabpanel" aria-labelledby="pills-features-tab">
                    <tenant-property-features-index
                            endpoint="{{ route('tenant.properties.features.index', $property) }}"></tenant-property-features-index>
                </div>
            </div>
        </div>
    </div>
@endsection