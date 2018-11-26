@extends('tenant.layouts.default')

@section('title', page_title('Amenities'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Amenities</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Amenities</h4>

                    <aside>
                        <a href="{{ route('tenant.amenities.create') }}">
                            Add amenity
                        </a>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    @if($amenities->count())
        <div class="table-responsive-sm mb-3">
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
                    <th>Properties</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($amenities as $amenity)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="amenity{{ $amenity->id }}">
                                <label class="custom-control-label" for="amenity{{ $amenity->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $amenity->name }}</td>
                        <td>{{ $amenity->all_properties ? 'All' : $amenity->properties->count() }}</td>
                        <td>{{ $amenity->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('tenant.amenities.edit', $amenity) }}">
                                    <i class="fa fa-edit"></i> <span class="sr-only">Edit</span>
                                </a>
                                <!-- Delete Button -->
                                <a class="nav-link text-danger" role="button"
                                   href="{{ route('tenant.amenities.destroy', $amenity) }}"
                                   data-toggle="modal" data-target="#confirmModal"
                                   data-title="Delete Amenity Confirmation"
                                   data-message="Do you want to delete: <strong>{{ $amenity->name }}</strong> from amenities?"
                                   data-warning="This action can not be reversed."
                                   data-type="danger"
                                   data-action="delete-amenity-{{ $amenity->id }}">
                                    <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                </a>
                                <div class="btn-group dropleft" role="group">
                                    <button class="btn btn-link dropdown-toggle dropdown-toggle-split" type="button"
                                            id="amenity-{{ $amenity->id }}-MenuButton" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" title="Lease options">
                                        &nbsp;<i class="icon-options"></i>
                                    </button>

                                    <!-- Amenity options -->
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="amenity-{{ $amenity->id }}-MenuButton">
                                        <h6 class="dropdown-header">More options</h6>
                                        <a class="dropdown-item"
                                           href="#">
                                            Assign to all properties
                                        </a>
                                        <a class="dropdown-item"
                                           href="#">
                                            Remove from properties
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Form --}}
                            <form action="{{ route('tenant.amenities.destroy', $amenity) }}" method="POST"
                                  id="delete-amenity-{{ $amenity->id }}" style="display: none">
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
        @if($amenities->perPage() < $amenities->total())
            <nav role="navigation">
                {{ $amenities->links() }}
            </nav>
        @endif

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')
    @else
        <div class="card card-body">
            <div class="card-text">No amenities found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')
@endpush
