@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <h4>My Dashboard</h4>
    <hr>

    <section class="mb-3">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="pills-issues-tab" data-toggle="pill" href="#pills-issues" role="tab"
                   aria-controls="pills-issues" aria-selected="false">
                    <i class="icon-exclamation"></i> Issues
                </a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <!-- Issues -->
            <div class="tab-pane fade" id="pills-issues" role="tabpanel" aria-labelledby="pills-issues-tab">
            @if(env('APP_ENV') === 'local')
                <!-- New Property Issue -->
                    <div class="mb-3">
                        <new-property-issue endpoint="{{ route('account.issues.index') }}"
                                            :is-expanded="false"
                                            :autofocus="true"/>
                    </div>

                    <div>
                        <issues endpoint="{{ route('account.issues.index') }}"
                                :show-heading="false"/>
                    </div>
                @else
                    <div class="alert alert-info">
                        Feature coming soon.
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
