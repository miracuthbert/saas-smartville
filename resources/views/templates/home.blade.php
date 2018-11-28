<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-success text-light mb-3">
    <div class="container text-center">
        <h1 class="display-3">Invoicing for Rentals</h1>
        <div class="mb-3"></div>

        <p class="lead text-capitalize">
            Add properties &amp; tenants then start sending invoices within minutes.
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
    <section class="mb-5 py-2">
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

            <div class="row justify-content-center mb-3">
                <a href="{{ url('/features') }}" class="btn btn-lg btn-link" data-toggle="tooltip"
                   title="View more features">
                    <i class="icon-options"></i> <span class="sr-only">View more features</span>
                </a>
            </div>
        </div><!-- /.container -->
    </section><!-- /section -->
@endif

<!-- Contact -->
<section class="bg-primary pb-5 text-light">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="bg-light px-5 py-3 rounded-bottom text-dark">
                    <contact endpoint="{{ route('support.contact.store') }}"
                             :heading-styles="{{ json_encode(['text-center']) }}"/>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</section><!-- /section -->
