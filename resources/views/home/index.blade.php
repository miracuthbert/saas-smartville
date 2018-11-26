@extends('layouts.plain')

@section('content')
    <!-- Jumbotron -->
    <div class="jumbotron jumbotron-fluid bg-success text-light mb-3">
        <div class="container text-center">
            <h1 class="display-3">Simple, fast and easy.</h1>
            <div class="mb-3"></div>

            <p class="lead">
                Add properties, tenants and start sending them invoices.
            </p>
            <div class="mb-3"></div>

            @guest
                <p>
                    <a href="{{ url('/register') }}" class="btn btn-lg btn-light">Get Started</a>
                </p>
            @else
                {{-- do something here --}}
            @endguest
        </div><!-- /.container -->
    </div><!-- /.jumbotron -->

    <!-- Features -->
    @if($features->count())
        <section class="mb-3 py-2">
            <div class="container">
                <div class="row justify-content-center mb-3">
                    <h3>Features</h3>
                </div>
                <div class="card-columns mb-3">
                    @foreach($features as $feature)
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{ $feature->name }}</h4>
                                <div class="card-text">
                                    {{ $feature->overview }}
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    @endforeach
                </div><!-- /.card-columns -->
            </div><!-- /.container -->
        </section><!-- /section -->
    @endif
@endsection
