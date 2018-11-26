<!-- Jumbotron -->
<div class="jumbotron jumbotron-fluid bg-success text-light mb-3">
    <div class="container text-center">
        <h1 class="display-3">{{ $page->title }}</h1>

        <p class="lead">
            Get help from our team or report an issue.
        </p>
        <div class="mb-3"></div>
    </div><!-- /.container -->
</div><!-- /.jumbotron -->

<!-- Contact -->
<section class="mb-5 py-2">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="py-3">
                    <contact endpoint="{{ route('support.contact.store') }}"/>
                </div>
                <div class="mb-5"></div>
            </div>
        </div>
    </div><!-- /.container -->
</section><!-- /section -->
