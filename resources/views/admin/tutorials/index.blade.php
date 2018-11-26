@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Tutorials</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Tutorials</h4>

                    <a href="{{ route('admin.tutorials.create') }}">Add new tutorial</a>
                </div>
            </div>
        </div>
    </div>

    @if($tutorials->count())
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
                    <th>Parent</th>
                    <th>Usable</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tutorials as $tutorial)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="tutorial{{ $tutorial->id }}">
                                <label class="custom-control-label" for="tutorial{{ $tutorial->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $tutorial->title }}</td>
                        <td>{{ optional($tutorial->parent)->title }}</td>
                        <td>{{ $tutorial->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.tutorials.edit', $tutorial) }}">
                                    Edit
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.tutorials.status', $tutorial) }}"
                                   onclick="event.preventDefault(); document.getElementById('toggle-status-tutorial-{{ $tutorial->id }}').submit()">
                                    {{ $tutorial->usable ? 'Disable' : 'Activate' }}
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.tutorials.destroy', $tutorial) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-tutorial-{{ $tutorial->id }}').submit()">
                                    Delete
                                </a>
                            </div>
                            <form action="{{ route('admin.tutorials.status', $tutorial) }}" method="POST"
                                  id="toggle-status-tutorial-{{ $tutorial->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>
                            <form action="{{ route('admin.tutorials.destroy', $tutorial) }}" method="POST"
                                  id="delete-tutorial-{{ $tutorial->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <nav role="navigation">
            {{ $tutorials->links() }}
        </nav>
    @else
        <div class="card card-body">
            <div class="card-text">No tutorials found.</div>
        </div>
    @endif
@endsection