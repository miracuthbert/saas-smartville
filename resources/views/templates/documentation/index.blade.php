<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-dark text-light mb-3">
    <div class="container text-center">
        <h1 class="display-3">{{ $page->title }}</h1>

        <p class="lead">
            Simple and short guides to help you get the most out of the app.
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
<section class="mb-5 py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h4>Tutorials</h4>
                <ul class="list-unstyled mb-5">
                    @foreach($tutorials as $tutorial)
                        <li class="nav-item{{ !$loop->last ? ' border-bottom' : '' }}">
                            <div class="btn-group">
                                <a href="{{ route('documentation.show', $tutorial->slug) }}" class="nav-link"
                                   role="button">
                                    <h5>{{ $tutorial->title }}</h5>
                                </a>
                                <button class="btn btn-link" data-toggle="collapse"
                                        data-target="#tutCollapse{{ $tutorial->id }}">
                                    <h5><i class="fa fa-angle-down"></i></h5>
                                </button>
                            </div>
                            @if($tutorial->children->count())
                                <div class="nav flex-column my-0 ml-1 collapse" id="tutCollapse{{ $tutorial->id }}">
                                    @foreach($tutorial->children as $topic)
                                        <a href="{{ route('documentation.show', $topic->slug) }}"
                                           class="nav-item nav-link">
                                            {{ $topic->title }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-9">
                @if($selectedTutorials->count())
                    <h4>Recently added tutorials</h4>
                    <hr>

                    <ul class="list-unstyled">
                        @foreach($selectedTutorials as $tutorial)
                            <li class="media mb-3">
                                <div class="media-body">
                                    <h5 class="mt-0 mb-2">
                                        <a href="{{ route('documentation.show', $tutorial->slug) }}">
                                            {{ $tutorial->title }}
                                        </a>
                                    </h5>

                                    @if($tutorial->parent)
                                        <p class="small mb-2">
                                            Part of <span class="text-muted">{{ $tutorial->parent->title }}</span>
                                        </p>
                                    @endif

                                    @if($tutorial->overview)
                                        <article class="mb-3">
                                            {{ str_limit($tutorial->overview, 160) }}
                                        </article>
                                    @endif

                                    <ul class="list-inline mt-2">
                                        <li class="list-inline-item">
                                            Added
                                            <time>{{ $tutorial->created_at->diffForHumans() }}</time>
                                        </li>
                                        @if($tutorial->edited_at)
                                            <li class="list-inline-item">Edited</li>
                                        @endif
                                    </ul>
                                </div>
                            </li>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div><!-- /.container -->
</section><!-- /section -->
