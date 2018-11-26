<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-primary text-light mb-3">
    <div class="container text-center">
        <h1 class="display-3">{{ $page->title }}</h1>

        <p class="lead">
            Checkout the variety of features we offer below.
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
        @if($features->count())
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
        @else
            <p class="lead">Whoops! Something is wrong.</p>
        @endif
    </div><!-- /.container -->
</section><!-- /section -->
