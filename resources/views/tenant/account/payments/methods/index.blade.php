@extends('tenant.layouts.default')

@section('title', page_title("Payment Methods"))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Payments</li>
    <li class="breadcrumb-item">Methods</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <div>
                        <h4>Payment Methods</h4>
                        <p>A list of methods that tenants can use to make payments.</p>
                    </div>

                    <aside>
                        <a href="{{ route('tenant.account.payments.methods.create') }}">
                            Add payment method
                        </a>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    @if($methods->count())
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
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($methods as $method)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="amenity{{ $method->id }}">
                                <label class="custom-control-label" for="amenity{{ $method->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $method->name }}</td>
                        <td>{{ $method->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('tenant.account.payments.methods.edit', $method) }}">
                                    <i class="fa fa-edit"></i> <span class="sr-only">Edit</span>
                                </a>
                                <!-- Delete Button -->
                                <a class="nav-link text-danger" role="button"
                                   href="{{ route('tenant.account.payments.methods.destroy', $method) }}"
                                   data-toggle="modal" data-target="#confirmModal"
                                   data-title="Delete Amenity Confirmation"
                                   data-message="Do you want to delete: <strong>{{ $method->name }}</strong> from methods?"
                                   data-warning="This action can not be reversed."
                                   data-type="danger"
                                   data-action="delete-amenity-{{ $method->id }}">
                                    <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                </a>
                            </div>

                            {{-- Delete Form --}}
                            <form action="{{ route('tenant.account.payments.methods.destroy', $method) }}" method="POST"
                                  id="delete-amenity-{{ $method->id }}" style="display: none">
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
        @if($methods->perPage() < $methods->total())
            <nav role="navigation">
                {{ $methods->links() }}
            </nav>
        @endif

        <!-- Confirm Modal -->
        @includeIf('layouts.partials.modals._js_confirm_modal')
    @else
        <div class="card card-body">
            <div class="card-text">No payment methods found.</div>
        </div>
    @endif
@endsection

@push('scripts')
    @includeIf('layouts.partials.modals._script_for_confirm_modal')
@endpush
