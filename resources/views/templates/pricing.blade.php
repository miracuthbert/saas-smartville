<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-primary text-light mb-3">
    <div class="container text-center">
        <h1 class="display-3">{{ $page->title }}</h1>

        <p class="lead">
            Checkout the variety of plans we offer below.
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
<section class="mb-3 py-2">
    <div class="container">
        <div class="col-md-8 offset-md-2">
            @if($plans->count())
                <div class="card-deck mb-3">
                    @foreach($plans as $plan)
                        <div class="card text-center">
                            <div class="card-body">
                                <h1 class="card-title display-3">
                                    {{ $plan->formattedPrice }}
                                </h1>
                                <p class="h4">{{ $plan->name }}</p>
                            </div><!-- /.card-body -->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <p class="h5">Team limit</p>
                                </li>
                                <li class="list-group-item">
                                    <p class="h3">{{ $plan->teams_limit }}</p>
                                </li>
                                <li class="list-group-item">
                                    <p class="h5">Other features</p>
                                </li>
                                <li class="list-group-item">Basic Features</li>
                                <li class="list-group-item">Custom Reports</li>
                                <li class="list-group-item">Custom Invoices</li>
                                <li class="list-group-item">Joined Invoices(Rent + Utilities)</li>
                                <li class="list-group-item">Pages</li>
                            </ul><!-- /.list-group -->
                        </div><!-- /.card -->
                    @endforeach
                </div><!-- /.card-columns -->
            @else
                <p class="lead">Whoops! Something is wrong.</p>
            @endif
        </div><!-- /.col -->
    </div><!-- /.container -->
</section><!-- /section -->
