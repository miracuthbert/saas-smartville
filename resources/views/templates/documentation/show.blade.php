<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-primary text-light mb-3">
    <div class="container text-center">
        <h3 class="display-4">{{ $tutorial->title }}</h3>
    </div><!-- /.container -->
</div><!-- /.jumbotron -->

<!-- Content -->
<section class="mb-5 py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <ul class="list-unstyled mb-5">
                    @if($tutorial->parent)
                        <li>
                            <a href="{{ route('documentation.show', $tutorial->parent->slug) }}">
                                <h5>{{ $tutorial->parent->title }}</h5>
                            </a>

                            @if($tutorial->parent->children->count())
                                <ul class="nav flex-column nav-pills">
                                    @foreach($tutorial->parent->children as $topic)
                                        @if($topic->id == $tutorial->id)
                                            <li class="nav-item">
                                                <a href="{{ route('documentation.show', $topic->slug) }}"
                                                   class="nav-link active">
                                                    {{ $topic->title }}
                                                </a>

                                                @if($topic->children->count())
                                                    <ul class="list-unstyled ml-2 px-2">
                                                        @foreach($topic->children as $subTopic)
                                                            @if($subTopic->children->count())
                                                                <li class="nav-item">
                                                                    <a href="{{ route('documentation.show', $subTopic->slug) }}"
                                                                       class="nav-link">
                                                                        {{ $subTopic->title }}
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li class="nav-item">
                                                                    <a href="#tutSec{{ $subTopic->id }}"
                                                                       class="nav-link text-dark">
                                                                        <i class="icon-link"></i> {{ $subTopic->title }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul><!-- /.list-unstyled -->
                                                @endif
                                            </li><!-- /.nav-item -->
                                        @else
                                            <li class="nav-item">
                                                <a href="{{ route('documentation.show', $topic->slug) }}"
                                                   class="nav-link">
                                                    {{ $topic->title }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul><!-- /.nav -->
                            @endif
                        </li>
                    @elseif($tutorial->children->count())
                        <li>
                            <h5>{{ $tutorial->title }}</h5>
                            <ul class="nav flex-column nav-pill">
                                @foreach($tutorial->children as $topic)
                                    <li class="nav-item">
                                        <a href="{{ route('documentation.show', $topic->slug) }}" class="nav-link">
                                            {{ $topic->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul><!-- /.list-unstyled -->
                        </li>
                    @endif
                </ul><!-- /.list-unstyled -->
            </div><!-- /.col-md-3 -->
            <div class="col-md-9">
                @if($tutorial->overview)
                    <p class="lead">
                        {{ $tutorial->overview }}
                    </p>
                    <hr>
                @endif

                @if($tutorial->body)
                    <article>{{ $tutorial->body }}</article>
                    <hr>
                @endif

                <ul class="list-unstyled mb-3">
                    @foreach($tutorial->children as $topic)
                        <li class="media mb-3">
                            <div class="media-body">
                                @if($topic->children->count())
                                    <h5>
                                        <a href="{{ route('documentation.show', $topic->slug) }}">
                                            {{ $topic->title }}
                                        </a>
                                    </h5>
                                @else
                                    <h5 id="tutSec{{ $topic->id }}">{{ $topic->title }}</h5>

                                    @if($topic->overview)
                                        <p class="mb-2">{{ $topic->overview }}</p>
                                    @endif

                                    <article>{{ $topic->body }}</article>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul><!-- /.list-unstyled -->

                <nav class="mt-2" aria-label="tuts-navigation">
                    <ul class="pagination pagination-lg justify-content-center">
                        @if($prevTutorial)
                            <li class="page-item">
                                <a href="{{ route('documentation.show', $prevTutorial->slug) }}" class="page-link"
                                   aria-label="previous">
                                    <span>&laquo;</span> {{ $prevTutorial->title }}
                                </a>
                            </li>
                        @endif

                        @if($nextTutorial)
                            <li class="page-item">
                                <a href="{{ route('documentation.show', $nextTutorial->slug) }}" class="page-link"
                                   aria-label="next">
                                    {{ $nextTutorial->title }} <span>&raquo;</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div><!-- /.col-9 -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section><!-- /section -->
