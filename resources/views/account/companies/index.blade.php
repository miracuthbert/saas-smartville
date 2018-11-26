@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Companies</h4>
                        <div class="card-subtitle">
                            A list of companies you own or are part of.
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($companies as $company)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $company->name }}

                                <aside>
                                    <div>
                                        <a href="{{ route('tenant.switch', $company) }}" class="btn btn-outline-primary"
                                           role="button">
                                            Dashboard
                                        </a>
                                        {{--<a href="{{ route('account.companies.destroy', $company) }}"--}}
                                           {{--class="btn btn-outline-danger" role="button"--}}
                                           {{--onclick="event.preventDefault(); document.getElementById('delete-company-{{ $company->id }}').submit()">--}}
                                            {{--Delete--}}
                                        {{--</a>--}}
                                    </div>

                                    {{-- Delete Form --}}
                                    {{--<form action="{{ route('account.companies.destroy', $company) }}" method="POST"--}}
                                          {{--id="delete-company-{{ $company->id }}" style="display: none">--}}
                                        {{--@csrf--}}
                                        {{--@method('DELETE')--}}
                                    {{--</form>--}}
                                </aside>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection