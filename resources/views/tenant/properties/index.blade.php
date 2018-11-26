@extends('tenant.layouts.default')

@section('title', page_title('Properties'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Properties</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Properties</h4>

                    <aside>
                        <div class="btn-group" role="group">
                            <a href="{{ route('tenant.properties.create.start') }}" class="btn btn-link">
                                Add Property
                            </a>
                            <!-- Filters link -->
                            <a href="#filters" data-toggle="collapse" aria-expanded="false" aria-controls="filters"
                               class="btn btn-link" id="toggle-filters">
                                Filters
                            </a>
                        </div>
                    </aside>
                </div>
            </div>

            {{-- Filters --}}
            @include('tenant.properties.partials._filters')
        </div>
    </div>

    @if($properties->count())
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
                    <th>Category</th>
                    <th>Price</th>
                    <th><span data-toggle="tooltip" title="Square feet">Size (sq. ft)</span></th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($properties as $property)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="property{{ $property->id }}">
                                <label class="custom-control-label" for="property{{ $property->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->category->name }}</td>
                        <td>
                            {{ $property->formattedPrice }}
                        </td>
                        <td>{{ $property->size }}</td>
                        <td>{{ $property->status }}</td>
                        <td>{{ $property->isVacant ? 'Vacant' : 'Occupied' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('tenant.properties.edit', $property) }}">
                                    <i class="fa fa-edit"></i> <span class="sr-only">Edit</span>
                                </a>
                                @if($property->isVacant)
                                    <a class="btn btn-link" role="button"
                                       href="{{ route('tenant.properties.status', $property) }}"
                                       onclick="event.preventDefault(); document.getElementById('toggle-status-property-{{ $property->id }}').submit()">
                                        {{ $property->live ? 'Disable' : 'Activate' }}
                                    </a>
                                    {{-- Delete Button --}}
                                    <a class="nav-link text-danger" role="button"
                                       href="{{ route('tenant.properties.destroy', $property) }}"
                                       data-toggle="modal" data-target="#confirmModal"
                                       data-title="Delete Property Confirmation"
                                       data-message="Do you want to delete: <strong>{{ $property->name }}</strong> from properties?"
                                       data-warning="This action can not be reversed."
                                       data-type="danger"
                                       data-action="delete-property-{{ $property->id }}">
                                        <div data-toggle="tooltip" title="Delete {{ $property->name}}">
                                            <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                        </div>
                                    </a>
                                @endif
                                <div class="btn-group dropleft" role="group">
                                    <button class="btn btn-link dropdown-toggle dropdown-toggle-split" type="button"
                                            id="property-{{ $property->id }}-MenuButton" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" title="Property options">
                                        <i class="icon-options"></i>
                                    </button>

                                    <!-- Property options -->
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="property-{{ $property->id }}-MenuButton">
                                        <h6 class="dropdown-header">More options</h6>
                                        <a class="dropdown-item"
                                           href="{{ route('tenant.properties.invitations.create', $property) }}">
                                            Add Tenant
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Toggle Status Form --}}
                            <form action="{{ route('tenant.properties.status', $property) }}" method="POST"
                                  id="toggle-status-property-{{ $property->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>

                            {{-- Delete Form --}}
                            <form action="{{ route('tenant.properties.destroy', $property) }}" method="POST"
                                  id="delete-property-{{ $property->id }}" style="display: none">
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
        @if($properties->perPage() < $properties->total())
            <nav role="navigation">
                {{ $properties->appends(request()->query())->links() }}
            </nav>
        @endif

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')
    @else
        <div class="card card-body">
            <div class="card-text">No properties found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')

    <script>
        var button = $('#toggle-filters').first() // Button that triggered the collapse

        $('#filters').on('shown.bs.collapse', function (event) {
            button.html("Hide Filters")
        }).on('hide.bs.collapse', function (event) {
            button.html("Filters")
        })
    </script>
@endpush
