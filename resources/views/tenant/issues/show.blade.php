@extends('tenant.layouts.default')

@section('title', page_title($issue->title . ' | Company Issues '))

@section('tenant.breadcrumb')
    <li class="breadcrumb-item">Dashboard</li>
    <li class="breadcrumb-item">Issues</li>
    <li class="breadcrumb-item active">{{ $issue->title }}</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <h4>{{ $issue->title }}</h4>

            @if($issue->isClosed())
                <div class="alert alert-warning">
                    Issue was closed
                    <time>{{ $issue->local_closed_at['for_human'] }}</time>
                </div>
            @endif

            <div>
                <small>
                    Posted by <span class="text-muted">{{ $issue->owner ? 'You' : $issue->user->name }}</span>
                    <time>{{ $issue->local_created_at['for_human'] }}</time>
                </small>
            </div>
            <div>
                <issue-edit-status :issue="{{ $issue }}"/>
            </div>
            <hr>

            <article class="mb-3">
                <issue-edit :issue="{{ $issue }}"/>
            </article>

            <div class="topics mb-3">
                <span class="mr-1"><i class="icon-tag"></i></span>

                @foreach($topics as $topic)
                    <span class="topic badge badge-secondary py-1 px-2 mr-1">
                {{ $topic['name'] }}
            </span>
                @endforeach
            </div>

            <ul class="list-inline">
                @if($issue->owner && !$issue->isClosed())
                    <li class="list-inline-item">
                        <a href="{{ route('account.issues.destroy', $issue) }}"
                           onclick="event.preventDefault(); document.getElementById('delete-issue-form-{{ $issue->id }}').submit()">
                            Delete
                        </a>
                        <form method="POST" action="{{ route('account.issues.destroy', $issue) }}"
                              id="delete-issue-form-{{ $issue->id }}"
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </li>
                @endif
                <li class="list-inline-item">
                    <issue-close :issue="{{ $issue }}"
                                 :close-time="{{ json_encode($issue->local_closed_at) }}"
                                 :wrapped="true"/>
                </li>
            </ul>
        </div>
    </div>
    <hr>

    <div class="card mb-3">
        <div class="card-body">
            <comments endpoint="{{ route('issues.comments.index', $issue) }}"/>
        </div>
    </div>
@endsection
