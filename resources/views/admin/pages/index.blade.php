@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Pages</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Pages</h4>

                    <a href="{{ route('admin.pages.create') }}">Add new page</a>
                </div>
            </div>
        </div>
    </div>

    @if($pages->count())
        <div class="table-responsive mb-3">
            <table class="table table-hover table-outline mb-0">
                <thead class="thead-light">
                <tr>
                    <th>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="selectAll">
                            <label class="custom-control-label" for="selectAll">&nbsp;</label>
                        </div>
                    </th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Name</th>
                    <th>Template</th>
                    <th>Usable</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="page{{ $page->id }}">
                                <label class="custom-control-label" for="page{{ $page->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{!! paddedNestedString($page->depth) !!}{{ $page->title }}</td>
                        <td>
                            <a href="{{ url($page->uri) }}">
                                {{ $page->prettyUri }}
                            </a>
                        </td>
                        <td>{{ $page->name or 'None' }}</td>
                        <td>{{ $page->template or 'None' }}</td>
                        <td>{{ $page->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.pages.edit', $page) }}">
                                    Edit
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.pages.status', $page) }}"
                                   onclick="event.preventDefault(); document.getElementById('toggle-status-page-{{ $page->id }}').submit()">
                                    {{ $page->usable ? 'Disable' : 'Activate' }}
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.pages.destroy', $page) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-page-{{ $page->id }}').submit()">
                                    Delete
                                </a>
                            </div>
                            <form action="{{ route('admin.pages.status', $page) }}" method="POST"
                                  id="toggle-status-page-{{ $page->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST"
                                  id="delete-page-{{ $page->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card card-body">
            <div class="card-text">No pages found.</div>
        </div>
    @endif
@endsection