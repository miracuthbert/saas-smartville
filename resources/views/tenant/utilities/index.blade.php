@extends('tenant.layouts.default')

@section('title', page_title('Utilities'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Utilities</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <section>
                        <h4>Utilities</h4>
                        <p>These are paid for services that compliment leased properties.</p>
                    </section>

                    <aside>
                        <a href="{{ route('tenant.utilities.create') }}">
                            Add utility
                        </a>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    @if($utilities->count())
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
                    <th>Price</th>
                    <th>Type</th>
                    <th>Interval</th>
                    <th>Unit</th>
                    <th>Properties</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($utilities as $utility)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="utility{{ $utility->id }}">
                                <label class="custom-control-label" for="utility{{ $utility->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $utility->name }}</td>
                        <td>
                            {{ $utility->formattedPrice }}
                        </td>
                        <td>
                            {{ $utility->formattedBillingType }}
                        </td>
                        <td>
                            {{ $utility->formattedBillingInterval }}
                        </td>
                        <td>
                            {{ $utility->formattedBillingUnit }}
                        </td>
                        <td>{{ $utility->all_properties ? 'All' : $utility->properties->count() }}</td>
                        <td>{{ $utility->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('tenant.utilities.edit', $utility) }}">
                                    <i class="fa fa-edit"></i> <span class="sr-only">Edit</span>
                                </a>
                                <!-- Delete Button -->
                                <a class="nav-link text-danger" role="button"
                                   href="{{ route('tenant.utilities.destroy', $utility) }}"
                                   data-toggle="modal" data-target="#confirmModal"
                                   data-title="Delete Utility Confirmation"
                                   data-message="Do you want to delete: <strong>{{ $utility->name }}</strong> from utilities?"
                                   data-warning="Utility will only be removed if no invoices are associated with it."
                                   data-type="danger"
                                   data-action="delete-utility-{{ $utility->id }}">
                                    <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                </a>
                                <div class="btn-group dropleft" role="group">
                                    <button class="btn btn-link dropdown-toggle dropdown-toggle-split" type="button"
                                            id="utility-{{ $utility->id }}-MenuButton" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" title="Lease options">
                                        &nbsp;<i class="icon-options"></i>
                                    </button>

                                    <!-- Amenity options -->
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="utility-{{ $utility->id }}-MenuButton">
                                        <h6 class="dropdown-header">More options</h6>
                                        <a class="dropdown-item"
                                           href="#">
                                            Assign to all properties
                                        </a>
                                        <a class="dropdown-item"
                                           href="#">
                                            Remove from all properties
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Form --}}
                            <form action="{{ route('tenant.utilities.destroy', $utility) }}" method="POST"
                                  id="delete-utility-{{ $utility->id }}" style="display: none">
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
        @if($utilities->perPage() < $utilities->total())
            <div class="mb-3">
                {{ $utilities->links() }}
            </div>
        @endif

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')
    @else
        <div class="card card-body">
            <div class="card-text">No utilities found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')
@endpush
