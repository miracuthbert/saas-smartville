@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Features</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Features</h4>

                    <a href="{{ route('admin.features.create') }}">Add new feature</a>
                </div>
            </div>
        </div>
    </div>

    @if($features->count())
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
                    <th>Added</th>
                    <th>Last edited</th>
                    <th>Usable</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($features as $feature)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="feature{{ $feature->id }}">
                                <label class="custom-control-label" for="feature{{ $feature->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>
                            <span data-toggle="tooltip" title="{{ $feature->overview }}">
                                <i class="icon-info"></i>
                            </span>
                            {{ $feature->name }}
                        </td>
                        <td>{{ $feature->created_at->diffForHumans() }}</td>
                        <td>{{ $feature->edited_at ? $feature->edited_at->diffForHumans() : '' }}</td>
                        <td>{{ $feature->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.features.edit', $feature) }}">
                                    Edit
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.features.status', $feature) }}"
                                   onclick="event.preventDefault(); document.getElementById('toggle-status-feature-{{ $feature->id }}').submit()">
                                    {{ $feature->usable ? 'Disable' : 'Activate' }}
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.features.destroy', $feature) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-feature-{{ $feature->id }}').submit()">
                                    Delete
                                </a>
                            </div>
                            <form action="{{ route('admin.features.status', $feature) }}" method="POST"
                                  id="toggle-status-feature-{{ $feature->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>
                            <form action="{{ route('admin.features.destroy', $feature) }}" method="POST"
                                  id="delete-feature-{{ $feature->id }}" style="display: none">
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
            <div class="card-text">No features found.</div>
        </div>
    @endif
@endsection