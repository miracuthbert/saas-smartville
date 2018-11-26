@extends('tenant.layouts.default')

@section('title', page_title('Tenants'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Tenants</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <h4>Tenants</h4>

                    <aside>
                        <a href="{{ route('tenant.tenants.create') }}">
                            Add new tenant
                        </a>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    @if($tenants->count())
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
                    <th>Property</th>
                    <th>Moved in</th>
                    <th>Moved out</th>
                    <th>Vacated at</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($tenants as $tenant)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="tenant{{ $tenant->id }}">
                                <label class="custom-control-label" for="tenant{{ $tenant->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ optional($tenant->user)->name ?: $tenant->invitation->name }}</td>
                        <td>{{ $tenant->property->name }}</td>
                        <td>{{ $tenant->local_moved_in_at['date'] }}</td>
                        <td>
                            <span data-toggle="tooltip" title="Tenant move out">
                                {{ $tenant->local_moved_out_at['date'] }}
                            </span>
                        </td>
                        <td>{{ $tenant->local_vacated_at['date'] }}</td>
                        <td>
                            <span data-toggle="tooltip"
                                  data-title="{{ $tenant->statusMessage }}">
                                {{ $tenant->status }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('tenant.tenants.edit', $tenant) }}">
                                    View
                                </a>
                                <!-- Delete Button -->
                                <a class="nav-link text-danger" role="button"
                                   href="{{ route('tenant.properties.destroy', $tenant) }}"
                                   data-toggle="modal" data-target="#confirmModal"
                                   data-title="Delete Tenant Lease Confirmation"
                                   data-message="Do you want to delete lease #:
                                    <strong>{{ $tenant->identifier }}</strong> for
                                    <strong>{{ $tenant->property->name }}</strong> from
                                    <strong>{{ optional($tenant->user)->name ?: $tenant->invitation->name }}'s</strong> leases?"
                                   data-warning="This action can not be reversed."
                                   data-type="danger"
                                   data-action="delete-tenant-{{ $tenant->id }}">
                                    <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                </a>
                                <div class="btn-group dropleft" role="group">
                                    <button class="btn btn-link dropdown-toggle dropdown-toggle-split" type="button"
                                            id="tenant-{{ $tenant->id }}-MenuButton" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" title="Tenant lease options">
                                        &nbsp;<i class="icon-options"></i>
                                    </button>

                                    <!-- Lease options -->
                                    <div class="dropdown-menu dropdown-menu-right"
                                         aria-labelledby="tenant-{{ $tenant->id }}-MenuButton">
                                        <h6 class="dropdown-header">More options</h6>
                                        @if(!$tenant->finishedSetup)
                                            <a class="dropdown-item"
                                               href="{{ route('tenant.tenants.invitation.resend', $tenant) }}"
                                               onclick="event.preventDefault(); document.getElementById('resend-tenant-invitation-{{ $tenant->id }}').submit()">
                                                Resend Activation Email
                                            </a>
                                        @else
                                            @if(!$tenant->hasVacated)
                                                <a href="{{ route('tenant.tenants.vacate.index', $tenant) }}"
                                                   class="dropdown-item">
                                                    Vacate Tenant
                                                </a>
                                            @endif
                                            <a class="dropdown-item" href="#" data-toggle="tooltip"
                                               title="@lang('globals.features.coming_soon')">
                                                Notices
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Form --}}
                            <form action="{{ route('tenant.tenants.destroy', $tenant) }}" method="POST"
                                  id="delete-tenant-{{ $tenant->id }}" style="display: none">
                                @csrf
                                @method('DELETE')
                            </form>

                            @if(!$tenant->finishedSetup)
                                {{--Resend Tenant Invitation Form--}}
                                <form action="{{ route('tenant.tenants.invitation.resend', $tenant) }}" method="POST"
                                      id="resend-tenant-invitation-{{ $tenant->id }}" style="display: none">
                                    @csrf
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tenants->perPage() < $tenants->total())
            <nav class="navigation">
                {{ $tenants->appends(request()->query())->links() }}
            </nav>
        @endif

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')
    @else
        <div class="card card-body">
            <div class="card-text">No tenants found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')
@endpush
