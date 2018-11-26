@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Categories</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <strong>Categories</strong>

                    <a href="{{ route('admin.categories.create') }}">Add new category</a>
                </div>
            </div>
        </div>
    </div>

    @if($categories->count())
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
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Usable</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="category{{ $category->id }}">
                                <label class="custom-control-label" for="category{{ $category->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>
                            {{ $category->parent ? $category->parent->name : null }}
                        </td>
                        <td>{{ $category->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.categories.edit', $category) }}">
                                    Edit
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.categories.status', $category) }}"
                                   onclick="event.preventDefault(); document.getElementById('toggle-status-category-{{ $category->id }}').submit()">
                                    {{ $category->usable ? 'Disable' : 'Activate' }}
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.categories.destroy', $category) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-category-{{ $category->id }}').submit()">
                                    Delete
                                </a>
                            </div>
                            <form action="{{ route('admin.categories.status', $category) }}" method="POST"
                                  id="toggle-status-category-{{ $category->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                  id="delete-category-{{ $category->id }}" style="display: none">
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
            <div class="card-text">No categories found.</div>
        </div>
    @endif
@endsection