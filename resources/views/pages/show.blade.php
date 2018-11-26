@extends($page->layoutPath)

@section('title', page_title($pageTitle))

@section('content')
    @if($page->view)
        {!! $page->view->render() !!}
    @else
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="clearfix">
                        {!! $page->bodyHtml !!}
                    </div>
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    @endif
@endsection