@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.tutorials.index') }}">Tutorials</a>
    </li>
    <li class='breadcrumb-item active'>Create</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add new tutorial</h4>

            <form action="{{ route('admin.tutorials.store') }}" method="post">
                @csrf

                <div class="form-group row{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title"
                           class="control-label col-md-4">{{ trans('admin.tutorials.form.labels.title') }}</label>
                    <div class="col-md-6">
                        <input type="text" name="title"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title"
                               value="{{ old('title') }}" maxlength="250" required autofocus>

                        @if($errors->has('title'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('title') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('overview') ? ' has-error' : '' }}">
                    <label for="overview" class="col-md-4">{{ trans('admin.tutorials.form.labels.overview') }}</label>
                    <div class="col-md-6">
                        <textarea name="overview" id="overview" rows="3" maxlength="300"
                                  class="form-control{{ $errors->has('overview') ? ' is-invalid' : '' }}">{{ old('overview') }}</textarea>

                        @if($errors->has('overview'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('overview') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            {{ trans('admin.tutorials.form.text.overview') }}
                        </small>
                    </div>
                </div>

                <fieldset>
                    <div class="form-group row">
                        <label class="col-md-4">{{ trans('admin.tutorials.form.labels.order') }}</label>

                        <div class="col-md-2">
                            <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
                                <label for="order">Order</label>
                                <select name="order" id="order"
                                        class="custom-select form-control{{ $errors->has('order') ? ' is-invalid' : '' }}">
                                    <option value=""> ---</option>
                                    <option value="before" {{ old('order') == 'before' ? 'selected' : '' }}>Before
                                    </option>
                                    <option value="after" {{ old('order') == 'after' ? 'selected' : '' }}>After</option>
                                    <option value="child" {{ old('order') == 'child' ? 'selected' : '' }}>Child Of
                                    </option>
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
                                <label for="node">Tutorial</label>
                                <select name="node" id="node"
                                        class="custom-select form-control{{ $errors->has('node') ? ' is-invalid' : '' }}">
                                    <option value=""> ---</option>

                                    @foreach($tutorials as $parent)
                                        <option value="{{ $parent->id }}"
                                                {{ old('node') == $parent->id ? 'selected' : '' }}>
                                            {!! paddedNestedString($parent->depth) !!}{{ $parent->title }}
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
                                {{ trans('admin.tutorials.form.text.order') }}
                            </small>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group row{{ $errors->has('body') ? ' has-error' : '' }}">
                    <label for="body" class="col-md-4">{{ trans('admin.tutorials.form.labels.body') }}</label>

                    <div class="col-md-6">
                        <textarea name="body" id="body" rows="5"
                                  class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}">{{ old('body') }}</textarea>

                        @if($errors->has('body'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('body') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            {{ trans('admin.tutorials.form.text.body') }}
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable"
                           class="control-label col-md-4">{{ trans('admin.tutorials.form.labels.usable') }}</label>
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
                            {{ trans('admin.tutorials.form.text.usable') }}
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
    {{--<script>--}}
    {{--var simplemde = new SimpleMDE({--}}
    {{--element: document.getElementById("body")--}}
    {{--}).render();--}}
    {{--</script>--}}
@endpush
