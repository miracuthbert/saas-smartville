@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item'>
        <a href="{{ route('admin.pages.index') }}">Pages</a>
    </li>
    <li class='breadcrumb-item active'>Create</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Add new page</h4>

            <form action="{{ route('admin.pages.store') }}" method="post">
                @csrf

                <div class="form-group row{{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="control-label col-md-4">{{ trans('admin.pages.form.labels.title') }}</label>
                    <div class="col-md-6">
                        <input type="text" name="title"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title"
                               value="{{ old('title') }}" required autofocus>

                        @if($errors->has('title'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('title') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('uri') ? ' has-error' : '' }}">
                    <label for="uri" class="control-label col-md-4">{{ trans('admin.pages.form.labels.uri') }}</label>
                    <div class="col-md-6">
                        <input type="text" name="uri"
                               class="form-control{{ $errors->has('uri') ? ' is-invalid' : '' }}" id="uri"
                               value="{{ old('uri') }}" required>

                        @if($errors->has('uri'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('uri') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            {{ trans('admin.pages.form.text.uri') }}
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="control-label col-md-4">{{ trans('admin.pages.form.labels.name') }}</label>
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
                            {{ trans('admin.pages.form.text.name') }}
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="hidden" class="custom-control-input"
                                   id="hidden" value="1" {{ old('hidden') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="hidden">
                                {{ trans('admin.pages.form.labels.hidden') }}
                            </label>
                        </div>

                        <small class="form-text">
                            {{ trans('admin.pages.form.text.hidden') }}
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('template') ? ' has-error' : '' }}">
                    <label for="template" class="control-label col-md-4">{{ trans('admin.pages.form.labels.template') }}</label>
                    <div class="col-md-4">
                        <select name="template" id="template"
                                class="custom-select form-control{{ $errors->has('template') ? ' is-invalid' : '' }}">
                            <option value=""> --- Select page template ---</option>

                            @foreach($templates as $template)
                                <option value="{{ $template }}"
                                        {{ old('template') == $template ? 'selected' : '' }}>
                                    {{ $template }}
                                </option>
                            @endforeach
                        </select>

                        @if($errors->has('template'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('template') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            {{ trans('admin.pages.form.text.template') }}
                        </small>
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('layout') ? ' has-error' : '' }}">
                    <label for="layout" class="control-label col-md-4">{{ trans('admin.pages.form.labels.layout') }}</label>
                    <div class="col-md-4">
                        <select name="layout" id="layout"
                                class="custom-select form-control{{ $errors->has('layout') ? ' is-invalid' : '' }}">
                            <option value=""> --- Select page layout ---</option>

                            @foreach($layouts as $layout)
                                <option value="{{ $layout }}"
                                        {{ old('layout', config('cms.theme.layout')) == $layout ? 'selected' : '' }}>
                                    {{ $layout }}
                                </option>
                            @endforeach
                        </select>

                        @if($errors->has('layout'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('layout') }}</strong>
                            </div>
                        @endif

                        <small class="form-text text-muted">
                            {{ trans('admin.pages.form.text.layout') }}
                        </small>
                    </div>
                </div>

                <fieldset>
                    <div class="form-group row">
                        <div class="col-md-4"><label>{{ trans('admin.pages.form.labels.order') }}</label></div>
                        <div class="col-md-2">
                            <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
                                <label for="order">Order</label>
                                <select name="order" id="order"
                                        class="custom-select form-control{{ $errors->has('order') ? ' is-invalid' : '' }}">
                                    <option value=""> --- </option>
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
                                <label for="node">Page</label>
                                <select name="node" id="node"
                                        class="custom-select form-control{{ $errors->has('node') ? ' is-invalid' : '' }}">
                                    <option value=""> --- </option>

                                    @foreach($pages as $parent)
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
                                {{ trans('admin.pages.form.text.order') }}
                            </small>
                        </div>
                    </div>
                </fieldset>

                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                    <label for="body">{{ trans('admin.pages.form.labels.body') }}</label>
                    <textarea name="body" id="body" rows="5"
                              class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}">{{ old('body') }}</textarea>

                    @if($errors->has('body'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('body') }}</strong>
                        </div>
                    @endif

                    <small class="form-text text-muted">
                        {{ trans('admin.pages.form.text.body') }}
                    </small>
                </div>

                <div class="form-group row{{ $errors->has('usable') ? ' has-error' : '' }}">
                    <label for="usable" class="control-label col-md-4">{{ trans('admin.pages.form.labels.usable') }}</label>
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
                            {{ trans('admin.pages.form.text.usable') }}
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
        var simplemde = new SimpleMDE({
            element: document.getElementById("body")[0]
        }).render();
    </script>
@endpush
