@extends('account.dashboard.layouts.default')

@section('dashboard.content')
    <div>
        <!-- New Property Issue -->
        <div class="mb-3">
            <new-property-issue endpoint="{{ route('account.issues.index') }}"
                                :is-expanded="false"
                                :autofocus="true"/>
        </div>

        <div>
            <issues endpoint="{{ route('account.issues.index') }}" :show-heading="true"/>
        </div>
    </div>
@endsection
