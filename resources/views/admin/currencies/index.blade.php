@extends('admin.layouts.default')

@section('admin.breadcrumb')
    <li class='breadcrumb-item active'>Currencies</li>
@endsection

@section('admin.content')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <strong>Currencies</strong>

                    <a href="{{ route('admin.currencies.create') }}">Add new currency</a>
                </div>
            </div>
        </div>
    </div>

    @if($currencies->count())
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
                    <th>CC</th>
                    <th>Symbol</th>
                    <th>Usable</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($currencies as $currency)
                    <tr>
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input"
                                       id="currency{{ $currency->id }}" value="{{ $currency->id }}">
                                <label class="custom-control-label" for="currency{{ $currency->id }}">&nbsp;</label>
                            </div>
                        </td>
                        <td>{{ $currency->name }}</td>
                        <td>
                            {{ $currency->cc }}
                        </td>
                        <td>{!! $currency->symbol !!}</td>
                        <td>{{ $currency->usable ? 'Active' : 'Disabled' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.currencies.edit', $currency) }}">
                                    Edit
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.currencies.status', $currency) }}"
                                   onclick="event.preventDefault(); document.getElementById('toggle-status-currency-{{ $currency->id }}').submit()">
                                    {{ $currency->usable ? 'Disable' : 'Activate' }}
                                </a>
                                <a class="btn btn-link" role="button"
                                   href="{{ route('admin.currencies.destroy', $currency) }}"
                                   onclick="event.preventDefault(); document.getElementById('delete-currency-{{ $currency->id }}').submit()">
                                    Delete
                                </a>
                            </div>
                            <form action="{{ route('admin.currencies.status', $currency) }}" method="POST"
                                  id="toggle-status-currency-{{ $currency->id }}" style="display: none">
                                @csrf
                                @method('PUT')
                            </form>
                            <form action="{{ route('admin.currencies.destroy', $currency) }}" method="POST"
                                  id="delete-currency-{{ $currency->id }}" style="display: none">
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
        @if($currencies->perPage() < $currencies->total())
            <nav role="navigation">
                {{ $currencies->links() }}
            </nav>
        @endif
    @else
        <div class="card card-body">
            <div class="card-text">No currencies found.</div>
        </div>
    @endif

@endsection