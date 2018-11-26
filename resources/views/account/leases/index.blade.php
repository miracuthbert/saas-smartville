@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div class="mb-3">
        <h4 class="card-title">My Leases</h4>
    </div>
    <hr>

    @if($leases->count())
        <div class="table-responsive-sm mb-3">
            <table class="table table-hover table-borderless">
                <thead>
                <tr>
                    <th>Property</th>
                    <th>Moved in</th>
                    <th>Moved out</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($leases as $lease)
                    <tr>
                        <td>{{ $lease->property->name }}</td>
                        <td>{{ $lease->formattedMoveIn }}</td>
                        <td>{{ $lease->hasVacated ? $lease->formattedMoveOut : '' }}</td>
                        <td>{{ $lease->status }}</td>
                        <td>
                            <div class="nav">
                                <a href="{{ route('account.leases.show', $lease) }}" class="nav-item nav-link">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($leases->perPage() < $leases->total())
            <div class="mb-3">
                {{ $leases->links() }}
            </div>
        @endif
    @else
        <div class="card card-body">
            <div class="card-text">No leases found.</div>
        </div>
    @endif
@endsection
