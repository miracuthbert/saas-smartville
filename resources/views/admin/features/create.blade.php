@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.features.index') }}">Features</a>
    </li>
    <li class='breadcrumb-item active'>Create</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add new feature</h4>

            <form action="{{ route('admin.features.store') }}" method="post">
                @csrf

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">Name</label>
                    <div class="col-md-6">
                        <input type="text" name="name"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                               value="{{ old('name') }}">

                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            The name is to generate links to the feature.
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('overview') ? ' has-error' : '' }}">
                    <label for="overview" class="control-label col-md-4">Overview</label>
                    <div class="col-md-6">
                        <input type="text" name="overview"
                               class="form-control{{ $errors->has('overview') ? ' is-invalid' : '' }}" id="overview"
                               value="{{ old('overview') }}" maxlength="160" required>

                        @if($errors->has('overview'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('overview') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            A short description of the feature.
                        </small>
                    </div>
                </div>

                <fieldset>
                    <div class="form-group row">
                        <div class="col-md-4"><label>Feature order</label></div>
                        <div class="col-md-2">
                            <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
                                <label for="order">Order</label>
                                <select name="order" id="order"
                                        class="custom-select form-control{{ $errors->has('order') ? ' is-invalid' : '' }}">
                                    <option value=""> --- </option>
                                    <option value="before" {{ old('order') == 'before' ? 'selected' : '' }}>Before
                                    </option>
                                    <option value="after" {{ old('order') == 'after' ? 'selected' : '' }}>After</option>
                                </select>

                                @if($errors->has('order'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('order') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group row{{ $errors->has('node') ? ' has-error' : '' }}">
                                <label for="node">Page</label>
                                <select name="node" id="node"
                                        class="custom-select form-control{{ $errors->has('node') ? ' is-invalid' : '' }}">
                                    <option value=""> --- </option>

                                    @foreach($features as $node)
                                        <option value="{{ $node->id }}"
                                                {{ old('node') == $node->id ? 'selected' : '' }}>
                                            {!! paddedNestedString($node->depth) !!}{{ $node->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @if($errors->has('node'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('node') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col offset-md-4">
                            <small class="form-text text-muted">
                                The order by which feature appears. &ast;Leave blank for default order.
                            </small>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group row{{ $errors->has('description') ? ' has-error' : '' }}">
                    <label for="description" class="control-label col-md-4">Description</label>
                    <div class="col-md-6">
                        <textarea name="description" id="description" rows="5"
                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{ old('description') }}</textarea>

                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('description') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable" class="control-label col-md-4">Usable</label>
                    <div class="col-md-6">
                        <select name="usable" id="usable"
                                class="custom-select form-control{{ $errors->has('usable') ? ' is-invalid' : '' }}">
                            <option value="0"{{ old('usable') == 0 ? ' selected' : '' }}>Disable
                            </option>
                            <option value="1"{{ old('usable') == 1 ? ' selected' : '' }}>Active
                            </option>
                        </select>

                        @if($errors->has('usable'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('usable') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            Set whether feature is live or not.
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

{{--@push('scripts')--}}
{{--<script>--}}
{{--var simplemde = new SimpleMDE({--}}
{{--element: document.getElementById("description")--}}
{{--}).render();--}}
{{--</script>--}}
{{--@endpush--}}
